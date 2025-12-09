<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Mata Pelajaran</h2>
                <Link href="/courses" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Daftar Mata Pelajaran
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Mata Pelajaran', href: '/courses' },
                { label: 'Detail Mata Pelajaran' }
            ]" />

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-white">
                            <h3 class="text-2xl font-bold">{{ course.nama_mapel }}</h3>
                            <p class="text-purple-100">Kode: {{ course.kode_mapel }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                :href="`/courses/${course.id}/edit`"
                                class="inline-flex items-center px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-purple-50 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </Link>
                            <button
                                @click="deleteCourse"
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

                <div class="p-6 space-y-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Informasi Mata Pelajaran
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Kode Mata Pelajaran</p>
                                <p class="font-semibold text-gray-900">{{ course.kode_mapel }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Nama Mata Pelajaran</p>
                                <p class="font-semibold text-gray-900">{{ course.nama_mapel }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-2">Guru Pengampu</p>
                                <div v-if="course.teachers && course.teachers.length > 0" class="space-y-2">
                                    <div v-for="teacher in course.teachers" :key="teacher.id" class="inline-block mr-2 mb-2">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            {{ teacher.nama }}
                                        </span>
                                    </div>
                                </div>
                                <p v-else class="font-semibold text-gray-500">Belum ada guru</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Jam per Minggu</p>
                                <p class="font-semibold text-gray-900">{{ course.jam_per_minggu }} JP</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Kelas</p>
                                <p class="font-semibold text-gray-900">{{ course.kelas || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Semester</p>
                                <p class="font-semibold text-gray-900 capitalize">{{ course.semester }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Tahun Ajaran</p>
                                <p class="font-semibold text-gray-900">{{ course.tahun_ajaran }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                                <p class="text-sm text-gray-600">Deskripsi</p>
                                <p class="font-semibold text-gray-900">{{ course.deskripsi || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Mata Pelajaran?"
            :message="`Apakah Anda yakin ingin menghapus mata pelajaran '${course.nama_mapel}'? Tindakan ini tidak dapat dibatalkan.`"
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
    course: {
        type: Object,
        required: true
    }
});

const showDeleteConfirm = ref(false);

const deleteCourse = () => {
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/courses/${props.course.id}`, {
        onSuccess: () => {
            router.visit('/courses');
        }
    });
};
</script>
