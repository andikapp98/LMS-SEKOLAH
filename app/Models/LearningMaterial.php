<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $table = 'learning_media';

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'uploaded_by',
        'type',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'url',
        'kelas',
        'visibility',
        'download_count'
    ];

    protected $casts = [
        'kelas' => 'array',
        'download_count' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Get the course that owns the learning material
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the user who uploaded the material
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope for filtering by course
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope for filtering by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for public materials
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
