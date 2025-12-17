<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Assignment::with(['creator', 'course']);
        
        // Students: show assignments for their class
        if ($user->role === 'student') {
            // Get student record
            $student = \App\Models\Student::where('email', $user->email)->first();
            
            if ($student && $student->kelas) {
                // Show assignments where student's class is in the kelas array
                $query->where(function($q) use ($student) {
                    $q->whereJsonContains('kelas', $student->kelas)
                      ->orWhereNull('kelas'); // Also show assignments for all classes
                });
            }
        }
        // Admin and Teachers: show all assignments
        
        $assignments = $query->orderBy('due_date', 'desc')->get();

        return Inertia::render('Assignments/Index', [
            'assignments' => $assignments
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $courses = [];
        
        // If teacher, get only their courses
        if ($user->teacher) {
            $courses = $user->teacher->courses()->get()->map(function($course) {
                return [
                    'id' => $course->id,
                    'nama_mapel' => $course->nama_mapel,
                    'kode_mapel' => $course->kode_mapel,
                    'kelas' => $course->kelas,
                ];
            });
        } else if ($user->isAdmin()) {
            // Admin can see all courses
            $courses = \App\Models\Course::all()->map(function($course) {
                return [
                    'id' => $course->id,
                    'nama_mapel' => $course->nama_mapel,
                    'kode_mapel' => $course->kode_mapel,
                    'kelas' => $course->kelas,
                ];
            });
        }
        
        // Get available kelas from students table
        $availableKelas = \App\Models\Student::select('kelas')
            ->distinct()
            ->whereNotNull('kelas')
            ->orderBy('kelas')
            ->pluck('kelas')
            ->toArray();
        
        return Inertia::render('Assignments/Create', [
            'courses' => $courses,
            'availableKelas' => $availableKelas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course' => 'required|string|max:255',
            'kelas' => 'nullable|array',
            'kelas.*' => 'string|max:50',
            'due_date' => 'required|date',
            // Status not required - auto set to pending
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments', $fileName, 'public');
        }

        Assignment::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course' => $validated['course'],
            'kelas' => $validated['kelas'] ?? null,
            'due_date' => $validated['due_date'],
            'status' => 'pending',  // Auto-set to pending
            'file_path' => $filePath,
            'created_by' => auth()->id()
        ]);

        return redirect('/assignments')->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function show(Assignment $assignment)
    {
        // Check authorization: admin can see all, teachers only their own
        if (!auth()->user()->isAdmin() && $assignment->created_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke tugas ini');
        }

        $assignment->load('creator');
        
        return Inertia::render('Assignments/Show', [
            'assignment' => $assignment
        ]);
    }

    public function edit(Assignment $assignment)
    {
        // Check authorization: admin can edit all, teachers only their own
        if (!auth()->user()->isAdmin() && $assignment->created_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit tugas ini');
        }

        $user = auth()->user();
        $courses = [];
        
        // If teacher, get only their courses
        if ($user->teacher) {
            $courses = $user->teacher->courses()->get()->map(function($course) {
                return [
                    'id' => $course->id,
                    'nama_mapel' => $course->nama_mapel,
                    'kode_mapel' => $course->kode_mapel,
                    'kelas' => $course->kelas,
                ];
            });
        } else if ($user->isAdmin()) {
            // Admin can see all courses
            $courses = \App\Models\Course::all()->map(function($course) {
                return [
                    'id' => $course->id,
                    'nama_mapel' => $course->nama_mapel,
                    'kode_mapel' => $course->kode_mapel,
                    'kelas' => $course->kelas,
                ];
            });
        }

        // Get available kelas from students table
        $availableKelas = \App\Models\Student::select('kelas')
            ->distinct()
            ->whereNotNull('kelas')
            ->orderBy('kelas')
            ->pluck('kelas')
            ->toArray();

        return Inertia::render('Assignments/Edit', [
            'assignment' => $assignment,
            'courses' => $courses,
            'availableKelas' => $availableKelas
        ]);
    }

    public function update(Request $request, Assignment $assignment)
    {
        // Check authorization: admin can update all, teachers only their own
        if (!auth()->user()->isAdmin() && $assignment->created_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit tugas ini');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course' => 'required|string|max:255',
            'kelas' => 'nullable|array',
            'kelas.*' => 'string|max:50',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240'
        ]);

        $filePath = $assignment->file_path;
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($assignment->file_path) {
                \Storage::disk('public')->delete($assignment->file_path);
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments', $fileName, 'public');
        }

        $assignment->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course' => $validated['course'],
            'kelas' => $validated['kelas'] ?? null,
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'file_path' => $filePath
        ]);

        return redirect('/assignments')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Assignment $assignment)
    {
        // Check authorization: admin can delete all, teachers only their own
        if (!auth()->user()->isAdmin() && $assignment->created_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus tugas ini');
        }

        // Delete file if exists
        if ($assignment->file_path) {
            \Storage::disk('public')->delete($assignment->file_path);
        }
        
        $assignment->delete();

        return redirect('/assignments')->with('success', 'Tugas berhasil dihapus!');
    }

    public function download(Assignment $assignment)
    {
        if (!$assignment->file_path) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        $filePath = storage_path('app/public/' . $assignment->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filePath);
    }
}
