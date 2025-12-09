<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use App\Imports\CoursesImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('teachers')->latest()->get();
        
        return Inertia::render('Courses/Index', [
            'courses' => $courses
        ]);
    }

    public function create()
    {
        $teachers = Teacher::active()->orderBy('nama')->get();
        
        return Inertia::render('Courses/Create', [
            'teachers' => $teachers
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|unique:courses,kode_mapel',
            'nama_mapel' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'kelas' => 'nullable|string',
            'jam_per_minggu' => 'required|integer|min:1|max:10',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    /**
     * Show course detail
     */
    public function show(Course $course)
    {
        // Load teachers untuk course ini
        $course->load('teachers');
        
        // Load semua courses dengan nama_mapel yang sama untuk aggregate guru
        $allCoursesWithSameName = Course::where('nama_mapel', $course->nama_mapel)
            ->with('teachers')
            ->get();
        
        // Aggregate all teachers (unique)
        $allTeachers = collect();
        foreach ($allCoursesWithSameName as $c) {
            foreach ($c->teachers as $teacher) {
                // Add teacher if not already in collection
                if (!$allTeachers->contains('id', $teacher->id)) {
                    $allTeachers->push($teacher);
                }
            }
        }
        
        // Override teachers dengan aggregated teachers
        $course->setRelation('teachers', $allTeachers);
        
        return Inertia::render('Courses/Show', [
            'course' => $course
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Course $course)
    {
        $teachers = Teacher::active()->orderBy('nama')->get();
        
        return Inertia::render('Courses/Edit', [
            'course' => $course,
            'teachers' => $teachers
        ]);
    }

    /**
     * Update course
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|unique:courses,kode_mapel,' . $course->id,
            'nama_mapel' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'kelas' => 'nullable|string',
            'jam_per_minggu' => 'required|integer|min:1|max:10',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string',
        ]);

        try {
            $course->update($validated);
            
            return redirect()
                ->route('courses.index')
                ->with('success', 'Mata pelajaran berhasil diperbarui!');
                
        } catch (\Exception $e) {
            \Log::error('Error updating course: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui mata pelajaran: ' . $e->getMessage());
        }
    }

    /**
     * Show upload form
     */
    public function uploadForm()
    {
        return Inertia::render('Courses/Upload');
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
            $import = new CoursesImport();
            
            Excel::import($import, $file);

            $errors = $import->getErrors();
            
            DB::commit();

            $message = 'Import mata pelajaran berhasil!';
            if (count($errors) > 0) {
                $message .= ' Namun ada ' . count($errors) . ' baris yang gagal diimport.';
            }

            return redirect()->route('courses.index')->with('success', $message);

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
            Log::error('Import courses failed: ' . $e->getMessage());
            
            return back()->with('error', 'Import gagal: ' . $e->getMessage());
        }
    }

    /**
     * Delete course
     */
    public function destroy(Course $course)
    {
        try {
            $course->delete();
            return redirect()
                ->route('courses.index')
                ->with('success', 'Mata pelajaran berhasil dihapus!');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting course: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal menghapus mata pelajaran: ' . $e->getMessage());
        }
    }
    
    /**
     * Clear all course data
     * Uses delete() instead of truncate() to respect foreign key constraints
     */
    public function clear()
    {
        try {
            // Use delete() instead of truncate() to trigger foreign key constraints properly
            $count = Course::count();
            Course::query()->delete();
            
            // Also clear the pivot table
            \DB::table('course_teacher')->truncate();
            
            return redirect()->route('courses.index')
                ->with('success', "Semua data mata pelajaran ({$count}) berhasil dihapus.");
        } catch (\Exception $e) {
            \Log::error('Error clearing courses: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}

