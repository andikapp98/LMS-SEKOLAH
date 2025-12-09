<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'created_by',
        'kelas',
        'duration',
        'max_attempts',
        'passing_score',
        'available_from',
        'available_until',
        'randomize_questions',
        'show_correct_answers',
        'status'
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'randomize_questions' => 'boolean',
        'show_correct_answers' => 'boolean',
        'kelas' => 'array',
    ];

    /**
     * Check if quiz is accessible for a specific student class
     */
    public function isForStudent($studentKelas)
    {
        // If no kelas specified, it's for all classes
        if (empty($this->kelas)) {
            return true;
        }
        
        // Check if student's class is in the target classes
        return in_array($studentKelas, $this->kelas);
    }

    /**
     * Get the course that owns the quiz
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who created the quiz
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all questions for this quiz
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get all attempts for this quiz
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Check if quiz is currently available
     */
    public function isAvailable()
    {
        $now = now();
        
        if ($this->status !== 'published') {
            return false;
        }
        
        if ($this->available_from && $now->lt($this->available_from)) {
            return false;
        }
        
        if ($this->available_until && $now->gt($this->available_until)) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if user can attempt this quiz
     */
    public function canAttempt($userId)
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $attemptCount = $this->attempts()
            ->where('student_id', $userId)
            ->where('status', '!=', 'in_progress')
            ->count();
        
        return $attemptCount < $this->max_attempts;
    }

    /**
     * Get user's attempts
     */
    public function userAttempts($userId)
    {
        return $this->attempts()
            ->where('student_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get total points for this quiz
     */
    public function getTotalPointsAttribute()
    {
        return $this->questions()->sum('points');
    }
}
