<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LearningMaterialController extends Controller
{
    /**
     * Get all learning materials
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = LearningMaterial::with('course');

        // Filter by role
        if ($user->role === 'student') {
            $student = $user->student;
            $studentKelas = $student?->kelas;
            
            $query->where(function($q) use ($studentKelas) {
                $q->whereNull('kelas');
                if ($studentKelas) {
                    $q->orWhereJsonContains('kelas', $studentKelas);
                }
            });
        } elseif ($user->role === 'teacher') {
            $query->where('created_by', $user->id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('title', 'ilike', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by course
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $materials = $query->orderBy('created_at', 'desc')
                          ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }

    /**
     * Get single learning material
     */
    public function show($id)
    {
        $material = LearningMaterial::with('course')->find($id);
        
        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }

    /**
     * Create new learning material
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:document,video,link',
            'kelas' => 'nullable|array',
            'file' => 'required_if:type,document|file|max:20480',
            'url' => 'required_if:type,video,link|nullable|url'
        ]);

        $validated['created_by'] = $request->user()->id;

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $validated['file_path'] = $file->store('materials', 'public');
            $validated['file_name'] = $file->getClientOriginalName();
            $validated['file_size'] = $file->getSize();
            $validated['mime_type'] = $file->getMimeType();
        }

        $material = LearningMaterial::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dibuat',
            'data' => $material
        ], 201);
    }

    /**
     * Update learning material
     */
    public function update(Request $request, $id)
    {
        $material = LearningMaterial::find($id);
        
        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'exists:courses,id',
            'type' => 'in:document,video,link',
            'kelas' => 'nullable|array',
            'url' => 'nullable|url'
        ]);

        $material->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil diupdate',
            'data' => $material
        ]);
    }

    /**
     * Delete learning material
     */
    public function destroy($id)
    {
        $material = LearningMaterial::find($id);
        
        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Materi tidak ditemukan'
            ], 404);
        }

        // Delete file if exists
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return response()->json([
            'success' => true,
            'message' => 'Materi berhasil dihapus'
        ]);
    }

    /**
     * Download material file
     */
    public function download($id)
    {
        $material = LearningMaterial::find($id);
        
        if (!$material || !$material->file_path) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan'
            ], 404);
        }

        $path = Storage::disk('public')->path($material->file_path);
        
        return response()->download($path, $material->file_name);
    }

    /**
     * Get material stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'admin') {
            $total = LearningMaterial::count();
            $documents = LearningMaterial::where('type', 'document')->count();
            $videos = LearningMaterial::where('type', 'video')->count();
            $links = LearningMaterial::where('type', 'link')->count();
        } else {
            $total = LearningMaterial::where('created_by', $user->id)->count();
            $documents = LearningMaterial::where('created_by', $user->id)->where('type', 'document')->count();
            $videos = LearningMaterial::where('created_by', $user->id)->where('type', 'video')->count();
            $links = LearningMaterial::where('created_by', $user->id)->where('type', 'link')->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'documents' => $documents,
                'videos' => $videos,
                'links' => $links
            ]
        ]);
    }
}
