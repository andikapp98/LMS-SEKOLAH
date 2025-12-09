<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Rules\ValidClass;
use App\Helpers\ClassHelper;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('created_at', 'desc')->paginate(20);
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|unique:students,nis',
            'nisn' => 'nullable|unique:students,nisn',
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kelas' => ['nullable', 'string', new ValidClass()],
            'no_hp' => 'nullable|string',
            'email' => 'nullable|email|unique:students,email',
            'nama_wali' => 'nullable|string',
            'no_hp_wali' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Normalisasi format kelas sebelum disimpan
        $data = $request->all();
        if (!empty($data['kelas'])) {
            $data['kelas'] = ClassHelper::normalizeClass($data['kelas']);
        }

        $student = Student::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan',
            'data' => $student
        ], 201);
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nis' => 'required|unique:students,nis,' . $id,
            'nisn' => 'nullable|unique:students,nisn,' . $id,
            'nama' => 'required|string',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kelas' => ['nullable', 'string', new ValidClass()],
            'no_hp' => 'nullable|string',
            'email' => 'nullable|email|unique:students,email,' . $id,
            'nama_wali' => 'nullable|string',
            'no_hp_wali' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Normalisasi format kelas sebelum disimpan
        $data = $request->all();
        if (!empty($data['kelas'])) {
            $data['kelas'] = ClassHelper::normalizeClass($data['kelas']);
        }

        $student->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diperbarui',
            'data' => $student
        ]);
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil dihapus'
        ]);
    }

    public function importExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:5120' // Max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            // Jika file adalah .xls (biasanya format Nominatif lama), coba gunakan NominatifImportService
            if ($extension === 'xls') {
                $service = new \App\Services\NominatifImportService();
                $result = $service->import($file->getPathname());

                if (!empty($result['errors'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Import selesai dengan beberapa catatan',
                        'data' => $result,
                        'errors' => $result['errors'] // Tampilkan error per baris
                    ], 422);
                }

                return response()->json([
                    'success' => true,
                    'message' => "Berhasil mengimport {$result['count']} siswa dari kelas {$result['kelas_detected']}",
                    'data' => $result
                ]);
            }

            // Untuk .xlsx atau .csv, gunakan import standar (Maatwebsite)
            Excel::import(new StudentsImport, $file);

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diimport dari Excel'
            ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            return response()->json([
                'success' => false,
                'message' => 'Terdapat kesalahan dalam data Excel',
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            // Fallback: Jika Maatwebsite gagal, coba NominatifImportService juga untuk .xlsx
            try {
                $service = new \App\Services\NominatifImportService();
                $result = $service->import($file->getPathname());
                
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil mengimport {$result['count']} siswa (Fallback Mode)",
                    'data' => $result
                ]);
            } catch (\Exception $ex) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal import: ' . $e->getMessage() . ' | Fallback error: ' . $ex->getMessage()
                ], 500);
            }
        }
    }

    public function downloadTemplate()
    {
        $headers = [
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
            'no_hp_wali'
        ];

        $filename = 'template_siswa.csv';
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function getJurusan()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'jurusan' => ClassHelper::getJurusan(),
                'tingkat' => ClassHelper::$tingkat,
                'contoh_format' => ClassHelper::getExamples(),
            ]
        ]);
    }

    public function getClassInfo()
    {
        $jurusan = ClassHelper::getJurusan();
        $tingkat = ClassHelper::$tingkat;
        
        $kelasOptions = [];
        
        // Generate list kelas untuk setiap tingkat dan jurusan
        foreach ($tingkat as $t) {
            if (in_array($t, ['X', 'XI', 'XII'])) {
                $t = str_replace(['X', 'XI', 'XII'], ['10', '11', '12'], $t);
            }
            
            foreach ($jurusan as $code => $name) {
                for ($i = 1; $i <= 4; $i++) {
                    $kelasOptions[] = [
                        'value' => "{$t} {$code} {$i}",
                        'label' => "{$t} {$name} {$i}",
                        'tingkat' => $t,
                        'jurusan' => $code,
                        'jurusan_name' => $name,
                        'rombel' => $i
                    ];
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'jurusan' => $jurusan,
                'tingkat' => $tingkat,
                'kelas_options' => $kelasOptions,
                'contoh_format' => ClassHelper::getExamples(),
            ]
        ]);
    }
}


