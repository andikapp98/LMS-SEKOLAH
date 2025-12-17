<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\LearningMaterialController;

Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', function (Request $request) {
                $request->user()->currentAccessToken()->delete();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Logout berhasil'
                ]);
            });
            
            Route::get('/user', function (Request $request) {
                return response()->json([
                    'success' => true,
                    'data' => $request->user()
                ]);
            });
        });
    });

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
            Route::get('/quick-stats', [DashboardController::class, 'quickStats']);
        });

        // Students
        Route::prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index']);
            Route::post('/', [StudentController::class, 'store']);
            Route::get('/jurusan', [StudentController::class, 'getJurusan']);
            Route::get('/class-info', [StudentController::class, 'getClassInfo']);
            Route::get('/{id}', [StudentController::class, 'show']);
            Route::put('/{id}', [StudentController::class, 'update']);
            Route::delete('/{id}', [StudentController::class, 'destroy']);
            
            Route::post('/import', [StudentController::class, 'importExcel']);
            Route::get('/template/download', [StudentController::class, 'downloadTemplate']);
        });

        // Teachers
        Route::prefix('teachers')->group(function () {
            Route::get('/stats', [TeacherController::class, 'stats']);
            Route::get('/', [TeacherController::class, 'index']);
            Route::post('/', [TeacherController::class, 'store']);
            Route::get('/{id}', [TeacherController::class, 'show']);
            Route::put('/{id}', [TeacherController::class, 'update']);
            Route::delete('/{id}', [TeacherController::class, 'destroy']);
        });

        // Courses
        Route::prefix('courses')->group(function () {
            Route::get('/stats', [CourseController::class, 'stats']);
            Route::get('/', [CourseController::class, 'index']);
            Route::post('/', [CourseController::class, 'store']);
            Route::get('/{id}', [CourseController::class, 'show']);
            Route::put('/{id}', [CourseController::class, 'update']);
            Route::delete('/{id}', [CourseController::class, 'destroy']);
        });

        // Assignments
        Route::prefix('assignments')->group(function () {
            Route::get('/stats', [AssignmentController::class, 'stats']);
            Route::get('/', [AssignmentController::class, 'index']);
            Route::post('/', [AssignmentController::class, 'store']);
            Route::get('/{id}', [AssignmentController::class, 'show']);
            Route::put('/{id}', [AssignmentController::class, 'update']);
            Route::delete('/{id}', [AssignmentController::class, 'destroy']);
        });

        // Quizzes
        Route::prefix('quizzes')->group(function () {
            Route::get('/stats', [QuizController::class, 'stats']);
            Route::get('/', [QuizController::class, 'index']);
            Route::post('/', [QuizController::class, 'store']);
            Route::get('/{id}', [QuizController::class, 'show']);
            Route::put('/{id}', [QuizController::class, 'update']);
            Route::delete('/{id}', [QuizController::class, 'destroy']);
            Route::post('/{id}/take', [QuizController::class, 'take']);
            Route::post('/{id}/submit', [QuizController::class, 'submit']);
        });

        // Learning Materials
        Route::prefix('materials')->group(function () {
            Route::get('/stats', [LearningMaterialController::class, 'stats']);
            Route::get('/', [LearningMaterialController::class, 'index']);
            Route::post('/', [LearningMaterialController::class, 'store']);
            Route::get('/{id}', [LearningMaterialController::class, 'show']);
            Route::put('/{id}', [LearningMaterialController::class, 'update']);
            Route::delete('/{id}', [LearningMaterialController::class, 'destroy']);
            Route::get('/{id}/download', [LearningMaterialController::class, 'download']);
        });
    });
});

