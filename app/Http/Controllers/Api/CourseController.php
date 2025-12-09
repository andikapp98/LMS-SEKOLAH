<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Get all courses
     * GET /api/v1/courses
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        $teacherId = $request->get('teacher_id');
        $semester = $request->get('semester');
        
        $query = Course::with('teachers');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_mapel', 'like', "%{$search}%")
                  ->orWhere('kode_mapel', 'like', "%{$search}%");
            });
        }
        
        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        
        if ($semester) {
            $query->where('semester', $semester);
        }
        
        $courses = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    /**
     * Get single course
     * GET /api/v1/courses/{id}
     */
    public function show($id)
    {
        $course = Course::with('teachers')->find($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    /**
     * Create new course
     * POST /api/v1/courses
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_mapel' => 'required|unique:courses,kode_mapel',
            'nama_mapel' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'kelas' => 'nullable|string',
            'jam_per_minggu' => 'required|integer|min:1|max:10',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::create($validator->validated());
        $course->load('teachers');

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan',
            'data' => $course
        ], 201);
    }

    /**
     * Update course
     * PUT /api/v1/courses/{id}
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kode_mapel' => 'required|unique:courses,kode_mapel,' . $id,
            'nama_mapel' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'kelas' => 'nullable|string',
            'jam_per_minggu' => 'required|integer|min:1|max:10',
            'deskripsi' => 'nullable|string',
            'semester' => 'required|in:ganjil,genap',
            'tahun_ajaran' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $course->update($validator->validated());
        $course->load('teachers');

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui',
            'data' => $course
        ]);
    }

    /**
     * Delete course
     * DELETE /api/v1/courses/{id}
     */
    public function destroy($id)
    {
        $course = Course::find($id);
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus'
        ]);
    }

    /**
     * Get course statistics
     * GET /api/v1/courses/stats
     */
    public function stats()
    {
        $total = Course::count();
        $ganjil = Course::where('semester', 'ganjil')->count();
        $genap = Course::where('semester', 'genap')->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'semester_ganjil' => $ganjil,
                'semester_genap' => $genap
            ]
        ]);
    }
}
