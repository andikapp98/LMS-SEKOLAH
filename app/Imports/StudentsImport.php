<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Helpers\ClassHelper;
use Carbon\Carbon;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // Normalisasi format kelas
        $kelas = isset($row['kelas']) ? ClassHelper::normalizeClass($row['kelas']) : null;

        return new Student([
            'nis' => $row['nis'],
            'nisn' => $row['nisn'] ?? null,
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => isset($row['tanggal_lahir']) ? $this->transformDate($row['tanggal_lahir']) : null,
            'alamat' => $row['alamat'] ?? null,
            'kelas' => $kelas,
            'no_hp' => $row['no_hp'] ?? null,
            'email' => $row['email'] ?? null,
            'nama_wali' => $row['nama_wali'] ?? null,
            'no_hp_wali' => $row['no_hp_wali'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|unique:students,nis',
            'nisn' => 'nullable|unique:students,nisn',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => function ($attribute, $value, $fail) {
                if (!empty($value) && !ClassHelper::validateClass($value)) {
                    $examples = implode(', ', ClassHelper::getExamples());
                    $fail("Format kelas tidak valid. Contoh: {$examples}");
                }
            },
            'email' => 'nullable|email|unique:students,email',
        ];
    }

    private function transformDate($value)
    {
        if (is_numeric($value)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        }
        
        return Carbon::parse($value);
    }
}
