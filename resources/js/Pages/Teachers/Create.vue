<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                        <svg class="w-8 h-8 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Tambah Guru Baru
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">SMKS Yasmu Gresik - Manajemen Data Guru</p>
                </div>
                <Link href="/teachers" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Kembali
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Guru', href: '/teachers' },
                { label: 'Tambah Guru Baru' }
            ]" />

            <div class="max-w-4xl mx-auto">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <form @submit.prevent="submitForm" class="p-8">
                    <!-- Header -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Form Data Guru</h3>
                        <p class="text-gray-600">Isi form di bawah untuk menambahkan data guru baru</p>
                    </div>

                    <!-- Data Pribadi -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Pribadi
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIK -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    NIK (Nomor Induk Kependudukan)
                                </label>
                                <input
                                    v-model="form.nik"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="3524010101900001"
                                />
                                <p class="mt-1 text-xs text-gray-500">16 digit NIK (opsional)</p>
                            </div>

                            <!-- Nama -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nama"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Drs. Ahmad Fauzi, M.Pd"
                                />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="ahmad.fauzi@smksyasmu.sch.id"
                                />
                            </div>

                            <!-- No HP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    No. HP
                                </label>
                                <input
                                    v-model="form.no_hp"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="081234567890"
                                />
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat
                                </label>
                                <textarea
                                    v-model="form.alamat"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Alamat lengkap guru..."
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Akademik -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center border-b pb-2">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Data Akademik
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mata Pelajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mata Pelajaran yang Diampu
                                </label>
                                <input
                                    v-model="form.mata_pelajaran"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Matematika, Fisika, dll"
                                />
                                <p class="mt-1 text-xs text-gray-500">Bisa lebih dari satu, pisahkan dengan koma</p>
                            </div>

                            <!-- Pendidikan Terakhir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pendidikan Terakhir
                                </label>
                                <select
                                    v-model="form.pendidikan_terakhir"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                >
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <Link
                            href="/teachers"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center"
                        >
                            <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isSubmitting ? 'Menyimpan...' : 'Simpan Data Guru' }}
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

const isSubmitting = ref(false);

const form = reactive({
    nik: '',
    nama: '',
    email: '',
    no_hp: '',
    mata_pelajaran: '',
    alamat: '',
    pendidikan_terakhir: '',
});

const submitForm = () => {
    isSubmitting.value = true;
    
    router.post('/teachers', form, {
        onSuccess: () => {
            alert('Data guru berhasil ditambahkan!');
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
