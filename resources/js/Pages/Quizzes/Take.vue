<template>
    <AppLayout :user="$page.props.auth.user">
        <div class="max-w-4xl mx-auto py-8">
            <!-- Header dengan Timer -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ quiz.title }}</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ quiz.course?.nama_mapel }}</p>
                    </div>
                    <div v-if="quiz.duration" class="text-right">
                        <p class="text-sm text-gray-600">Waktu Tersisa</p>
                        <p :class="timeRemaining < 300 ? 'text-red-600' : 'text-blue-600'" class="text-3xl font-bold">
                            {{ formatTime(timeRemaining) }}
                        </p>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Progress</span>
                        <span>{{ answeredCount }} / {{ quiz.questions.length }} terjawab</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                            class="bg-blue-600 h-2 rounded-full transition-all"
                            :style="{ width: (answeredCount / quiz.questions.length * 100) + '%' }"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Question -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Soal {{ currentIndex + 1 }} dari {{ quiz.questions.length }}
                        </h3>
                        <span class="text-sm text-gray-600">{{ currentQuestion.points }} poin</span>
                    </div>
                    <p class="text-gray-800 whitespace-pre-line">{{ currentQuestion.question_text }}</p>
                </div>

                <!-- Multiple Choice -->
                <div v-if="currentQuestion.type === 'multiple_choice'" class="space-y-3">
                    <label 
                        v-for="(option, index) in currentQuestion.options" 
                        :key="index"
                        class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors"
                        :class="answers[currentQuestion.id] === index ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
                    >
                        <input
                            type="radio"
                            :name="`question-${currentQuestion.id}`"
                            :value="index"
                            v-model="answers[currentQuestion.id]"
                            class="mt-1 text-blue-600 focus:ring-blue-500"
                        />
                        <span class="ml-3 text-gray-900">{{ option.text }}</span>
                    </label>
                </div>

                <!-- True/False -->
                <div v-else-if="currentQuestion.type === 'true_false'" class="space-y-3">
                    <label 
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-blue-50"
                        :class="answers[currentQuestion.id] === 'true' ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
                    >
                        <input
                            type="radio"
                            v-model="answers[currentQuestion.id]"
                            value="true"
                            class="text-blue-600 focus:ring-blue-500"
                        />
                        <span class="ml-3 text-gray-900">✓ Benar</span>
                    </label>
                    <label 
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-blue-50"
                        :class="answers[currentQuestion.id] === 'false' ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
                    >
                        <input
                            type="radio"
                            v-model="answers[currentQuestion.id]"
                            value="false"
                            class="text-blue-600 focus:ring-blue-500"
                        />
                        <span class="ml-3 text-gray-900">✕ Salah</span>
                    </label>
                </div>

                <!-- Short Answer -->
                <div v-else-if="currentQuestion.type === 'short_answer'">
                    <input
                        v-model="answers[currentQuestion.id]"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Ketik jawaban Anda..."
                    />
                </div>

                <!-- Essay -->
                <div v-else-if="currentQuestion.type === 'essay'">
                    <textarea
                        v-model="answers[currentQuestion.id]"
                        rows="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Tulis jawaban essay Anda di sini..."
                    ></textarea>
                </div>
            </div>

            <!-- Navigation -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center">
                    <button
                        @click="previousQuestion"
                        :disabled="currentIndex === 0"
                        class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        ← Sebelumnya
                    </button>
                    
                    <div class="flex gap-2">
                        <button
                            v-for="(q, i) in quiz.questions"
                            :key="q.id"
                            @click="goToQuestion(i)"
                            class="w-10 h-10 rounded-lg font-semibold transition-colors"
                            :class="[
                                i === currentIndex ? 'bg-blue-600 text-white' : 
                                answers[q.id] !== undefined && answers[q.id] !== null && answers[q.id] !== '' ? 'bg-green-100 text-green-800 border-2 border-green-600' :
                                'bg-gray-100 text-gray-600 hover:bg-gray-200'
                            ]"
                        >
                            {{ i + 1 }}
                        </button>
                    </div>
                    
                    <button
                        v-if="currentIndex < quiz.questions.length - 1"
                        @click="nextQuestion"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Selanjutnya →
                    </button>
                    <button
                        v-else
                        @click="showSubmitConfirm = true"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                    >
                        Serahkan Jawaban ✓
                    </button>
                </div>
            </div>
        </div>

        <!-- Submit Confirmation -->
        <ConfirmDialog
            :show="showSubmitConfirm"
            type="warning"
            title="Serahkan Jawaban?"
            :message="`Anda telah menjawab ${answeredCount} dari ${quiz.questions.length} soal. Yakin ingin menyerahkan jawaban sekarang?`"
            confirm-text="Ya, Serahkan"
            cancel-text="Periksa Lagi"
            @confirm="submitQuiz"
            @cancel="showSubmitConfirm = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({
    quiz: Object,
    attempt: Object
});

const currentIndex = ref(0);
const answers = ref(props.attempt.answers || {});
const timeRemaining = ref(null);
const showSubmitConfirm = ref(false);
let timer = null;

const currentQuestion = computed(() => props.quiz.questions[currentIndex.value]);

const answeredCount = computed(() => {
    return Object.values(answers.value).filter(a => a !== null && a !== undefined && a !== '').length;
});

const formatTime = (seconds) => {
    if (!seconds) return '00:00';
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

const nextQuestion = () => {
    if (currentIndex.value < props.quiz.questions.length - 1) {
        currentIndex.value++;
    }
};

const previousQuestion = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

const goToQuestion = (index) => {
    currentIndex.value = index;
};

const submitQuiz = () => {
    router.post(`/quizzes/${props.quiz.id}/submit`, {
        attempt_id: props.attempt.id,
        answers: answers.value
    }, {
        onSuccess: () => {
            if (timer) clearInterval(timer);
        }
    });
};

// Timer setup
onMounted(() => {
    if (props.quiz.duration) {
        const startTime = new Date(props.attempt.started_at).getTime();
        const durationMs = props.quiz.duration * 60 * 1000;
        const endTime = startTime + durationMs;
        
        const updateTimer = () => {
            const now = Date.now();
            const remaining = Math.floor((endTime - now) / 1000);
            
            if (remaining <= 0) {
                timeRemaining.value = 0;
                clearInterval(timer);
                submitQuiz();
            } else {
                timeRemaining.value = remaining;
            }
        };
        
        updateTimer();
        timer = setInterval(updateTimer, 1000);
    }
    
    // Prevent accidental page close
    window.addEventListener('beforeunload', preventClose);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
    window.removeEventListener('beforeunload', preventClose);
});

const preventClose = (e) => {
    e.preventDefault();
    e.returnValue = 'Jawaban Anda belum diserahkan. Yakin ingin meninggalkan halaman?';
};
</script>
