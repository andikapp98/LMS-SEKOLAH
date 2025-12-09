<template>
    <AppLayout :user="$page.props.auth.user">
        <div class="max-w-6xl mx-auto py-8 px-4">
            <Breadcrumb :items="[
                { label: 'Kuis & Ujian', href: '/quizzes' },
                { label: quiz.title }
            ]" />

            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span :class="getStatusClass(quiz.status)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                {{ getStatusLabel(quiz.status) }}
                            </span>
                            <span v-if="quiz.duration" class="text-sm text-gray-500">
                                ⏱️ {{ quiz.duration }} menit
                            </span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ quiz.title }}</h1>
                        <p class="text-gray-600 mt-1">{{ quiz.course?.nama_mapel }}</p>
                        <p v-if="quiz.description" class="text-gray-600 mt-3">{{ quiz.description }}</p>
                    </div>
                    
                    <div class="flex gap-2" v-if="$page.props.auth.user.role !== 'student'">
                        <Link :href="`/quizzes/${quiz.id}/edit`" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                            ✏️ Edit
                        </Link>
                        <button @click="showDeleteConfirm = true" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                            🗑️ Hapus
                        </button>
                    </div>
                </div>

                <!-- Target Kelas -->
                <div v-if="quiz.kelas && quiz.kelas.length > 0" class="mt-4 pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Target Kelas:</h4>
                    <div class="flex flex-wrap gap-2">
                        <span v-for="kelas in quiz.kelas" :key="kelas" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ kelas }}
                        </span>
                    </div>
                </div>
                <div v-else class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-sm text-gray-500">📢 Kuis untuk semua kelas</span>
                </div>
            </div>

            <!-- Quiz Info Cards -->
            <div class="grid md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ quiz.questions?.length || 0 }}</div>
                    <div class="text-sm text-gray-500">Soal</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-3xl font-bold text-green-600">{{ quiz.passing_score }}%</div>
                    <div class="text-sm text-gray-500">Nilai Lulus</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ quiz.max_attempts }}x</div>
                    <div class="text-sm text-gray-500">Max Percobaan</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 text-center">
                    <div class="text-3xl font-bold text-orange-600">{{ totalPoints }}</div>
                    <div class="text-sm text-gray-500">Total Poin</div>
                </div>
            </div>

            <!-- Schedule Info -->
            <div v-if="quiz.available_from || quiz.available_until" class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">📅 Jadwal</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div v-if="quiz.available_from">
                        <span class="text-sm text-gray-500">Mulai:</span>
                        <span class="ml-2 font-medium">{{ formatDate(quiz.available_from) }}</span>
                    </div>
                    <div v-if="quiz.available_until">
                        <span class="text-sm text-gray-500">Berakhir:</span>
                        <span class="ml-2 font-medium">{{ formatDate(quiz.available_until) }}</span>
                    </div>
                </div>
            </div>

            <!-- Student: Start Quiz Button -->
            <div v-if="$page.props.auth.user.role === 'student'" class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Mulai Mengerjakan</h3>
                        <p class="text-sm text-gray-500">
                            Percobaan: {{ quiz.user_attempts || 0 }} / {{ quiz.max_attempts }}
                        </p>
                    </div>
                    <div>
                        <Link 
                            v-if="quiz.can_attempt && quiz.is_available"
                            :href="`/quizzes/${quiz.id}/take`"
                            class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold"
                        >
                            🚀 Mulai Kuis
                        </Link>
                        <span v-else-if="!quiz.is_available" class="px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                            ⏳ Kuis Belum Tersedia
                        </span>
                        <span v-else class="px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                            ❌ Batas Percobaan Habis
                        </span>
                    </div>
                </div>

                <!-- Student's Attempts History -->
                <div v-if="quiz.attempts && quiz.attempts.length > 0" class="mt-6 pt-4 border-t border-gray-200">
                    <h4 class="text-md font-semibold text-gray-900 mb-3">Riwayat Pengerjaan</h4>
                    <div class="space-y-2">
                        <Link 
                            v-for="attempt in quiz.attempts" 
                            :key="attempt.id"
                            :href="`/quiz-attempts/${attempt.id}`"
                            class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <span class="font-medium">Percobaan #{{ attempt.attempt_number }}</span>
                                    <span class="text-sm text-gray-500 ml-2">{{ formatDate(attempt.submitted_at || attempt.started_at) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span v-if="attempt.status === 'completed'" 
                                          :class="attempt.passed ? 'text-green-600' : 'text-red-600'"
                                          class="font-bold">
                                        {{ attempt.score }}%
                                    </span>
                                    <span v-else class="text-yellow-600 text-sm">Sedang dikerjakan</span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Questions Preview (for Teacher/Admin) -->
            <div v-if="$page.props.auth.user.role !== 'student'" class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📝 Daftar Soal</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    <div v-for="(question, index) in quiz.questions" :key="question.id" class="p-4">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-800 rounded-full font-bold text-sm">
                                {{ index + 1 }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 text-xs bg-gray-200 rounded">{{ getTypeLabel(question.type) }}</span>
                                    <span class="text-sm text-gray-500">{{ question.points }} poin</span>
                                </div>
                                <p class="text-gray-900">{{ question.question_text }}</p>
                                
                                <!-- Options for MCQ -->
                                <div v-if="question.type === 'multiple_choice' && question.options" class="mt-2 space-y-1">
                                    <div v-for="(option, i) in question.options" :key="i" 
                                         :class="question.correct_answer === i ? 'text-green-600 font-medium' : 'text-gray-600'"
                                         class="text-sm">
                                        {{ String.fromCharCode(65 + i) }}. {{ option.text }}
                                        <span v-if="question.correct_answer === i" class="ml-1">✓</span>
                                    </div>
                                </div>
                                
                                <!-- True/False Answer -->
                                <div v-if="question.type === 'true_false'" class="mt-2 text-sm text-green-600">
                                    Jawaban: {{ question.correct_answer === 'true' ? 'Benar' : 'Salah' }}
                                </div>
                                
                                <!-- Short Answer -->
                                <div v-if="question.type === 'short_answer'" class="mt-2 text-sm text-green-600">
                                    Jawaban: {{ question.correct_answer }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Attempts (for Teacher/Admin) -->
            <div v-if="$page.props.auth.user.role !== 'student' && quiz.attempts && quiz.attempts.length > 0" class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📊 Hasil Siswa ({{ quiz.attempts.length }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Percobaan</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Waktu</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="attempt in quiz.attempts" :key="attempt.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900">{{ attempt.student?.name || 'Unknown' }}</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-600">#{{ attempt.attempt_number }}</td>
                                <td class="px-4 py-3 text-sm text-center font-bold" :class="attempt.passed ? 'text-green-600' : 'text-red-600'">
                                    {{ attempt.score !== null ? attempt.score + '%' : '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span v-if="attempt.status === 'completed'" :class="attempt.passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs rounded-full">
                                        {{ attempt.passed ? 'Lulus' : 'Tidak Lulus' }}
                                    </span>
                                    <span v-else class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">
                                        Sedang Dikerjakan
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-center text-gray-600">{{ formatDate(attempt.submitted_at || attempt.started_at) }}</td>
                                <td class="px-4 py-3 text-center">
                                    <Link :href="`/quiz-attempts/${attempt.id}`" class="text-blue-600 hover:underline text-sm">
                                        Detail
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-else-if="$page.props.auth.user.role !== 'student'" class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="text-gray-400 text-5xl mb-4">📭</div>
                <p class="text-gray-600">Belum ada siswa yang mengerjakan kuis ini.</p>
            </div>
        </div>

        <!-- Delete Confirmation -->
        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Kuis?"
            :message="`Apakah Anda yakin ingin menghapus kuis '${quiz.title}'? Semua data percobaan siswa juga akan terhapus.`"
            confirm-text="Ya, Hapus"
            cancel-text="Batal"
            @confirm="deleteQuiz"
            @cancel="showDeleteConfirm = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({
    quiz: Object
});

const showDeleteConfirm = ref(false);

const totalPoints = computed(() => {
    return props.quiz.questions?.reduce((sum, q) => sum + q.points, 0) || 0;
});

const getStatusClass = (status) => {
    const classes = {
        draft: 'bg-gray-100 text-gray-800',
        published: 'bg-green-100 text-green-800',
        closed: 'bg-red-100 text-red-800'
    };
    return classes[status] || classes.draft;
};

const getStatusLabel = (status) => {
    const labels = {
        draft: 'Draft',
        published: 'Published',
        closed: 'Closed'
    };
    return labels[status] || status;
};

const getTypeLabel = (type) => {
    const labels = {
        multiple_choice: 'Pilihan Ganda',
        true_false: 'Benar/Salah',
        short_answer: 'Jawaban Singkat',
        essay: 'Essay'
    };
    return labels[type] || type;
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const deleteQuiz = () => {
    router.delete(`/quizzes/${props.quiz.id}`, {
        onSuccess: () => {
            showDeleteConfirm.value = false;
        }
    });
};
</script>
