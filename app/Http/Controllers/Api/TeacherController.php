<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    /**
     * Get all teachers
     * GET /api/v1/teachers
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search');
        
        $query = Teacher::withCount('courses');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('mata_pelajaran', 'like', "%{$search}%");
            });
        }
        
        $teachers = $query->latest()->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $teachers
        ]);
    }

    /**
     * Get single teacher
     * GET /api/v1/teachers/{id}
     */
    public function show($id)
    {
        $teacher = Teacher::with('courses')->find($id);
        
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $teacher
        ]);
    }

    /**
     * Create new teacher
     * POST /api/v1/teachers
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'nullable|unique:teachers,nip',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:teachers,email',
            'no_hp' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $teacher = Teacher::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan',
            'data' => $teacher
        ], 201);
    }

    /**
     * Update teacher
     * PUT /api/v1/teachers/{id}
     */
    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nip' => 'nullable|unique:teachers,nip,' . $id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|unique:teachers,email,' . $id,
            'no_hp' => 'nullable|string',
            'mata_pelajaran' => 'nullable|string',
            'alamat' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $teacher->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui',
            'data' => $teacher
        ]);
    }

    /**
     * Delete teacher
     * DELETE /api/v1/teachers/{id}
     */
    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tidak ditemukan'
            ], 404);
        }

        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus'
        ]);
    }

    /**
     * Get teacher statistics
     * GET /api/v1/teachers/stats
     */
    public function stats()
    {
        $total = Teacher::count();
        $active = Teacher::where('status', 'aktif')->count();
        $inactive = Teacher::where('status', 'non-aktif')->count();
        $withCourses = Teacher::has('courses')->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'with_courses' => $withCourses
            ]
        ]);
    }
}
