<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Imports\TeachersImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::withCount('courses');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('mata_pelajaran', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by mata pelajaran
        if ($request->has('mata_pelajaran') && $request->mata_pelajaran != '') {
            $query->where('mata_pelajaran', 'like', "%{$request->mata_pelajaran}%");
        }
        
        $teachers = $query->latest()->paginate(10)->withQueryString();
        
        return Inertia::render('Teachers/Index', [
            'teachers' => $teachers,
            'filters' => [
                'search' => $request->search ?? '',
                'status' => $request->status ?? '',
                'mata_pelajaran' => $request->mata_pelajaran ?? '',
            ]
        ]);
    }

    public function create()
    {
        return Inertia::render('Teachers/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'nullable|unique:teachers,nik',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
        ]);

        Teacher::create($validated);

        return redirect()->route('teachers.index')
            ->with('success', 'Data guru berhasil ditambahkan!');
    }

    /**
     * Show upload form
     */
    public function uploadForm()
    {
        return Inertia::render('Teachers/Upload');
    }

    /**
     * Process Excel/CSV upload
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv|max:5120' // 5MB, Excel & CSV
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('file');
            $import = new TeachersImport();
            
            Excel::import($import, $file);

            $errors = $import->getErrors();
            
            DB::commit();

            $message = 'Import berhasil!';
            if (count($errors) > 0) {
                $message .= ' Namun ada ' . count($errors) . ' baris yang gagal diimport.';
            }

            return redirect()->route('teachers.index')->with('success', $message);

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
     * Show teacher detail
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('courses');
        
        return Inertia::render('Teachers/Show', [
            'teacher' => $teacher
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Teacher $teacher)
    {
        return Inertia::render('Teachers/Edit', [
            'teacher' => $teacher
        ]);
    }

    /**
     * Update teacher
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'nik' => 'nullable|unique:teachers,nik,' . $teacher->id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
        ]);

        try {
            $teacher->update($validated);
            
            return redirect()
                ->route('teachers.index')
                ->with('success', 'Data guru berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Error updating teacher: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data guru: ' . $e->getMessage());
        }
    }

    /**
     * Delete teacher
     */
    public function destroy(Teacher $teacher)
    {
        try {
            $teacher->delete();
            return redirect()
                ->route('teachers.index')
                ->with('success', 'Data guru berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Error deleting teacher: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal menghapus data guru: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete teachers
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:teachers,id'
        ]);

        try {
            $count = Teacher::whereIn('id', $validated['ids'])->count();
            Teacher::whereIn('id', $validated['ids'])->delete();
            
            return redirect()
                ->route('teachers.index')
                ->with('success', "Berhasil menghapus {$count} data guru!");
                
        } catch (\Exception $e) {
            Log::error('Error bulk deleting teachers: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal menghapus data guru: ' . $e->getMessage());
        }
    }

    /**
     * Clear all teacher data
     * Uses delete() instead of truncate() to respect foreign key constraints
     * This ensures admin users and teacher users are not deleted, only teacher_id is set to null
     */
    public function clear()
    {
        try {
            // Use delete() instead of truncate() to trigger foreign key SET NULL
            // This will set teacher_id to NULL for users, not delete the users
            $count = Teacher::count();
            Teacher::query()->delete();
            
            // Also clear the pivot table
            \DB::table('course_teacher')->truncate();
            
            return redirect()->route('teachers.index')->with('success', "Semua data guru ({$count}) berhasil dihapus. User accounts tetap aman.");
        } catch (\Exception $e) {
            \Log::error('Error clearing teachers: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Reset password for a teacher's user account
     */
    public function resetPassword(Teacher $teacher)
    {
        try {
            // Find user account by email
            if (!$teacher->email) {
                return back()->with('error', 'Guru tidak memiliki email terdaftar.');
            }

            $user = User::where('email', $teacher->email)->first();
            
            if (!$user) {
                // Create new user account if not exists
                $password = $teacher->kode_guru ?? '123456';
                $user = User::create([
                    'name' => $teacher->nama,
                    'email' => $teacher->email,
                    'password' => Hash::make($password),
                    'role' => 'teacher',
                    'teacher_id' => $teacher->id,
                ]);
                
                return back()->with('success', "Akun baru dibuat untuk {$teacher->nama}. Password: " . ($teacher->kode_guru ?? '123456'));
            }

            // Reset password to default
            $newPassword = $teacher->kode_guru ?? '123456';
            $user->update([
                'password' => Hash::make($newPassword),
                'teacher_id' => $teacher->id,
            ]);

            return back()->with('success', "Password untuk {$teacher->nama} berhasil direset. Password baru: {$newPassword}");
            
        } catch (\Exception $e) {
            Log::error('Error resetting teacher password: ' . $e->getMessage());
            return back()->with('error', 'Gagal mereset password: ' . $e->getMessage());
        }
    }
}
