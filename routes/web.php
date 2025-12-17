<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AssignmentController;
use App\Http\Controllers\Web\StudentController as WebStudentController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\TeacherController;
use App\Http\Controllers\Web\LearningMaterialController;
use App\Http\Controllers\Web\QuizController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\StudentController;

// Home/Landing Page - Public
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Allow logout from any state (even if not authenticated)
Route::match(['post', 'delete'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Assignments - Accessible by ALL authenticated users (teachers can manage)
    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{assignment}/edit', [AssignmentController::class, 'edit'])->name('assignments.edit');
    Route::get('/assignments/{assignment}/download', [AssignmentController::class, 'download'])->name('assignments.download');
    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');
    
    // Learning Media - Accessible by ALL authenticated users (teachers can manage their own)
    Route::get('/learning-media', [LearningMaterialController::class, 'index'])->name('learning-media.index');
    Route::get('/learning-media/create', [LearningMaterialController::class, 'create'])->name('learning-media.create');
    Route::post('/learning-media', [LearningMaterialController::class, 'store'])->name('learning-media.store');
    Route::get('/learning-media/{learningMaterial}', [LearningMaterialController::class, 'show'])->name('learning-media.show');
    Route::get('/learning-media/{learningMaterial}/edit', [LearningMaterialController::class, 'edit'])->name('learning-media.edit');
    Route::get('/learning-media/{learningMaterial}/download', [LearningMaterialController::class, 'download'])->name('learning-media.download');
    Route::put('/learning-media/{learningMaterial}', [LearningMaterialController::class, 'update'])->name('learning-media.update');
    Route::delete('/learning-media/{learningMaterial}', [LearningMaterialController::class, 'destroy'])->name('learning-media.destroy');
    
    // Quizzes - Accessible by ALL authenticated users
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    
    // Student quiz taking
    Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quiz-attempts/{attempt}', [QuizController::class, 'results'])->name('quiz-attempts.show');
});

// Admin-only routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Students
    Route::get('/students', [WebStudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [WebStudentController::class, 'create'])->name('students.create');
    Route::post('/students', [WebStudentController::class, 'store'])->name('students.store');
    Route::get('/students/upload', [WebStudentController::class, 'uploadForm'])->name('students.upload');
    Route::post('/students/import-nominatif', [WebStudentController::class, 'import'])->name('students.import.nominatif');
    Route::delete('/students/clear', [WebStudentController::class, 'clear'])->name('students.clear');
    Route::get('/students/{student}', [WebStudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [WebStudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [WebStudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [WebStudentController::class, 'destroy'])->name('students.destroy');
    
    
    // API routes for students (accessible from web with auth)
    Route::post('/api/students', [StudentController::class, 'store'])->name('api.students.store');
    Route::post('/api/students/import', [StudentController::class, 'importExcel'])->name('api.students.import');
    Route::get('/api/students/template', [StudentController::class, 'downloadTemplate'])->name('api.students.template');
    
    // Courses (Mata Pelajaran)
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/upload', [CourseController::class, 'uploadForm'])->name('courses.upload');
    Route::post('/courses/import', [CourseController::class, 'import'])->name('courses.import');
    Route::post('/courses/clear', [CourseController::class, 'clear'])->name('courses.clear');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    
    // Teachers (Guru)
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/upload', [TeacherController::class, 'uploadForm'])->name('teachers.upload');
    Route::post('/teachers/import', [TeacherController::class, 'import'])->name('teachers.import');
    Route::delete('/teachers/bulk-delete', [TeacherController::class, 'bulkDelete'])->name('teachers.bulk-delete');
    Route::delete('/teachers/clear', [TeacherController::class, 'clear'])->name('teachers.clear');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
    Route::post('/teachers/{teacher}/reset-password', [TeacherController::class, 'resetPassword'])->name('teachers.reset-password');
    
    // Student password reset
    Route::post('/students/{student}/reset-password', [WebStudentController::class, 'resetPassword'])->name('students.reset-password');
});
