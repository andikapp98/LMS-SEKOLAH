<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'explanation',
        'points',
        'order',
        'options',
        'correct_answer'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Get the quiz that owns this question
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Check if the given answer is correct
     */
    public function checkAnswer($answer)
    {
        switch ($this->type) {
            case 'multiple_choice':
            case 'true_false':
                return $this->correct_answer === $answer;
            
            case 'short_answer':
                // Case-insensitive comparison
                return strtolower(trim($this->correct_answer)) === strtolower(trim($answer));
            
            case 'essay':
                // Essay needs manual grading
                return null;
            
            default:
                return false;
        }
    }

    /**
     * Get formatted options for display
     */
    public function getFormattedOptionsAttribute()
    {
        if (!$this->options) {
            return [];
        }

        if ($this->type === 'true_false') {
            return [
                ['value' => 'true', 'label' => 'Benar'],
                ['value' => 'false', 'label' => 'Salah']
            ];
        }

        return $this->options;
    }
}
