<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kuis & Ujian
                </h2>
                <Link 
                    v-if="$page.props.auth.user.role !== 'student'"
                    href="/quizzes/create" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Kuis Baru
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[{ label: 'Kuis & Ujian' }]" />

            <!-- Stats Card -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total Kuis</p>
                        <p class="text-4xl font-bold mt-2">{{ quizzes?.length || 0 }}</p>
                        <p class="text-green-100 text-sm mt-1">Tersedia di sistem</p>
                    </div>
                    <div>
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Quizzes List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Kuis</h3>
                </div>

                <div v-if="!quizzes || quizzes.length === 0" class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum ada kuis</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai buat kuis untuk mengukur pemahaman siswa</p>
                </div>

                <div v-else class="divide-y divide-gray-200">
                    <div v-for="quiz in quizzes" :key="quiz.id" class="p-4 md:p-6 hover:bg-gray-50 transition-colors">
                        <!-- Mobile-friendly card layout -->
                        <div class="space-y-3">
                            <!-- Header: Status + Title -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap mb-2">
                                        <span :class="getStatusClass(quiz.status)" class="px-2 py-0.5 text-xs font-semibold rounded-full">
                                            {{ getStatusLabel(quiz.status) }}
                                        </span>
                                        <span v-if="quiz.duration" class="text-xs text-gray-500">
                                            ⏱️ {{ quiz.duration }} menit
                                        </span>
                                        <!-- Target Kelas Badge for teachers/admin -->
                                        <span v-if="$page.props.auth.user.role !== 'student'" class="text-xs text-gray-500">
                                            🎯 {{ quiz.kelas && quiz.kelas.length > 0 ? quiz.kelas.join(', ') : 'Semua Kelas' }}
                                        </span>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 truncate">{{ quiz.title }}</h4>
                                    <p class="text-sm text-gray-600">{{ quiz.course?.nama_mapel || 'Unknown' }}</p>
                                </div>
                                
                                <!-- Action buttons for desktop -->
                                <div class="hidden md:flex flex-col gap-2">
                                    <Link :href="`/quizzes/${quiz.id}`" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center">
                                        Detail
                                    </Link>
                                    <template v-if="$page.props.auth.user.role !== 'student'">
                                        <Link :href="`/quizzes/${quiz.id}/edit`" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 text-center">
                                            Edit
                                        </Link>
                                        <button @click="deleteQuiz(quiz)" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Description -->
                            <p v-if="quiz.description" class="text-sm text-gray-600 line-clamp-2">
                                {{ quiz.description }}
                            </p>

                            <!-- Info Pills -->
                            <div class="flex items-center gap-2 flex-wrap text-xs">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                    📝 {{ quiz.questions?.length || 0 }} soal
                                </span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                    🔄 {{ quiz.max_attempts }}x percobaan
                                </span>
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                    📊 Lulus {{ quiz.passing_score }}%
                                </span>
                                <!-- Student progress -->
                                <span v-if="$page.props.auth.user.role === 'student' && quiz.user_attempts !== undefined" 
                                      class="px-2 py-1 bg-blue-100 text-blue-700 rounded font-medium">
                                    ✍️ {{ quiz.user_attempts }}/{{ quiz.max_attempts }} dikerjakan
                                </span>
                            </div>

                            <!-- Schedule Info for Students -->
                            <div v-if="quiz.available_from || quiz.available_until" 
                                 class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <div class="flex items-center gap-2 text-amber-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium">Jadwal Kuis</span>
                                </div>
                                <div class="mt-1 text-sm text-amber-700">
                                    <span v-if="quiz.available_from">Mulai: <strong>{{ formatDate(quiz.available_from) }}</strong></span>
                                    <span v-if="quiz.available_from && quiz.available_until"> • </span>
                                    <span v-if="quiz.available_until">Berakhir: <strong>{{ formatDate(quiz.available_until) }}</strong></span>
                                </div>
                            </div>

                            <!-- Student Action Buttons -->
                            <div v-if="$page.props.auth.user.role === 'student'" class="flex gap-2 pt-2">
                                <Link :href="`/quizzes/${quiz.id}`" 
                                      class="flex-1 px-4 py-2.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-center font-medium">
                                    📋 Lihat Detail
                                </Link>
                                <Link v-if="quiz.can_attempt && quiz.is_available"
                                      :href="`/quizzes/${quiz.id}/take`"
                                      class="flex-1 px-4 py-2.5 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-center font-medium">
                                    🚀 Mulai Kuis
                                </Link>
                                <span v-else-if="!quiz.is_available && quiz.available_from && new Date(quiz.available_from) > new Date()" 
                                      class="flex-1 px-4 py-2.5 text-sm bg-amber-100 text-amber-700 rounded-lg text-center font-medium cursor-not-allowed">
                                    ⏳ Mulai {{ formatTimeOnly(quiz.available_from) }}
                                </span>
                                <span v-else-if="!quiz.is_available" 
                                      class="flex-1 px-4 py-2.5 text-sm bg-gray-200 text-gray-500 rounded-lg text-center font-medium cursor-not-allowed">
                                    🔒 Kuis Ditutup
                                </span>
                                <span v-else 
                                      class="flex-1 px-4 py-2.5 text-sm bg-green-100 text-green-700 rounded-lg text-center font-medium cursor-not-allowed">
                                    ✅ Sudah Selesai
                                </span>
                            </div>

                            <!-- Mobile Action Buttons for Teacher/Admin -->
                            <div v-if="$page.props.auth.user.role !== 'student'" class="flex gap-2 pt-2 md:hidden">
                                <Link :href="`/quizzes/${quiz.id}`" class="flex-1 px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center">
                                    Detail
                                </Link>
                                <Link :href="`/quizzes/${quiz.id}/edit`" class="flex-1 px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 text-center">
                                    Edit
                                </Link>
                                <button @click="deleteQuiz(quiz)" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Dialog -->
        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Kuis?"
            :message="deleteMessage"
            confirm-text="Ya, Hapus"
            cancel-text="Batal"
            @confirm="confirmDelete"
            @cancel="showDeleteConfirm = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({
    quizzes: {
        type: Array,
        required: true
    }
});

const showDeleteConfirm = ref(false);
const quizToDelete = ref(null);
const deleteMessage = ref('');

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

const formatTimeOnly = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const deleteQuiz = (quiz) => {
    quizToDelete.value = quiz;
    deleteMessage.value = `Apakah Anda yakin ingin menghapus kuis "${quiz.title}"? Semua data attempt siswa juga akan terhapus. Tindakan ini tidak dapat dibatalkan.`;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/quizzes/${quizToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            quizToDelete.value = null;
        }
    });
};
</script>
