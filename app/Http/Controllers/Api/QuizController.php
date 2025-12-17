<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuizController extends Controller
{
    /**
     * Get all quizzes
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Quiz::with(['course', 'creator'])
                     ->withCount('questions');

        // Filter by role
        if ($user->role === 'student') {
            $student = $user->student;
            $studentKelas = $student?->kelas;
            
            $query->where('status', 'published')
                  ->where(function($q) use ($studentKelas) {
                      $q->whereNull('kelas')
                        ->orWhereRaw("jsonb_array_length(kelas::jsonb) = 0");
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

        $quizzes = $query->orderBy('created_at', 'desc')
                         ->paginate($request->per_page ?? 15);

        // Add additional info for students
        if ($user->role === 'student') {
            $quizzes->getCollection()->transform(function ($quiz) use ($user) {
                $quiz->user_attempts = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('student_id', $user->id)
                    ->count();
                $quiz->can_attempt = $quiz->max_attempts === null || $quiz->user_attempts < $quiz->max_attempts;
                $quiz->is_available = $quiz->isAvailable();
                return $quiz;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $quizzes
        ]);
    }

    /**
     * Get single quiz
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $quiz = Quiz::with(['course', 'creator', 'questions'])
                    ->withCount('questions')
                    ->find($id);
        
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        // Add attempt info for students
        if ($user->role === 'student') {
            $quiz->user_attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $user->id)
                ->count();
            $quiz->can_attempt = $quiz->max_attempts === null || $quiz->user_attempts < $quiz->max_attempts;
            $quiz->is_available = $quiz->isAvailable();
            
            // Get student's attempts
            $quiz->attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('student_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $quiz
        ]);
    }

    /**
     * Create new quiz
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'duration' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'kelas' => 'nullable|array',
            'status' => 'in:draft,published',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_answer' => 'nullable|string',
            'questions.*.points' => 'nullable|integer|min:1'
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['status'] = $validated['status'] ?? 'draft';

        // Create quiz
        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'created_by' => $validated['created_by'],
            'duration' => $validated['duration'] ?? null,
            'max_attempts' => $validated['max_attempts'] ?? null,
            'passing_score' => $validated['passing_score'] ?? 60,
            'available_from' => $validated['available_from'] ?? null,
            'available_until' => $validated['available_until'] ?? null,
            'randomize_questions' => $validated['randomize_questions'] ?? false,
            'show_correct_answers' => $validated['show_correct_answers'] ?? false,
            'kelas' => $validated['kelas'] ?? [],
            'status' => $validated['status']
        ]);

        // Create questions
        foreach ($validated['questions'] as $index => $question) {
            QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $question['question'],
                'type' => $question['type'],
                'options' => $question['options'] ?? null,
                'correct_answer' => $question['correct_answer'] ?? null,
                'points' => $question['points'] ?? 1,
                'order' => $index + 1
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil dibuat',
            'data' => $quiz->load('questions')
        ], 201);
    }

    /**
     * Update quiz
     */
    public function update(Request $request, $id)
    {
        $quiz = Quiz::find($id);
        
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'exists:courses,id',
            'duration' => 'nullable|integer|min:1',
            'max_attempts' => 'nullable|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'kelas' => 'nullable|array',
            'status' => 'in:draft,published'
        ]);

        $quiz->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil diupdate',
            'data' => $quiz
        ]);
    }

    /**
     * Delete quiz
     */
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        // Delete related data
        QuizQuestion::where('quiz_id', $id)->delete();
        QuizAttempt::where('quiz_id', $id)->delete();
        
        $quiz->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil dihapus'
        ]);
    }

    /**
     * Start quiz attempt
     */
    public function take(Request $request, $id)
    {
        $user = $request->user();
        $quiz = Quiz::with('questions')->find($id);
        
        if (!$quiz) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak ditemukan'
            ], 404);
        }

        // Check if quiz is available
        if (!$quiz->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Quiz tidak tersedia saat ini'
            ], 403);
        }

        // Check max attempts
        if (!$quiz->canAttempt($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mencapai batas maksimal percobaan'
            ], 403);
        }

        // Check existing in-progress attempt
        $existingAttempt = QuizAttempt::where('quiz_id', $id)
            ->where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingAttempt) {
            $attempt = $existingAttempt;
        } else {
            $attempt = QuizAttempt::create([
                'quiz_id' => $id,
                'student_id' => $user->id,
                'started_at' => now(),
                'status' => 'in_progress'
            ]);
        }

        // Prepare questions
        $questions = $quiz->questions;
        if ($quiz->randomize_questions) {
            $questions = $questions->shuffle();
        }

        // Hide correct answers
        $questions = $questions->map(function($q) {
            return [
                'id' => $q->id,
                'question' => $q->question,
                'type' => $q->type,
                'options' => $q->options,
                'points' => $q->points
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'attempt_id' => $attempt->id,
                'quiz' => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'duration' => $quiz->duration,
                    'questions_count' => $quiz->questions->count()
                ],
                'questions' => $questions,
                'started_at' => $attempt->started_at
            ]
        ]);
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, $id)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'attempt_id' => 'required|exists:quiz_attempts,id',
            'answers' => 'required|array'
        ]);

        $attempt = QuizAttempt::where('id', $validated['attempt_id'])
            ->where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => 'Attempt tidak valid'
            ], 404);
        }

        $quiz = Quiz::with('questions')->find($id);
        $totalPoints = 0;
        $earnedPoints = 0;

        // Process answers
        foreach ($quiz->questions as $question) {
            $totalPoints += $question->points;
            $answer = $validated['answers'][$question->id] ?? null;

            // Check correct answers for objective questions
            if (in_array($question->type, ['multiple_choice', 'true_false'])) {
                if ($answer === $question->correct_answer) {
                    $earnedPoints += $question->points;
                }
            }

            // Save answer
            QuizAnswer::updateOrCreate(
                [
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id
                ],
                [
                    'answer' => $answer,
                    'is_correct' => $answer === $question->correct_answer,
                    'points_earned' => ($answer === $question->correct_answer) ? $question->points : 0
                ]
            );
        }

        // Calculate score
        $score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
        $passed = $score >= ($quiz->passing_score ?? 60);

        // Update attempt
        $attempt->update([
            'completed_at' => now(),
            'score' => $score,
            'status' => 'completed',
            'passed' => $passed
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil diselesaikan',
            'data' => [
                'score' => $score,
                'passed' => $passed,
                'passing_score' => $quiz->passing_score ?? 60,
                'total_questions' => $quiz->questions->count(),
                'correct_answers' => $earnedPoints
            ]
        ]);
    }

    /**
     * Get quiz stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'admin') {
            $total = Quiz::count();
            $published = Quiz::where('status', 'published')->count();
            $draft = Quiz::where('status', 'draft')->count();
        } elseif ($user->role === 'teacher') {
            $total = Quiz::where('created_by', $user->id)->count();
            $published = Quiz::where('created_by', $user->id)->where('status', 'published')->count();
            $draft = Quiz::where('created_by', $user->id)->where('status', 'draft')->count();
        } else {
            // Student stats
            $total = QuizAttempt::where('student_id', $user->id)->count();
            $published = QuizAttempt::where('student_id', $user->id)->where('passed', true)->count();
            $draft = QuizAttempt::where('student_id', $user->id)->where('passed', false)->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'published' => $published,
                'draft' => $draft
            ]
        ]);
    }
}
