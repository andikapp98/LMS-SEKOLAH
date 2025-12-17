<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Imports\NominatifStudentsImport;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query()->orderBy('created_at', 'desc');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        
        // Tingkat filter
        if ($request->filled('tingkat')) {
            $query->where('kelas', 'like', $request->tingkat . '%');
        }
        
        // Jurusan filter
        if ($request->filled('jurusan')) {
            $query->where('kelas', 'like', '%' . $request->jurusan . '%');
        }
        
        // Rombel filter
        if ($request->filled('rombel')) {
            $query->where('kelas', 'like', '%' . $request->rombel);
        }
        
        $students = $query->paginate(20)->withQueryString();
        
        return Inertia::render('Students/Index', [
            'students' => $students,
            'filters' => $request->only(['search', 'tingkat', 'jurusan', 'rombel'])
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return Inertia::render('Students/Create');
    }

    /**
     * Store new student
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:students,nis',
            'nisn' => 'nullable|string|unique:students,nisn',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kelas' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:students,email',
            'nama_wali' => 'nullable|string|max:255',
            'no_hp_wali' => 'nullable|string|max:20',
        ]);

        try {
            $student = Student::create($validated);
            
            return redirect()
                ->route('students.index')
                ->with('success', 'Siswa berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            Log::error('Error creating student: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    /**
     * Show upload form
     */
    public function uploadForm()
    {
        return Inertia::render('Students/Upload');
    }

    /**
     * Process nominatif Excel upload
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:5120' // 5MB
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $import = new NominatifStudentsImport();
            
            Excel::import($import, $file);

            $errors = $import->getErrors();
            
            DB::commit();

            $message = 'Import berhasil!';
            if (count($errors) > 0) {
                $message .= ' Namun ada ' . count($errors) . ' baris yang gagal diimport.';
            }

            return redirect()->route('students.index')->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                ];
            }
            
            $errorSummary = "Validasi gagal pada " . count($errorMessages) . " baris. ";
            if (count($errorMessages) > 0) {
                $errorSummary .= "Contoh: Baris {$errorMessages[0]['row']} - " . implode(', ', $errorMessages[0]['errors']);
            }
            
            return back()
                ->withInput()
                ->with('error', $errorSummary)
                ->with('validation_errors', $errorMessages);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Show student detail
     */
    public function show(Student $student)
    {
        return Inertia::render('Students/Show', [
            'student' => $student
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Student $student)
    {
        return Inertia::render('Students/Edit', [
            'student' => $student
        ]);
    }

    /**
     * Update student
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'nisn' => 'nullable|string|unique:students,nisn,' . $student->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kelas' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:students,email,' . $student->id,
            'nama_wali' => 'nullable|string|max:255',
            'no_hp_wali' => 'nullable|string|max:20',
        ]);

        try {
            $student->update($validated);
            
            return redirect()
                ->route('students.index')
                ->with('success', 'Data siswa berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Delete student
     */
    public function destroy(Student $student)
    {
        try {
            $student->delete();
            return redirect()
                ->route('students.index')
                ->with('success', 'Data siswa berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Clear all student data
     */
    public function clear()
    {
        try {
            Student::truncate();
            return redirect()->route('students.index')->with('success', 'Semua data siswa berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Reset password for a student's user account
     */
    public function resetPassword(Student $student)
    {
        try {
            if (!$student->nis) {
                return back()->with('error', 'Siswa tidak memiliki NIS terdaftar.');
            }

            // Generate email from NIS
            $studentEmail = $student->nis . '@siswa.smkyasmu.sch.id';

            $user = User::where('email', $studentEmail)->first();
            
            if (!$user) {
                // Create new user account if not exists
                $user = User::create([
                    'name' => $student->nama ?? 'Siswa ' . $student->nis,
                    'email' => $studentEmail,
                    'password' => Hash::make($student->nis),
                    'role' => 'student',
                ]);
                
                // Update student email
                $student->update(['email' => $studentEmail]);
                
                return back()->with('success', "Akun baru dibuat untuk {$student->nama}. Email: {$studentEmail}, Password: {$student->nis}");
            }

            // Reset password to NIS
            $user->update([
                'password' => Hash::make($student->nis),
            ]);

            return back()->with('success', "Password untuk {$student->nama} berhasil direset. Email: {$studentEmail}, Password: {$student->nis}");
            
        } catch (\Exception $e) {
            Log::error('Error resetting student password: ' . $e->getMessage());
            return back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
}
