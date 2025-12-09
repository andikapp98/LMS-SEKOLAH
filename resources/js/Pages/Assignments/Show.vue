<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Tugas
                </h2>
                <Link href="/assignments" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Daftar Tugas
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Daftar Tugas', href: '/assignments' },
                { label: 'Detail Tugas' }
            ]" />

            <!-- Assignment Info Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Header with Actions -->
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-white flex-1">
                            <h3 class="text-2xl font-bold">{{ assignment.title }}</h3>
                            <p class="text-indigo-100 mt-1">{{ assignment.course }}</p>
                        </div>
                        <!-- Only show Edit/Delete if user is admin or creator -->
                        <div v-if="$page.props.auth.user.role === 'admin' || assignment.created_by === $page.props.auth.user.id" class="flex space-x-2">
                            <Link
                                :href="`/assignments/${assignment.id}/edit`"
                                class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </Link>
                            <button
                                @click="deleteAssignment"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Assignment Details -->
                <div class="p-6 space-y-6">
                    <!-- Status and Due Date -->
                    <div class="flex items-center justify-between pb-4 border-b">
                        <div>
                            <span :class="[
                                'px-3 py-1 text-sm font-semibold rounded-full',
                                assignment.status === 'completed' ? 'bg-green-100 text-green-800' :
                                assignment.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-gray-100 text-gray-800'
                            ]">
                                {{ getStatusLabel(assignment.status) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Batas Waktu</p>
                            <p class="text-lg font-semibold text-gray-900">{{ formatDate(assignment.due_date) }}</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Deskripsi Tugas
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ assignment.description || 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>

                    <!-- File Attachment -->
                    <div v-if="assignment.file_path">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            Lampiran File
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-10 h-10 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ getFileName(assignment.file_path) }}</p>
                                        <p class="text-sm text-gray-500">File lampiran tugas</p>
                                    </div>
                                </div>
                                <a
                                    :href="`/assignments/${assignment.id}/download`"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Creator Info -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Informasi Pembuat
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">
                                <span class="font-medium">Dibuat oleh:</span> {{ assignment.creator?.name || 'Unknown' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Dibuat pada: {{ formatDateTime(assignment.created_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Dialog -->
        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Tugas?"
            :message="`Apakah Anda yakin ingin menghapus tugas '${assignment.title}'? Tindakan ini tidak dapat dibatalkan.`"
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
    assignment: {
        type: Object,
        required: true
    }
});

const showDeleteConfirm = ref(false);

const formatDate = (date) => {
    if (!date) return '-';
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString('id-ID', options);
};

const formatDateTime = (date) => {
    if (!date) return '-';
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(date).toLocaleDateString('id-ID', options);
};

const getStatusLabel = (status) => {
    const labels = {
        'pending': 'Menunggu',
        'in_progress': 'Sedang Dikerjakan',
        'completed': 'Selesai'
    };
    return labels[status] || status;
};

const getFileName = (filePath) => {
    if (!filePath) return '';
    const parts = filePath.split('/');
    return parts[parts.length - 1];
};

const deleteAssignment = () => {
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/assignments/${props.assignment.id}`, {
        onSuccess: () => {
            router.visit('/assignments');
        }
    });
};
</script>
