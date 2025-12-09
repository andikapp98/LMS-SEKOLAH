<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            'Aqidah Akhlak',
            'Dasar Teknik Mesin-1',
            'Bahasa Indonesia',
            'Matematika',
            'Fiqih',
            'Bahasa Inggris dan Bahasa Asing Lainnya',
            'Teknik Pemesinan Frais',
            'Teknik Pemesinan Bubut',
            'Pendidikan Pancasila',
            'Pemeliharaan Mesin Kendaraan Ringan',
            'Pemeliharaan Kelistrikan Kendaraan Ringan',
            'Projek Kreatif dan Kewirausahaan',
            'Gambar Teknik Manufaktur',
            'IPAS',
            'Bahasa Jawa',
            'Dasar Kimia Analisis-2',
            'Analisis Mikrobiologi',
            'Teknik Pemesinan Gerinda',
            'Pemeliharaan Sasis dan Pemindah Tenaga Kendaraan Ringan',
            'Informatika',
            'Pengelolaan SDM',
            'Teknik Pengelasan',
            'Teknik Mekanik Mesin Industri',
            'Sejarah',
            'Dasar-Dasar Teknik Otomotif-2',
            'Dasar-dasar Desain Komunikasi Visual-2',
            'Dasar Desain dan Komunikasi',
            'Karya Desain',
            'Proses Produksi Desain',
            'Animasi',
            'Al Quran Hadits',
            'Teknik Bodi Kendaraan Ringan',
            'Teknik Pemesinan Nonkonvensional',
            'Dasar Manajemen Perkantoran dan Layanan Bisnis-2',
            'Komunikasi Kantor',
            'Pengelolaan Humas dan Keprotokolan',
            'Akuntansi Perusahaan Jasa',
            'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            'Dasar-Dasar Desain Komunikasi Visual-1',
            'Design Brief',
            'Produksi Film',
            'Seni Budaya',
            'Dasar Manajemen Perkantoran dan Layanan Bisnis-1',
            'Pengelolaan Kearsipan',
            'Bisnis dan Keuangan',
            'Bisnis Digital',
            'Dasar Kimia Analisis-1',
            'Analisis Kimia Instrumen',
            'Pengolahan Limbah',
            'Analisis Kuantitatif Konvensional',
            'Analisis Proksimat',
            'Proses Industri Kimia',
            'Teknologi Perkantoran',
            'Administrasi Umum',
            'Pengelolaan Sarana dan Prasarana',
            'Dasar Teknik Logistik-1',
            'Teknik Pengadaan Barang',
            'Perdagangan Internasional',
            'Dasar Teknik Mesin-2',
            'Dasar Teknik Logistik-2',
            'Sistem Pergudangan',
            'Teknik Pengiriman Barang',
            'Sistem Informasi Logistik',
            'Bisnis Retail',
            'Perangkat Lunak desain',
            'Bahasa Inggris dan Bahasa Asing Lainnya',
            'Bahasa Jepang',
            'Dasar-Dasar Teknik Otomotif-1',
            'Culture Industry',
            'Teknik Sepeda Motor',
        ];

        // Remove duplicates
        $subjects = array_unique($subjects);
        sort($subjects);

        $coursesData = [];
        $timestamp = now();

        foreach ($subjects as $index => $subject) {
            // Generate kode_mapel
            $kodeMapel = 'MP-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            $coursesData[] = [
                'kode_mapel' => $kodeMapel,
                'nama_mapel' => $subject,
                'teacher_id' => null,
                'kelas' => null,
                'jam_per_minggu' => 2,
                'deskripsi' => null,
                'semester' => 'ganjil',
                'tahun_ajaran' => '2025/2026',
                'status' => 'aktif',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($coursesData, 50) as $chunk) {
            DB::table('courses')->insert($chunk);
        }

        $this->command->info('Successfully imported ' . count($coursesData) . ' subjects to database.');
    }
}
