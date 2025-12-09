<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Siswa
                </h2>
                <Link :href="`/students/${student.id}`" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Detail
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Daftar Siswa', href: '/students' },
                { label: 'Edit Siswa' }
            ]" />

            <!-- Edit Form -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Form Edit Siswa</h3>
                    <p class="text-sm text-gray-600">Ubah data siswa sesuai kebutuhan</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <!-- Data Pribadi -->
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Pribadi
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIS -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    NIS <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nis"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nomor Induk Siswa"
                                />
                                <p v-if="errors.nis" class="mt-1 text-sm text-red-600">{{ errors.nis }}</p>
                            </div>

                            <!-- NISN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                                <input
                                    v-model="form.nisn"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nomor Induk Siswa Nasional"
                                />
                                <p v-if="errors.nisn" class="mt-1 text-sm text-red-600">{{ errors.nisn }}</p>
                            </div>

                            <!-- Nama -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="form.nama"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nama lengkap siswa"
                                />
                                <p v-if="errors.nama" class="mt-1 text-sm text-red-600">{{ errors.nama }}</p>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="form.jenis_kelamin"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <p v-if="errors.jenis_kelamin" class="mt-1 text-sm text-red-600">{{ errors.jenis_kelamin }}</p>
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                                <input
                                    v-model="form.tempat_lahir"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Kota/Kabupaten"
                                />
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                                <input
                                    v-model="form.tanggal_lahir"
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                />
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                                <textarea
                                    v-model="form.alamat"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Alamat lengkap siswa"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Akademik -->
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Data Akademik
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Tingkat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat</label>
                                <select
                                    v-model="selectedTingkat"
                                    @change="updateKelas"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                                    <option value="">Pilih Tingkat</option>
                                    <option value="10">Kelas 10 (X)</option>
                                    <option value="11">Kelas 11 (XI)</option>
                                    <option value="12">Kelas 12 (XII)</option>
                                </select>
                            </div>

                            <!-- Jurusan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                                <select
                                    v-model="selectedJurusan"
                                    @change="updateKelas"
                                    :disabled="!selectedTingkat"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                >
                                    <option value="">Pilih Jurusan</option>
                                    <option value="TPM">TPM - Teknik Pemesinan</option>
                                    <option value="TKR">TKR - Teknik Kendaraan Ringan</option>
                                    <option value="TL">TL - Teknik Logistik</option>
                                    <option value="DKV">DKV - Desain Komunikasi Visual</option>
                                    <option value="APL">APL - Analisis Pengujian Laboratorium</option>
                                    <option value="MPK">MPK - Manajemen Perkantoran</option>
                                </select>
                            </div>

                            <!-- Rombel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rombel</label>
                                <select
                                    v-model="selectedRombel"
                                    @change="updateKelas"
                                    :disabled="!selectedJurusan"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                >
                                    <option value="">Pilih Rombel</option>
                                    <option v-for="n in availableRombel" :key="n" :value="n">{{ n }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kelas Preview -->
                        <div v-if="form.kelas" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">Kelas yang dipilih:</span> 
                                <span class="font-bold text-blue-900">{{ form.kelas }}</span>
                            </p>
                        </div>
                        <p v-if="errors.kelas" class="mt-2 text-sm text-red-600">{{ errors.kelas }}</p>
                    </div>

                    <!-- Data Kontak -->
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Data Kontak
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- No HP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Siswa</label>
                                <input
                                    v-model="form.no_hp"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="08xxxxxxxxxx"
                                />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="email@example.com"
                                />
                                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                            </div>

                            <!-- Nama Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Wali/Orang Tua</label>
                                <input
                                    v-model="form.nama_wali"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nama orang tua/wali"
                                />
                            </div>

                            <!-- No HP Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Wali/Orang Tua</label>
                                <input
                                    v-model="form.no_hp_wali"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="08xxxxxxxxxx"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3">
                        <Link
                            :href="`/students/${student.id}`"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                        >
                            <svg v-if="isSubmitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props = defineProps({
    student: {
        type: Object,
        required: true
    }
});

const page = usePage();
const errors = computed(() => page.props.errors || {});

const isSubmitting = ref(false);
const selectedTingkat = ref('');
const selectedJurusan = ref('');
const selectedRombel = ref('');

const form = reactive({
    nis: props.student.nis || '',
    nisn: props.student.nisn || '',
    nama: props.student.nama || '',
    jenis_kelamin: props.student.jenis_kelamin || '',
    tempat_lahir: props.student.tempat_lahir || '',
    tanggal_lahir: props.student.tanggal_lahir || '',
    alamat: props.student.alamat || '',
    kelas: props.student.kelas || '',
    no_hp: props.student.no_hp || '',
    email: props.student.email || '',
    nama_wali: props.student.nama_wali || '',
    no_hp_wali: props.student.no_hp_wali || ''
});

// Parse existing kelas
onMounted(() => {
    if (props.student.kelas) {
        const parts = props.student.kelas.split(' ');
        if (parts.length === 3) {
            selectedTingkat.value = parts[0];
            selectedJurusan.value = parts[1];
            selectedRombel.value = parts[2];
        }
    }
});

const availableRombel = computed(() => {
    if (!selectedJurusan.value) return [];
    const jurusan = selectedJurusan.value;
    const tingkat = selectedTingkat.value;
    if (jurusan === 'TPM') {
        return tingkat === '12' ? [1, 2, 3, 4, 5] : [1, 2, 3, 4];
    }
    if (jurusan === 'MPK') {
        return [1, 2];
    }
    return [1];
});

const updateKelas = () => {
    if (selectedTingkat.value && selectedJurusan.value && selectedRombel.value) {
        form.kelas = `${selectedTingkat.value} ${selectedJurusan.value} ${selectedRombel.value}`;
    } else {
        form.kelas = '';
    }
};

const submitForm = () => {
    isSubmitting.value = true;
    router.put(`/students/${props.student.id}`, form, {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>
