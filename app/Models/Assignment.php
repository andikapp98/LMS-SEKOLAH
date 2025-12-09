<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'title',
        'description',
        'course',        // Keep for backward compatibility
        'course_id',     // New foreign key
        'kelas',         // Target class
        'due_date',
        'status',
        'created_by',
        'file_path'
    ];

    protected $casts = [
        'due_date' => 'date',
        'kelas' => 'array'
    ];

    /**
     * Relationship: Assignment belongs to a course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relationship: Assignment created by a user
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if the assignment belongs to a specific teacher
     */
    public function belongsToTeacher($teacherId)
    {
        if (!$this->course_id) {
            return false;
        }
        
        return $this->course->teachers()->where('teachers.id', $teacherId)->exists();
    }
}
