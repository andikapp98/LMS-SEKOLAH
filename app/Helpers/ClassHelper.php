<?php

namespace App\Helpers;

class ClassHelper
{
    // Daftar jurusan yang tersedia di SMKS Yasmu Gresik
    public static $jurusan = [
        'TPM' => 'Teknik Pemesinan',
        'TKR' => 'Teknik Kendaraan Ringan',
        'TL' => 'Teknik Logistik',
        'DKV' => 'Desain Komunikasi Visual',
        'APL' => 'Analisis Pengujian Laboratorium',
        'MPK' => 'Manajemen Perkantoran',
    ];

    // Tingkat kelas yang tersedia
    public static $tingkat = ['10', '11', '12', 'X', 'XI', 'XII'];

    /**
     * Validasi format kelas
     * Format yang diterima:
     * - "10 Teknik Pemesinan 1" atau "10 TPM 1"
     * - "TPM 1" (tanpa tingkat)
     * - "11 Teknik Pemesinan 2"
     */
    public static function validateClass($kelas)
    {
        if (empty($kelas)) {
            return true; // Kelas opsional
        }

        $kelas = trim($kelas);

        // Pattern 1: "10 Teknik Pemesinan 1" atau "10 TPM 1"
        if (preg_match('/^(10|11|12|X|XI|XII)\s+(.+?)\s+([1-4])$/i', $kelas, $matches)) {
            $jurusan = trim($matches[2]);
            
            // Cek apakah jurusan valid (singkatan atau nama lengkap)
            if (array_key_exists(strtoupper($jurusan), self::$jurusan) || 
                in_array($jurusan, self::$jurusan)) {
                return true;
            }
        }

        // Pattern 2: "TPM 1" (tanpa tingkat)
        if (preg_match('/^([A-Z]{2,5})\s+([1-4])$/i', $kelas, $matches)) {
            $jurusan = strtoupper($matches[1]);
            
            if (array_key_exists($jurusan, self::$jurusan)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Normalisasi format kelas
     * Ubah berbagai format menjadi format standar
     */
    public static function normalizeClass($kelas)
    {
        if (empty($kelas)) {
            return null;
        }

        $kelas = trim($kelas);

        // Pattern: "10 Teknik Pemesinan 1" atau "10 TPM 1"
        if (preg_match('/^(10|11|12|X|XI|XII)\s+(.+?)\s+([1-4])$/i', $kelas, $matches)) {
            $tingkat = strtoupper($matches[1]);
            $jurusan = trim($matches[2]);
            $rombel = $matches[3];
            
            // Konversi tingkat romawi ke angka
            $tingkat = str_replace(['X', 'XI', 'XII'], ['10', '11', '12'], $tingkat);
            
            // Jika jurusan adalah singkatan, gunakan singkatan
            $jurusanKey = strtoupper($jurusan);
            if (array_key_exists($jurusanKey, self::$jurusan)) {
                return "{$tingkat} {$jurusanKey} {$rombel}";
            }
            
            // Jika jurusan adalah nama lengkap, cari singkatannya
            $singkatan = array_search($jurusan, self::$jurusan);
            if ($singkatan !== false) {
                return "{$tingkat} {$singkatan} {$rombel}";
            }
            
            // Jika tidak ditemukan, gunakan apa adanya
            return "{$tingkat} {$jurusan} {$rombel}";
        }

        // Pattern: "TPM 1" (tanpa tingkat)
        if (preg_match('/^([A-Z]{2,5})\s+([1-4])$/i', $kelas, $matches)) {
            $jurusan = strtoupper($matches[1]);
            $rombel = $matches[2];
            
            if (array_key_exists($jurusan, self::$jurusan)) {
                return "{$jurusan} {$rombel}";
            }
        }

        return $kelas;
    }

    /**
     * Dapatkan daftar jurusan
     */
    public static function getJurusan()
    {
        return self::$jurusan;
    }

    /**
     * Dapatkan nama lengkap jurusan dari singkatan
     */
    public static function getJurusanName($singkatan)
    {
        return self::$jurusan[strtoupper($singkatan)] ?? $singkatan;
    }

    /**
     * Generate contoh format kelas
     */
    public static function getExamples()
    {
        return [
            '10 TPM 1',
            '10 Teknik Pemesinan 1',
            '11 TKR 2',
            '11 Teknik Kendaraan Ringan 2',
            '12 DKV 1',
            '12 Desain Komunikasi Visual 1',
            'X TL 1',
            'XI APL 2',
            'XII MPK 1',
        ];
    }
}
