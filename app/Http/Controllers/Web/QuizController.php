<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuizController extends Controller
{
    // Get available kelas options
    private function getKelasOptions()
    {
        return Student::select('kelas')
            ->distinct()
            ->whereNotNull('kelas')
            ->orderBy('kelas')
            ->pluck('kelas')
            ->toArray();
    }

    public function index()
    {
        $user = auth()->user();
        $query = Quiz::with(['course', 'creator', 'questions']);
        
        // Students: only show published quizzes for their class
        if ($user->role === 'student') {
            $query->where('status', 'published');
            
            // Filter by student's class
            $studentKelas = $user->student?->kelas;
            if ($studentKelas) {
                $query->where(function ($q) use ($studentKelas) {
                    $q->whereNull('kelas')
                      ->orWhereJsonContains('kelas', $studentKelas);
                });
            }
        }
        // Teachers: only see quizzes from their courses
        else if ($user->teacher) {
            $teacherCourseIds = $user->teacher->courses()->pluck('courses.id');
            $query->whereIn('course_id', $teacherCourseIds);
        }
        // Admin: see all
        
        $quizzes = $query->latest()->get();

        // Add user attempts info for students
        if ($user->role === 'student') {
            $quizzes->each(function ($quiz) use ($user) {
                $quiz->user_attempts = $quiz->attempts()
                    ->where('student_id', $user->id)
                    ->where('status', '!=', 'in_progress')
                    ->count();
                $quiz->can_attempt = $quiz->canAttempt($user->id);
                $quiz->is_available = $quiz->isAvailable();
            });
        }

        return Inertia::render('Quizzes/Index', [
            'quizzes' => $quizzes
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $courses = [];
        
        if ($user->teacher) {
            $courses = $user->teacher->courses()->get();
        } else if ($user->isAdmin()) {
            $courses = Course::all();
        }
        
        return Inertia::render('Quizzes/Create', [
            'courses' => $courses,
            'kelasOptions' => $this->getKelasOptions()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'kelas' => 'nullable|array',
            'duration' => 'nullable|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date|after:available_from',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'status' => 'required|in:draft,published,closed',
            'questions' => 'required|array|min:1',
            'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer,essay',
            'questions.*.question_text' => 'required|string',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_answer' => 'required_if:questions.*.type,multiple_choice,true_false,short_answer',
        ]);

        $quiz = Quiz::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'created_by' => auth()->id(),
            'kelas' => $validated['kelas'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'max_attempts' => $validated['max_attempts'],
            'passing_score' => $validated['passing_score'],
            'available_from' => $validated['available_from'] ?? null,
            'available_until' => $validated['available_until'] ?? null,
            'randomize_questions' => $validated['randomize_questions'] ?? false,
            'show_correct_answers' => $validated['show_correct_answers'] ?? true,
            'status' => $validated['status'],
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            Question::create([
                'quiz_id' => $quiz->id,
                'type' => $questionData['type'],
                'question_text' => $questionData['question_text'],
                'explanation' => $questionData['explanation'] ?? null,
                'points' => $questionData['points'],
                'order' => $index,
                'options' => $questionData['options'] ?? null,
                'correct_answer' => $questionData['correct_answer'] ?? null,
            ]);
        }

        return redirect('/quizzes')->with('success', 'Kuis berhasil dibuat!');
    }

    public function show(Quiz $quiz)
    {
        $user = auth()->user();
        $quiz->load(['course', 'creator', 'questions']);
        
        // For teacher/admin: load all attempts
        if ($user->role !== 'student') {
            $quiz->load(['attempts.student']);
        } else {
            // For student: load only their attempts
            $quiz->setRelation('attempts', 
                $quiz->attempts()->where('student_id', $user->id)->with('student')->get()
            );
            $quiz->user_attempts = $quiz->attempts->where('status', '!=', 'in_progress')->count();
            $quiz->can_attempt = $quiz->canAttempt($user->id);
            $quiz->is_available = $quiz->isAvailable();
        }
        
        return Inertia::render('Quizzes/Show', [
            'quiz' => $quiz
        ]);
    }

    public function edit(Quiz $quiz)
    {
        // Authorization check
        if (!auth()->user()->isAdmin() && $quiz->created_by !== auth()->id()) {
            abort(403);
        }

        $quiz->load('questions');
        
        $user = auth()->user();
        $courses = [];
        
        if ($user->teacher) {
            $courses = $user->teacher->courses()->get();
        } else if ($user->isAdmin()) {
            $courses = Course::all();
        }
        
        return Inertia::render('Quizzes/Edit', [
            'quiz' => $quiz,
            'courses' => $courses,
            'kelasOptions' => $this->getKelasOptions()
        ]);
    }

    public function update(Request $request, Quiz $quiz)
    {
        if (!auth()->user()->isAdmin() && $quiz->created_by !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'kelas' => 'nullable|array',
            'duration' => 'nullable|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'available_from' => 'nullable|date',
            'available_until' => 'nullable|date',
            'randomize_questions' => 'boolean',
            'show_correct_answers' => 'boolean',
            'status' => 'required|in:draft,published,closed',
            'questions' => 'required|array|min:1',
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'course_id' => $validated['course_id'],
            'kelas' => $validated['kelas'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'max_attempts' => $validated['max_attempts'],
            'passing_score' => $validated['passing_score'],
            'available_from' => $validated['available_from'] ?? null,
            'available_until' => $validated['available_until'] ?? null,
            'randomize_questions' => $validated['randomize_questions'] ?? false,
            'show_correct_answers' => $validated['show_correct_answers'] ?? true,
            'status' => $validated['status'],
        ]);

        // Delete old questions and create new ones
        $quiz->questions()->delete();
        
        foreach ($validated['questions'] as $index => $questionData) {
            Question::create([
                'quiz_id' => $quiz->id,
                'type' => $questionData['type'],
                'question_text' => $questionData['question_text'],
                'explanation' => $questionData['explanation'] ?? null,
                'points' => $questionData['points'],
                'order' => $index,
                'options' => $questionData['options'] ?? null,
                'correct_answer' => $questionData['correct_answer'] ?? null,
            ]);
        }

        return redirect('/quizzes')->with('success', 'Kuis berhasil diperbarui!');
    }

    public function destroy(Quiz $quiz)
    {
        if (!auth()->user()->isAdmin() && $quiz->created_by !== auth()->id()) {
            abort(403);
        }

        $quiz->delete();

        return redirect('/quizzes')->with('success', 'Kuis berhasil dihapus!');
    }

    // Student takes quiz
    public function take(Quiz $quiz)
    {
        $user = auth()->user();
        
        // Check if quiz is for student's class
        if ($user->role === 'student') {
            $studentKelas = $user->student?->kelas;
            if (!$quiz->isForStudent($studentKelas)) {
                return redirect('/quizzes')->with('error', 'Kuis ini tidak tersedia untuk kelas Anda.');
            }
        }
        
        if (!$quiz->isAvailable()) {
            return redirect('/quizzes')->with('error', 'Kuis tidak tersedia saat ini.');
        }

        if (!$quiz->canAttempt($user->id)) {
            return redirect('/quizzes')->with('error', 'Anda sudah mencapai batas maksimal percobaan.');
        }

        // Check for existing in-progress attempt
        $existingAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingAttempt) {
            $attempt = $existingAttempt;
        } else {
            // Create new attempt
            $attemptNumber = $quiz->attempts()->where('student_id', $user->id)->count() + 1;
            
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'student_id' => $user->id,
                'attempt_number' => $attemptNumber,
                'started_at' => now(),
                'answers' => [],
                'status' => 'in_progress'
            ]);
        }

        $quiz->load('questions');
        
        if ($quiz->randomize_questions) {
            $quiz->setRelation('questions', $quiz->questions->shuffle());
        }

        return Inertia::render('Quizzes/Take', [
            'quiz' => $quiz,
            'attempt' => $attempt
        ]);
    }

    // Submit quiz answers
    public function submit(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'attempt_id' => 'required|exists:quiz_attempts,id',
            'answers' => 'required|array'
        ]);

        $attempt = QuizAttempt::findOrFail($validated['attempt_id']);
        
        // Verify ownership
        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

        $attempt->answers = $validated['answers'];
        $attempt->submitted_at = now();
        $attempt->save();
        
        // Calculate score
        $attempt->calculateScore();

        return redirect("/quiz-attempts/{$attempt->id}")->with('success', 'Kuis berhasil diserahkan!');
    }

    // View quiz results
    public function results(QuizAttempt $attempt)
    {
        $user = auth()->user();
        
        // Students can only see their own results
        // Teachers can see results from their courses
        // Admins can see all
        if ($user->role === 'student' && $attempt->student_id !== $user->id) {
            abort(403);
        }
        
        if ($user->role === 'teacher') {
            $teacherCourseIds = $user->teacher->courses()->pluck('courses.id')->toArray();
            if (!in_array($attempt->quiz->course_id, $teacherCourseIds)) {
                abort(403);
            }
        }

        $attempt->load(['quiz.questions', 'quiz.course', 'student']);

        return Inertia::render('Quizzes/Results', [
            'attempt' => $attempt
        ]);
    }
}

