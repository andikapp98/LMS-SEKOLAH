<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Tambah Mata Pelajaran Baru
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">SMKS Yasmu Gresik - Manajemen Mata Pelajaran</p>
                </div>
                <Link href="/courses" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Kembali
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Mata Pelajaran', href: '/courses' },
                { label: 'Tambah Mata Pelajaran' }
            ]" />

            <div class="max-w-4xl mx-auto">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form @submit.prevent="submitForm" class="p-8">
                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Form Mata Pelajaran</h3>
                        <p class="text-gray-600">Isi form di bawah untuk menambahkan mata pelajaran baru</p>
                    </div>

                    <!-- Informasi Mata Pelajaran -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Informasi Mata Pelajaran
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kode Mata Pelajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Mata Pelajaran <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.kode_mapel"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Contoh: MAT-10"
                                />
                                <p class="mt-1 text-xs text-gray-500">Kode unik untuk mata pelajaran</p>
                            </div>

                            <!-- Nama Mata Pelajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Mata Pelajaran <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nama_mapel"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Contoh: Matematika"
                                />
                            </div>

                            <!-- Guru Pengampu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Guru Pengampu <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.teacher_id"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="">Pilih Guru</option>
                                    <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                                        {{ teacher.nama }} - {{ teacher.mata_pelajaran || 'Umum' }}
                                    </option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    Belum ada guru? 
                                    <Link href="/teachers/create" class="text-blue-600 hover:underline">Tambah guru baru</Link>
                                </p>
                            </div>

                            <!-- Kelas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kelas
                                </label>
                                <input
                                    v-model="form.kelas"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Contoh: 10 TPM 1"
                                />
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika untuk semua kelas</p>
                            </div>

                            <!-- Jam Per Minggu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jam Per Minggu <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model.number="form.jam_per_minggu"
                                    type="number"
                                    min="1"
                                    max="10"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="2"
                                />
                            </div>

                            <!-- Semester -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.semester"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="ganjil">Ganjil</option>
                                    <option value="genap">Genap</option>
                                </select>
                            </div>

                            <!-- Tahun Ajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tahun Ajaran <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.tahun_ajaran"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="2025/2026"
                                />
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi
                                </label>
                                <textarea
                                    v-model="form.deskripsi"
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Deskripsi singkat tentang mata pelajaran ini..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <Link
                            href="/courses"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                        >
                            <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isSubmitting ? 'Menyimpan...' : 'Simpan Mata Pelajaran' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props = defineProps({
    teachers: Array
});

const isSubmitting = ref(false);

const form = reactive({
    kode_mapel: '',
    nama_mapel: '',
    teacher_id: '',
    kelas: '',
    jam_per_minggu: 2,
    deskripsi: '',
    semester: 'ganjil',
    tahun_ajaran: '2025/2026',
});

const submitForm = () => {
    isSubmitting.value = true;
    
    router.post('/courses', form, {
        onSuccess: () => {
            alert('Mata pelajaran berhasil ditambahkan!');
        },
        onError: (errors) => {
            console.error(errors);
            alert('Terjadi kesalahan. Periksa form Anda.');
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>
