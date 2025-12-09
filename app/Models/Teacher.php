<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_guru',
        'nik',
        'nama',
        'email',
        'no_hp',
        'mata_pelajaran',
        'status',
        'alamat',
        'pendidikan_terakhir',
    ];

    /**
     * Relationship: Teacher has many courses (many-to-many)
     * A teacher can teach multiple courses
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_teacher')
            ->withTimestamps();
    }

    /**
     * Relationship: Teacher has one user account
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Scope: Only active teachers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
