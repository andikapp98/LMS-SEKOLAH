<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Guru
                </h2>
                <Link href="/teachers" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Daftar Guru
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Daftar Guru', href: '/teachers' },
                { label: 'Detail Guru' }
            ]" />

            <!-- Teacher Info Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Header with Actions -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-white">
                            <h3 class="text-2xl font-bold">{{ teacher.nama }}</h3>
                            <p class="text-green-100">NIK: {{ teacher.nik || '-' }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                :href="`/teachers/${teacher.id}/edit`"
                                class="inline-flex items-center px-4 py-2 bg-white text-green-600 rounded-lg hover:bg-green-50 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </Link>
                            <button
                                @click="deleteTeacher"
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

                <!-- Teacher Details -->
                <div class="p-6 space-y-6">
                    <!-- Data Pribadi -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Pribadi
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">NIK</p>
                                <p class="font-semibold text-gray-900">{{ teacher.nik || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Nama Lengkap</p>
                                <p class="font-semibold text-gray-900">{{ teacher.nama }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Pendidikan Terakhir</p>
                                <p class="font-semibold text-gray-900">{{ teacher.pendidikan_terakhir || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Mata Pelajaran</p>
                                <p class="font-semibold text-gray-900">{{ teacher.mata_pelajaran || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                                <p class="text-sm text-gray-600">Alamat</p>
                                <p class="font-semibold text-gray-900">{{ teacher.alamat || '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Kontak -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Data Kontak
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">No. HP</p>
                                <p class="font-semibold text-gray-900">{{ teacher.no_hp || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-gray-900">{{ teacher.email || '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Courses -->
                    <div v-if="teacher.courses && teacher.courses.length > 0">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Mata Pelajaran yang Diampu ({{ teacher.courses.length }})
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div
                                v-for="course in teacher.courses"
                                :key="course.id"
                                class="bg-blue-50 border border-blue-200 p-3 rounded-lg"
                            >
                                <p class="font-semibold text-blue-900">{{ course.nama_mapel }}</p>
                                <p class="text-sm text-blue-700">{{ course.kode_mapel }}</p>
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
            title="Hapus Guru?"
            :message="`Apakah Anda yakin ingin menghapus guru '${teacher.nama}'? Tindakan ini tidak dapat dibatalkan.`"
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
    teacher: {
        type: Object,
        required: true
    }
});

const showDeleteConfirm = ref(false);

const deleteTeacher = () => {
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/teachers/${props.teacher.id}`, {
        onSuccess: () => {
            router.visit('/teachers');
        }
    });
};
</script>
