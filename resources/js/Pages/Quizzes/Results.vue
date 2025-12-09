<template>
    <AppLayout :user="$page.props.auth.user">
        <div class="max-w-4xl mx-auto py-8">
            <!-- Result Header -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
                <div class="text-center">
                    <div v-if="attempt.status === 'graded'" class="mb-4">
                        <div v-if="isPassed" class="text-6xl mb-2">🎉</div>
                        <div v-else class="text-6xl mb-2">📝</div>
                    </div>
                    
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ quiz.title }}</h1>
                    
                    <div v-if="attempt.status === 'graded'" class="mt-6">
                        <div class="inline-block">
                            <div class="text-6xl font-bold mb-2" :class="isPassed ? 'text-green-600' : 'text-yellow-600'">
                                {{ attempt.score }}%
                            </div>
                            <div class="text-gray-600">
                                {{ attempt.points_earned }} / {{ attempt.total_points }} poin
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <span 
                                class="inline-block px-6 py-2 rounded-full text-lg font-semibold"
                                :class="isPassed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                            >
                                {{ isPassed ? '✓ LULUS' : '✗ Belum Lulus' }}
                            </span>
                        </div>
                    </div>
                    
                    <div v-else class="mt-6">
                        <div class="inline-block px-6 py-3 bg-blue-100 text-blue-800 rounded-lg">
                            <p class="font-semibold">Jawaban Anda sedang dinilai</p>
                            <p class="text-sm mt-1">Kuis ini memiliki soal essay yang perlu dinilai manual oleh guru</p>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mt-8 pt-6 border-t">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Percobaan ke</p>
                        <p class="text-2xl font-bold text-gray-900">{{ attempt.attempt_number }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Waktu Pengerjaan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ timeTaken }} menit</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Passing Score</p>
                        <p class="text-2xl font-bold text-gray-900">{{ quiz.passing_score }}%</p>
                    </div>
                </div>
            </div>

            <!-- Review Answers (if allowed) -->
            <div v-if="quiz.show_correct_answers && attempt.status === 'graded'" class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Jawaban</h3>
                
                <div class="space-y-6">
                    <div 
                        v-for="(question, index) in quiz.questions" 
                        :key="question.id"
                        class="border-b border-gray-200 pb-4 last:border-0"
                    >
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900">Soal #{{ index + 1 }}</h4>
                            <span 
                                class="px-3 py-1 rounded-full text-sm font-semibold"
                                :class="isCorrect(question) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                            >
                                {{ isCorrect(question) ? '✓ Benar' : '✗ Salah' }}
                            </span>
                        </div>
                        
                        <p class="text-gray-800 mb-3">{{ question.question_text }}</p>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Jawaban Anda:</p>
                                    <p class="text-gray-900">{{ formatAnswer(question, attempt.answers[question.id]) }}</p>
                                </div>
                                <div v-if="question.type !== 'essay'">
                                    <p class="text-sm font-medium text-gray-600 mb-1">Jawaban Benar:</p>
                                    <p class="text-green-700 font-semibold">{{ formatAnswer(question, question.correct_answer) }}</p>
                                </div>
                            </div>
                            
                            <div v-if="question.explanation" class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm font-medium text-gray-600 mb-1">💡 Penjelasan:</p>
                                <p class="text-sm text-gray-700">{{ question.explanation }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-center gap-4">
                <Link href="/quizzes" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    Kembali ke Daftar Kuis
                </Link>
                <Link 
                    v-if="canRetry"
                    :href="`/quizzes/${quiz.id}/take`"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                    🔄 Coba Lagi
                </Link>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    attempt: Object
});

const quiz = computed(() => props.attempt.quiz);

const isPassed = computed(() => {
    return props.attempt.score >= quiz.value.passing_score;
});

const timeTaken = computed(() => {
    if (!props.attempt.submitted_at || !props.attempt.started_at) return '-';
    const start = new Date(props.attempt.started_at);
    const end = new Date(props.attempt.submitted_at);
    const mins = Math.floor((end - start) / 1000 / 60);
    return mins || 1;
});

const canRetry = computed(() => {
    const attemptCount = props.attempt.attempt_number;
    return attemptCount < quiz.value.max_attempts && quiz.value.status === 'published';
});

const isCorrect = (question) => {
    if (question.type === 'essay') return null;
    
    const studentAnswer = props.attempt.answers[question.id];
    const correctAnswer = question.correct_answer;
    
    if (question.type === 'short_answer') {
        return String(studentAnswer).toLowerCase().trim() === String(correctAnswer).toLowerCase().trim();
    }
    
    return String(studentAnswer) === String(correctAnswer);
};

const formatAnswer = (question, answer) => {
    if (!answer && answer !== 0 && answer !== false) return '-';
    
    if (question.type === 'multiple_choice') {
        const option = question.options[answer];
        return option ? option.text : '-';
    }
    
    if (question.type === 'true_false') {
        return answer === 'true' || answer === true ? 'Benar' : 'Salah';
    }
    
    return answer;
};
</script>
