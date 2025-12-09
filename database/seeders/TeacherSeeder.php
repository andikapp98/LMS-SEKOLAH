<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = [
            [
                'kode_guru' => 'GRU001',
                'nip' => '197001011998031001',
                'nama' => 'Drs. Ahmad Fauzi, M.Pd',
                'email' => 'ahmad.fauzi@smksyasmu.sch.id',
                'no_hp' => '081234567890',
                'mata_pelajaran' => 'Matematika',
                'pendidikan_terakhir' => 'S2 Pendidikan Matematika',
                'status' => 'aktif',
            ],
            [
                'kode_guru' => 'GRU002',
                'nip' => '198505102009032002',
                'nama' => 'Siti Nurhaliza, S.Pd',
                'email' => 'siti.nurhaliza@smksyasmu.sch.id',
                'no_hp' => '082345678901',
                'mata_pelajaran' => 'Bahasa Indonesia',
                'pendidikan_terakhir' => 'S1 Pendidikan Bahasa Indonesia',
                'status' => 'aktif',
            ],
            [
                'kode_guru' => 'GRU003',
                'nip' => '199203152015031003',
                'nama' => 'Budi Santoso, S.T',
                'email' => 'budi.santoso@smksyasmu.sch.id',
                'no_hp' => '083456789012',
                'mata_pelajaran' => 'Teknik Pemesinan',
                'pendidikan_terakhir' => 'S1 Teknik Mesin',
                'status' => 'aktif',
            ],
            [
                'kode_guru' => 'GRU004',
                'nip' => '198807202010032004',
                'nama' => 'Dewi Lestari, S.Pd',
                'email' => 'dewi.lestari@smksyasmu.sch.id',
                'no_hp' => '084567890123',
                'mata_pelajaran' => 'Bahasa Inggris',
                'pendidikan_terakhir' => 'S1 Pendidikan Bahasa Inggris',
                'status' => 'aktif',
            ],
            [
                'kode_guru' => 'GRU005',
                'nip' => '199506252019031005',
                'nama' => 'Eko Prasetyo, S.Kom',
                'email' => 'eko.prasetyo@smksyasmu.sch.id',
                'no_hp' => '085678901234',
                'mata_pelajaran' => 'Desain Komunikasi Visual',
                'pendidikan_terakhir' => 'S1 Ilmu Komputer',
                'status' => 'aktif',
            ],
            [
                'kode_guru' => 'GRU006',
                'nip' => '199012102016032006',
                'nama' => 'Rina Wijaya, S.Si',
                'email' => 'rina.wijaya@smksyasmu.sch.id',
                'no_hp' => '086789012345',
                'mata_pelajaran' => 'Kimia',
                'pendidikan_terakhir' => 'S1 Kimia',
                'status' => 'aktif',
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }
    }
}
