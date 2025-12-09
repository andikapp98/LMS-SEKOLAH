<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'teacher_id',
        'kelas',
        'jam_per_minggu',
        'deskripsi',
        'semester',
        'tahun_ajaran',
        'status',
    ];

    /**
     * Relationship: Course belongs to many teachers (many-to-many)
     * A course can be taught by multiple teachers
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'course_teacher')
            ->withTimestamps();
    }

    /**
     * Relationship: Course has many assignments
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Scope: Only active courses
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Relationship: Course has many learning media
     */
    public function learningMedia()
    {
        return $this->hasMany(LearningMedia::class);
    }
}
