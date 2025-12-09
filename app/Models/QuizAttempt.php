<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'student_id',
        'attempt_number',
        'started_at',
        'submitted_at',
        'score',
        'points_earned',
        'total_points',
        'answers',
        'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
    ];

    /**
     * Get the quiz
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Calculate and save the score
     */
    public function calculateScore()
    {
        $quiz = $this->quiz()->with('questions')->first();
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            
            $studentAnswer = $this->answers[$question->id] ?? null;
            
            $isCorrect = $question->checkAnswer($studentAnswer);
            
            // For essay questions, null means needs manual grading
            if ($isCorrect === true) {
                $earnedPoints += $question->points;
            }
        }

        $this->total_points = $totalPoints;
        $this->points_earned = $earnedPoints;
        $this->score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
        
        // Check if all questions are auto-gradable
        $hasEssay = $quiz->questions()->where('type', 'essay')->exists();
        $this->status = $hasEssay ? 'submitted' : 'graded';
        
        $this->save();

        return $this->score;
    }

    /**
     * Check if attempt is passed
     */
    public function isPassed()
    {
        if (!$this->score) {
            return false;
        }

        return $this->score >= $this->quiz->passing_score;
    }

    /**
     * Get time taken in minutes
     */
    public function getTimeTakenAttribute()
    {
        if (!$this->submitted_at || !$this->started_at) {
            return null;
        }

        return $this->started_at->diffInMinutes($this->submitted_at);
    }

    /**
     * Check if attempt is still in progress
     */
    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if time limit exceeded
     */
    public function isTimeExpired()
    {
        if (!$this->quiz->duration) {
            return false;
        }

        $elapsed = $this->started_at->diffInMinutes(now());
        return $elapsed > $this->quiz->duration;
    }
}
