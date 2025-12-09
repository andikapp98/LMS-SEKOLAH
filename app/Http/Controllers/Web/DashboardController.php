<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        // Initialize stats
        $stats = [];
        $recentActivities = collect();
        $upcomingTasks = collect();
        
        if ($isAdmin) {
            // ADMIN: Full dashboard with all stats
            $totalStudents = Student::count();
            $newStudentsToday = Student::whereDate('created_at', today())->count();
            $newStudentsWeek = Student::where('created_at', '>=', now()->subDays(7))->count();
            
            $totalCourses = Course::count();
            $newCoursesWeek = Course::where('created_at', '>=', now()->subDays(7))->count();
            
            $totalAssignments = Assignment::count();
            $pendingAssignments = Assignment::where('status', 'pending')->count();
            $completedAssignments = Assignment::where('status', 'completed')->count();
            $dueTodayAssignments = Assignment::whereDate('due_date', today())->count();
            
            $stats = [
                'total_courses' => $totalCourses,
                'new_courses' => $newCoursesWeek,
                'total_students' => $totalStudents,
                'new_students' => $newStudentsWeek,
                'new_students_today' => $newStudentsToday,
                'pending_tasks' => $pendingAssignments,
                'due_today' => $dueTodayAssignments,
                'completed_tasks' => $completedAssignments,
                'total_assignments' => $totalAssignments
            ];
            
            // Recent activities - show all
            $recentStudents = Student::latest()->take(5)->get()->map(function($student) {
                return [
                    'id' => 'student-' . $student->id,
                    'type' => 'student',
                    'title' => 'Siswa Baru Terdaftar',
                    'description' => $student->nama . ' - ' . ($student->kelas ?? 'Belum ada kelas'),
                    'time' => $student->created_at->diffForHumans(),
                    'timestamp' => $student->created_at
                ];
            });
            
            $recentCourses = Course::latest()->take(3)->get()->map(function($course) {
                return [
                    'id' => 'course-' . $course->id,
                    'type' => 'course',
                    'title' => 'Mata Pelajaran Baru',
                    'description' => $course->nama_mapel . ' telah ditambahkan',
                    'time' => $course->created_at->diffForHumans(),
                    'timestamp' => $course->created_at
                ];
            });
            
            $recentAssignments = Assignment::latest()->take(3)->get()->map(function($assignment) {
                return [
                    'id' => 'assignment-' . $assignment->id,
                    'type' => 'assignment',
                    'title' => 'Tugas Baru',
                    'description' => $assignment->title . ' - Deadline: ' . Carbon::parse($assignment->due_date)->format('d M Y'),
                    'time' => $assignment->created_at->diffForHumans(),
                    'timestamp' => $assignment->created_at
                ];
            });
            
            // Merge and sort activities
            $recentActivities = $recentStudents
                ->concat($recentCourses)
                ->concat($recentAssignments)
                ->sortByDesc('timestamp')
                ->take(10)
                ->values()
                ->map(function($activity) {
                    unset($activity['timestamp']);
                    return $activity;
                });
            
            // Upcoming tasks - all assignments
            $upcomingTasks = Assignment::where('due_date', '>=', today())
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get()
                ->map(function($assignment) {
                    return [
                        'id' => $assignment->id,
                        'title' => $assignment->title,
                        'course' => $assignment->course,
                        'due_date' => Carbon::parse($assignment->due_date)->format('d M Y'),
                        'status' => $assignment->status ?? 'pending'
                    ];
                });
                
        } elseif ($user->role === 'teacher') {
            // TEACHER: Dashboard with their courses and assignments
            $teacher = $user->teacher;
            
            if ($teacher) {
                // Get teacher's courses
                $myCourses = $teacher->courses()->count();
                
                // Get assignments
                $myAssignments = Assignment::where('created_by', $user->id)->count();
                $myPendingAssignments = Assignment::where('created_by', $user->id)
                    ->where('status', 'pending')->count();
                $myCompletedAssignments = Assignment::where('created_by', $user->id)
                    ->where('status', 'completed')->count();
                $myDueTodayAssignments = Assignment::where('created_by', $user->id)
                    ->whereDate('due_date', today())->count();
                
                $stats = [
                    'my_courses' => $myCourses,
                    'total_assignments' => $myAssignments,
                    'pending_tasks' => $myPendingAssignments,
                    'completed_tasks' => $myCompletedAssignments,
                    'due_today' => $myDueTodayAssignments
                ];
                
                // Recent activities - courses and assignments
                $recentActivities = Assignment::where('created_by', $user->id)
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function($assignment) {
                        return [
                            'id' => 'assignment-' . $assignment->id,
                            'type' => 'assignment',
                            'title' => 'Tugas Anda',
                            'description' => $assignment->title . ' - Deadline: ' . Carbon::parse($assignment->due_date)->format('d M Y'),
                            'time' => $assignment->created_at->diffForHumans()
                        ];
                    });
                
                // Upcoming tasks - only my assignments
                $upcomingTasks = Assignment::where('created_by', $user->id)
                    ->where('due_date', '>=', today())
                    ->orderBy('due_date', 'asc')
                    ->take(5)
                    ->get()
                    ->map(function($assignment) {
                        return [
                            'id' => $assignment->id,
                            'title' => $assignment->title,
                            'course' => $assignment->course,
                            'due_date' => Carbon::parse($assignment->due_date)->format('d M Y'),
                            'status' => $assignment->status ?? 'pending'
                        ];
                    });
            } else {
                // Teacher without profile
                $stats = [
                    'my_courses' => 0,
                    'total_assignments' => 0,
                    'pending_tasks' => 0,
                    'completed_tasks' => 0,
                    'due_today' => 0
                ];
            }
        } else {
            // STUDENT: Dashboard with available assignments, quizzes, materials
            $studentKelas = $user->student?->kelas;
            
            // Count assignments for student's class
            $totalAssignments = Assignment::where('status', 'active')
                ->where(function($query) use ($studentKelas) {
                    $query->whereNull('kelas');
                    if ($studentKelas) {
                        $query->orWhereJsonContains('kelas', $studentKelas);
                    }
                })
                ->count();
            
            // Count available quizzes - use raw query for PostgreSQL JSON
            $totalQuizzes = Quiz::where('status', 'published')
                ->where(function($query) use ($studentKelas) {
                    $query->whereNull('kelas')
                          ->orWhereRaw("jsonb_array_length(kelas::jsonb) = 0");
                    if ($studentKelas) {
                        $query->orWhereJsonContains('kelas', $studentKelas);
                    }
                })
                ->count();
            
            $availableQuizzes = Quiz::where('status', 'published')
                ->where(function($query) use ($studentKelas) {
                    $query->whereNull('kelas')
                          ->orWhereRaw("jsonb_array_length(kelas::jsonb) = 0");
                    if ($studentKelas) {
                        $query->orWhereJsonContains('kelas', $studentKelas);
                    }
                })
                ->where(function($query) {
                    $query->whereNull('available_from')
                          ->orWhere('available_from', '<=', now());
                })
                ->where(function($query) {
                    $query->whereNull('available_until')
                          ->orWhere('available_until', '>=', now());
                })
                ->count();
            
            // Count materials
            $totalMaterials = LearningMaterial::where(function($query) use ($studentKelas) {
                $query->whereNull('kelas');
                if ($studentKelas) {
                    $query->orWhereJsonContains('kelas', $studentKelas);
                }
            })->count();
            
            $stats = [
                'total_assignments' => $totalAssignments,
                'total_quizzes' => $totalQuizzes,
                'available_quizzes' => $availableQuizzes,
                'total_materials' => $totalMaterials,
                'pending_tasks' => $totalAssignments, // Simplified - all assignments are pending
                'completed_tasks' => 0, // We don't track submissions yet
                'due_today' => 0
            ];
            
            // Recent quizzes for students
            $recentQuizzes = Quiz::where('status', 'published')
                ->where(function($query) use ($studentKelas) {
                    $query->whereNull('kelas')
                          ->orWhereRaw("jsonb_array_length(kelas::jsonb) = 0");
                    if ($studentKelas) {
                        $query->orWhereJsonContains('kelas', $studentKelas);
                    }
                })
                ->latest()
                ->take(5)
                ->get()
                ->map(function($quiz) {
                    return [
                        'id' => 'quiz-' . $quiz->id,
                        'type' => 'assignment',
                        'title' => 'Kuis: ' . $quiz->title,
                        'description' => $quiz->course?->nama_mapel . ' - ' . ($quiz->questions_count ?? $quiz->questions()->count()) . ' soal',
                        'time' => $quiz->created_at->diffForHumans()
                    ];
                });
            
            $recentAssignmentsStudent = Assignment::where('status', 'active')
                ->where(function($query) use ($studentKelas) {
                    $query->whereNull('kelas');
                    if ($studentKelas) {
                        $query->orWhereJsonContains('kelas', $studentKelas);
                    }
                })
                ->latest()
                ->take(5)
                ->get()
                ->map(function($assignment) {
                    return [
                        'id' => 'assignment-' . $assignment->id,
                        'type' => 'assignment',
                        'title' => 'Tugas: ' . $assignment->title,
                        'description' => 'Deadline: ' . Carbon::parse($assignment->due_date)->format('d M Y'),
                        'time' => $assignment->created_at->diffForHumans()
                    ];
                });
            
            $recentActivities = $recentQuizzes->concat($recentAssignmentsStudent)
                ->sortByDesc('time')
                ->take(10)
                ->values();
            
            // Upcoming tasks for students
            $upcomingTasks = Assignment::where('status', 'active')
                ->where(function($query) use ($studentKelas) {
                    $query->whereNull('kelas');
                    if ($studentKelas) {
                        $query->orWhereJsonContains('kelas', $studentKelas);
                    }
                })
                ->where('due_date', '>=', today())
                ->orderBy('due_date', 'asc')
                ->take(5)
                ->get()
                ->map(function($assignment) {
                    return [
                        'id' => $assignment->id,
                        'title' => $assignment->title,
                        'course' => $assignment->course,
                        'due_date' => Carbon::parse($assignment->due_date)->format('d M Y'),
                        'status' => 'pending'
                    ];
                });
        }
        
        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'upcomingTasks' => $upcomingTasks,
            'isAdmin' => $isAdmin
        ]);
    }
}

