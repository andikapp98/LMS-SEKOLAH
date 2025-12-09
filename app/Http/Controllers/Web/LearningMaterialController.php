<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class LearningMaterialController extends Controller
{
    public function index()
    {
        $query = LearningMaterial::with(['course', 'uploader']);
        
        // If user is teacher, only show materials from their courses
        if (auth()->user()->teacher) {
            $teacherCourseIds = auth()->user()->teacher->courses()->pluck('courses.id');
            $query->whereIn('course_id', $teacherCourseIds);
        }
        
        $materials = $query->latest()->get();

        return Inertia::render('LearningMaterials/Index', [
            'materials' => $materials
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
                ];
            });
        } else if ($user->isAdmin()) {
            // Admin can see all courses
            $courses = Course::all()->map(function($course) {
                return [
                    'id' => $course->id,
                    'nama_mapel' => $course->nama_mapel,
                    'kode_mapel' => $course->kode_mapel,
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
        
        return Inertia::render('LearningMaterials/Create', [
            'courses' => $courses,
            'availableKelas' => $availableKelas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:modul,video,audio,dokumen,presentasi,link,lainnya',
            'kelas' => 'nullable|array',
            'kelas.*' => 'string|max:50',
            'url' => 'nullable|url',
            'file' => 'nullable|file|max:51200', // 50MB max
            'visibility' => 'required|in:public,private'
        ]);

        $filePath = null;
        $fileName = null;
        $fileType = null;
        $fileSize = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('learning-materials', $fileName, 'public');
            $fileType = $file->getClientMimeType();
            $fileSize = $file->getSize();
        }

        LearningMaterial::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'uploaded_by' => auth()->id(),
            'type' => $validated['type'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'url' => $validated['url'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'visibility' => $validated['visibility']
        ]);

        return redirect('/learning-materials')->with('success', 'Materi berhasil ditambahkan!');
    }

    public function show(LearningMaterial $learningMaterial)
    {
        $learningMaterial->load(['course', 'uploader']);
        
        return Inertia::render('LearningMaterials/Show', [
            'material' => $learningMaterial
        ]);
    }

    public function edit(LearningMaterial $learningMaterial)
    {
        // Check authorization: admin can edit all, teachers only their own
        if (!auth()->user()->isAdmin() && $learningMaterial->uploaded_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit materi ini');
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
                ];
            });
        } else if ($user->isAdmin()) {
            // Admin can see all courses
            $courses = Course::all()->map(function($course) {
                return [
                    'id' => $course->id,
                    'nama_mapel' => $course->nama_mapel,
                    'kode_mapel' => $course->kode_mapel,
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

        return Inertia::render('LearningMaterials/Edit', [
            'material' => $learningMaterial,
            'courses' => $courses,
            'availableKelas' => $availableKelas
        ]);
    }

    public function update(Request $request, LearningMaterial $learningMaterial)
    {
        // Check authorization
        if (!auth()->user()->isAdmin() && $learningMaterial->uploaded_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit materi ini');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:modul,video,audio,dokumen,presentasi,link,lainnya',
            'kelas' => 'nullable|array',
            'kelas.*' => 'string|max:50',
            'url' => 'nullable|url',
            'file' => 'nullable|file|max:51200',
            'visibility' => 'required|in:public,private'
        ]);

        $filePath = $learningMaterial->file_path;
        $fileName = $learningMaterial->file_name;
        $fileType = $learningMaterial->file_type;
        $fileSize = $learningMaterial->file_size;

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($learningMaterial->file_path) {
                Storage::disk('public')->delete($learningMaterial->file_path);
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('learning-materials', $fileName, 'public');
            $fileType = $file->getClientMimeType();
            $fileSize = $file->getSize();
        }

        $learningMaterial->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'type' => $validated['type'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'url' => $validated['url'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'visibility' => $validated['visibility']
        ]);

        return redirect('/learning-materials')->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(LearningMaterial $learningMaterial)
    {
        // Check authorization
        if (!auth()->user()->isAdmin() && $learningMaterial->uploaded_by !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus materi ini');
        }

        // Delete file if exists
        if ($learningMaterial->file_path) {
            Storage::disk('public')->delete($learningMaterial->file_path);
        }
        
        $learningMaterial->delete();

        return redirect('/learning-materials')->with('success', 'Materi berhasil dihapus!');
    }

    public function download(LearningMaterial $learningMaterial)
    {
        if (!$learningMaterial->file_path) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        $filePath = storage_path('app/public/' . $learningMaterial->file_path);
        
        if (!file_exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        // Increment download count
        $learningMaterial->increment('download_count');

        return response()->download($filePath, $learningMaterial->file_name);
    }
}
