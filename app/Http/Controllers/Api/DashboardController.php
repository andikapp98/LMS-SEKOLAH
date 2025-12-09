<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Assignment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard data
     * GET /api/v1/dashboard
     */
    public function index()
    {
        // Get statistics
        $totalStudents = Student::count();
        $newStudentsToday = Student::whereDate('created_at', today())->count();
        $newStudentsWeek = Student::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::where('status', 'aktif')->count();
        
        $totalCourses = Course::count();
        $newCoursesWeek = Course::where('created_at', '>=', now()->subDays(7))->count();
        
        $totalAssignments = Assignment::count();
        $pendingAssignments = Assignment::where('status', 'pending')->count();
        $completedAssignments = Assignment::where('status', 'completed')->count();
        $dueTodayAssignments = Assignment::whereDate('due_date', today())->count();
        
        // Recent students  
        $recentStudents = Student::latest()->take(5)->get()->map(function($student) {
            return [
                'id' => 'student-' . $student->id,
                'type' => 'student',
                'title' => 'Siswa Baru Terdaftar',
                'description' => $student->nama . ' - ' . ($student->kelas ?? 'Belum ada kelas'),
                'time' => $student->created_at->diffForHumans(),
                'timestamp' => $student->created_at->toISOString()
            ];
        });
        
        // Recent courses
        $recentCourses = Course::with('teachers')->latest()->take(3)->get()->map(function($course) {
            $teacherNames = $course->teachers->pluck('nama')->join(', ') ?: 'Belum ada guru';
            return [
                'id' => 'course-' . $course->id,
                'type' => 'course',
                'title' => 'Mata Pelajaran Baru',
                'description' => $course->nama_mapel . ' oleh ' . $teacherNames,
                'time' => $course->created_at->diffForHumans(),
                'timestamp' => $course->created_at->toISOString()
            ];
        });
        
        // Assignments (empty for now)
        $recentAssignments = collect([]);
        $upcomingTasks = collect([]);
        
        // Merge and sort activities
        $recentActivities = $recentStudents
            ->concat($recentCourses)
            ->concat($recentAssignments)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();
        
        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    // Students
                    'total_students' => $totalStudents,
                    'new_students_today' => $newStudentsToday,
                    'new_students_week' => $newStudentsWeek,
                    
                    // Teachers
                    'total_teachers' => $totalTeachers,
                    'active_teachers' => $activeTeachers,
                    
                    // Courses
                    'total_courses' => $totalCourses,
                    'new_courses_week' => $newCoursesWeek,
                    
                    // Assignments
                    'total_assignments' => $totalAssignments,
                    'pending_tasks' => $pendingAssignments,
                    'completed_tasks' => $completedAssignments,
                    'due_today' => $dueTodayAssignments,
                ],
                'recent_activities' => $recentActivities,
                'upcoming_tasks' => $upcomingTasks
            ]
        ]);
    }

    /**
     * Get quick stats for widgets
     * GET /api/v1/dashboard/quick-stats
     */
    public function quickStats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'students' => Student::count(),
                'teachers' => Teacher::count(),
                'courses' => Course::count(),
                'assignments' => Assignment::count(),
            ]
        ]);
    }
}
