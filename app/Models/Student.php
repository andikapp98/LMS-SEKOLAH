<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nis',
        'nisn',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'kelas',
        'no_hp',
        'email',
        'nama_wali',
        'no_hp_wali',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}
