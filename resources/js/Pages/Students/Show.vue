<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Siswa
                </h2>
                <Link href="/students" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Daftar Siswa
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Daftar Siswa', href: '/students' },
                { label: 'Detail Siswa' }
            ]" />

            <!-- Student Info Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Header with Actions -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div class="text-white">
                            <h3 class="text-2xl font-bold">{{ student.nama }}</h3>
                            <p class="text-blue-100">NIS: {{ student.nis }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <Link
                                :href="`/students/${student.id}/edit`"
                                class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </Link>
                            <button
                                @click="deleteStudent"
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

                <!-- Student Details -->
                <div class="p-6 space-y-6">
                    <!-- Data Pribadi -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Pribadi
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">NIS</p>
                                <p class="font-semibold text-gray-900">{{ student.nis }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">NISN</p>
                                <p class="font-semibold text-gray-900">{{ student.nisn || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                                <p class="text-sm text-gray-600">Nama Lengkap</p>
                                <p class="font-semibold text-gray-900">{{ student.nama }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Jenis Kelamin</p>
                                <p class="font-semibold text-gray-900">
                                    <span v-if="student.jenis_kelamin === 'L'" class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        Laki-laki
                                    </span>
                                    <span v-else class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        Perempuan
                                    </span>
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Tempat, Tanggal Lahir</p>
                                <p class="font-semibold text-gray-900">
                                    {{ student.tempat_lahir || '-' }}{{ student.tanggal_lahir ? ', ' + formatDate(student.tanggal_lahir) : '' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
                                <p class="text-sm text-gray-600">Alamat</p>
                                <p class="font-semibold text-gray-900">{{ student.alamat || '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Akademik -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Data Akademik
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Kelas</p>
                                <p class="font-semibold text-gray-900">
                                    <span v-if="student.kelas" class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ student.kelas }}
                                    </span>
                                    <span v-else>-</span>
                                </p>
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
                                <p class="text-sm text-gray-600">No. HP Siswa</p>
                                <p class="font-semibold text-gray-900">{{ student.no_hp || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-gray-900">{{ student.email || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">Nama Wali/Orang Tua</p>
                                <p class="font-semibold text-gray-900">{{ student.nama_wali || '-' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600">No. HP Wali</p>
                                <p class="font-semibold text-gray-900">{{ student.no_hp_wali || '-' }}</p>
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
            title="Hapus Siswa?"
            :message="`Apakah Anda yakin ingin menghapus siswa '${student.nama}' (NIS: ${student.nis})? Tindakan ini tidak dapat dibatalkan.`"
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
    student: {
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

const deleteStudent = () => {
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/students/${props.student.id}`, {
        onSuccess: () => {
            router.visit('/students');
        }
    });
};
</script>
