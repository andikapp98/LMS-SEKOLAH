<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            // Mata Pelajaran Umum
            ['kode_mapel' => 'AQ-AK', 'nama_mapel' => 'Aqidah Akhlak'],
            ['kode_mapel' => 'BI', 'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'FQH', 'nama_mapel' => 'Fiqih'],
            ['kode_mapel' => 'BING', 'nama_mapel' => 'Bahasa Inggris dan Bahasa Asing Lainnya'],
            ['kode_mapel' => 'PP', 'nama_mapel' => 'Pendidikan Pancasila'],
            ['kode_mapel' => 'IPAS', 'nama_mapel' => 'IPAS'],
            ['kode_mapel' => 'BJW', 'nama_mapel' => 'Bahasa Jawa'],
            ['kode_mapel' => 'INF', 'nama_mapel' => 'Informatika'],
            ['kode_mapel' => 'SEJ', 'nama_mapel' => 'Sejarah'],
            ['kode_mapel' => 'AQH', 'nama_mapel' => 'Al Quran Hadits'],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'],
            ['kode_mapel' => 'SBD', 'nama_mapel' => 'Seni Budaya'],
            ['kode_mapel' => 'BJPG', 'nama_mapel' => 'Bahasa Jepang'],
            
            // Teknik Mesin
            ['kode_mapel' => 'DTM-1', 'nama_mapel' => 'Dasar Teknik Mesin-1'],
            ['kode_mapel' => 'DTM-2', 'nama_mapel' => 'Dasar Teknik Mesin-2'],
            ['kode_mapel' => 'TPF', 'nama_mapel' => 'Teknik Pemesinan Frais'],
            ['kode_mapel' => 'TPB', 'nama_mapel' => 'Teknik Pemesinan Bubut'],
            ['kode_mapel' => 'TPG', 'nama_mapel' => 'Teknik Pemesinan Gerinda'],
            ['kode_mapel' => 'TPN', 'nama_mapel' => 'Teknik Pemesinan Nonkonvensional'],
            ['kode_mapel' => 'GTM', 'nama_mapel' => 'Gambar Teknik Manufaktur'],
            ['kode_mapel' => 'TPLS', 'nama_mapel' => 'Teknik Pengelasan'],
            ['kode_mapel' => 'TMMI', 'nama_mapel' => 'Teknik Mekanik Mesin Industri'],
            
            // Teknik Otomotif
            ['kode_mapel' => 'PMKR', 'nama_mapel' => 'Pemeliharaan Mesin Kendaraan Ringan'],
            ['kode_mapel' => 'PKKR', 'nama_mapel' => 'Pemeliharaan Kelistrikan Kendaraan Ringan'],
            ['kode_mapel' => 'PSPKR', 'nama_mapel' => 'Pemeliharaan Sasis dan Pemindah Tenaga Kendaraan Ringan'],
            ['kode_mapel' => 'TBKR', 'nama_mapel' => 'Teknik Bodi Kendaraan Ringan'],
            ['kode_mapel' => 'DTO-1', 'nama_mapel' => 'Dasar-Dasar Teknik Otomotif-1'],
            ['kode_mapel' => 'DTO-2', 'nama_mapel' => 'Dasar-Dasar Teknik Otomotif-2'],
            ['kode_mapel' => 'TSM', 'nama_mapel' => 'Teknik Sepeda Motor'],
            
            // Desain Komunikasi Visual
            ['kode_mapel' => 'DDKV-1', 'nama_mapel' => 'Dasar-Dasar Desain Komunikasi Visual-1'],
            ['kode_mapel' => 'DDKV-2', 'nama_mapel' => 'Dasar-dasar Desain Komunikasi Visual-2'],
            ['kode_mapel' => 'DDK', 'nama_mapel' => 'Dasar Desain dan Komunikasi'],
            ['kode_mapel' => 'KD', 'nama_mapel' => 'Karya Desain'],
            ['kode_mapel' => 'PPD', 'nama_mapel' => 'Proses Produksi Desain'],
            ['kode_mapel' => 'ANM', 'nama_mapel' => 'Animasi'],
            ['kode_mapel' => 'DB', 'nama_mapel' => 'Design Brief'],
            ['kode_mapel' => 'PF', 'nama_mapel' => 'Produksi Film'],
            ['kode_mapel' => 'PLD', 'nama_mapel' => 'Perangkat Lunak desain'],
            
            // Manajemen Perkantoran
            ['kode_mapel' => 'DMPLB-1', 'nama_mapel' => 'Dasar Manajemen Perkantoran dan Layanan Bisnis-1'],
            ['kode_mapel' => 'DMPLB-2', 'nama_mapel' => 'Dasar Manajemen Perkantoran dan Layanan Bisnis-2'],
            ['kode_mapel' => 'KK', 'nama_mapel' => 'Komunikasi Kantor'],
            ['kode_mapel' => 'PHK', 'nama_mapel' => 'Pengelolaan Humas dan Keprotokolan'],
            ['kode_mapel' => 'APJ', 'nama_mapel' => 'Akuntansi Perusahaan Jasa'],
            ['kode_mapel' => 'PKA', 'nama_mapel' => 'Pengelolaan Kearsipan'],
            ['kode_mapel' => 'BK', 'nama_mapel' => 'Bisnis dan Keuangan'],
            ['kode_mapel' => 'BD', 'nama_mapel' => 'Bisnis Digital'],
            ['kode_mapel' => 'PSDM', 'nama_mapel' => 'Pengelolaan SDM'],
            ['kode_mapel' => 'AU', 'nama_mapel' => 'Administrasi Umum'],
            ['kode_mapel' => 'PSP', 'nama_mapel' => 'Pengelolaan Sarana dan Prasarana'],
            ['kode_mapel' => 'TP', 'nama_mapel' => 'Teknologi Perkantoran'],
            
            // Kimia Analisis
            ['kode_mapel' => 'DKA-1', 'nama_mapel' => 'Dasar Kimia Analisis-1'],
            ['kode_mapel' => 'DKA-2', 'nama_mapel' => 'Dasar Kimia Analisis-2'],
            ['kode_mapel' => 'AM', 'nama_mapel' => 'Analisis Mikrobiologi'],
            ['kode_mapel' => 'AKI', 'nama_mapel' => 'Analisis Kimia Instrumen'],
            ['kode_mapel' => 'PL', 'nama_mapel' => 'Pengolahan Limbah'],
            ['kode_mapel' => 'AKK', 'nama_mapel' => 'Analisis Kuantitatif Konvensional'],
            ['kode_mapel' => 'AP', 'nama_mapel' => 'Analisis Proksimat'],
            ['kode_mapel' => 'PIK', 'nama_mapel' => 'Proses Industri Kimia'],
            
            // Logistik
            ['kode_mapel' => 'DTL-1', 'nama_mapel' => 'Dasar Teknik Logistik-1'],
            ['kode_mapel' => 'DTL-2', 'nama_mapel' => 'Dasar Teknik Logistik-2'],
            ['kode_mapel' => 'TPB-L', 'nama_mapel' => 'Teknik Pengadaan Barang'],
            ['kode_mapel' => 'PI', 'nama_mapel' => 'Perdagangan Internasional'],
            ['kode_mapel' => 'SP', 'nama_mapel' => 'Sistem Pergudangan'],
            ['kode_mapel' => 'TPgB', 'nama_mapel' => 'Teknik Pengiriman Barang'],
            ['kode_mapel' => 'SIL', 'nama_mapel' => 'Sistem Informasi Logistik'],
            ['kode_mapel' => 'BR', 'nama_mapel' => 'Bisnis Retail'],
            
            // Lainnya
            ['kode_mapel' => 'PKK', 'nama_mapel' => 'Projek Kreatif dan Kewirausahaan'],
            ['kode_mapel' => 'CI', 'nama_mapel' => 'Culture Industry'],
        ];

        $now = Carbon::now();
        
        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['kode_mapel' => $course['kode_mapel']],
                [
                    'nama_mapel' => $course['nama_mapel'],
                    'teacher_id' => null, // Will be assigned later
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $this->command->info('✅ ' . count($courses) . ' mata pelajaran berhasil ditambahkan!');
    }
}
