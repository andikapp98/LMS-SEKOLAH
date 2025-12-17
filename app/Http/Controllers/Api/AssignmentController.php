<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Get all assignments
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Assignment::with('courseRelation');

        // Filter by role
        if ($user->role === 'student') {
            $student = $user->student;
            $studentKelas = $student?->kelas;
            
            $query->where('status', 'active')
                  ->where(function($q) use ($studentKelas) {
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

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->orderBy('created_at', 'desc')
                            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    /**
     * Get single assignment
     */
    public function show($id)
    {
        $assignment = Assignment::with('courseRelation')->find($id);
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $assignment
        ]);
    }

    /**
     * Create new assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'due_date' => 'required|date',
            'kelas' => 'nullable|array',
            'status' => 'in:draft,active,completed',
            'file' => 'nullable|file|max:10240'
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['status'] = $validated['status'] ?? 'active';

        // Handle file upload
        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('assignments', 'public');
        }

        $assignment = Assignment::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Assignment berhasil dibuat',
            'data' => $assignment
        ], 201);
    }

    /**
     * Update assignment
     */
    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'exists:courses,id',
            'due_date' => 'date',
            'kelas' => 'nullable|array',
            'status' => 'in:draft,active,completed'
        ]);

        $assignment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Assignment berhasil diupdate',
            'data' => $assignment
        ]);
    }

    /**
     * Delete assignment
     */
    public function destroy($id)
    {
        $assignment = Assignment::find($id);
        
        if (!$assignment) {
            return response()->json([
                'success' => false,
                'message' => 'Assignment tidak ditemukan'
            ], 404);
        }

        // Delete file if exists
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Assignment berhasil dihapus'
        ]);
    }

    /**
     * Get assignment stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'admin') {
            $total = Assignment::count();
            $active = Assignment::where('status', 'active')->count();
            $completed = Assignment::where('status', 'completed')->count();
        } else {
            $total = Assignment::where('created_by', $user->id)->count();
            $active = Assignment::where('created_by', $user->id)->where('status', 'active')->count();
            $completed = Assignment::where('created_by', $user->id)->where('status', 'completed')->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'active' => $active,
                'completed' => $completed
            ]
        ]);
    }
}
