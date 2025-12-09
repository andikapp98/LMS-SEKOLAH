<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Siswa
                </h2>
                <div class="flex space-x-3">
                    <button
                        @click="deleteAllStudents"
                        v-if="(students?.total || 0) > 0"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Semua Data
                    </button>
                    <Link href="/students/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Siswa
                    </Link>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="breadcrumbItems" />

            <!-- Filter & Search -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form @submit.prevent="applyFilters">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                            <input
                                v-model="form.search"
                                type="text"
                                placeholder="Cari nama, NIS, atau NISN..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat</label>
                            <select
                                v-model="form.tingkat"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Semua Tingkat</option>
                                <option value="10">Kelas 10</option>
                                <option value="11">Kelas 11</option>
                                <option value="12">Kelas 12</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                            <select
                                v-model="form.jurusan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Semua Jurusan</option>
                                <option value="TPM">TPM - Teknik Pemesinan</option>
                                <option value="TKR">TKR - Teknik Kendaraan Ringan</option>
                                <option value="TL">TL - Teknik Logistik</option>
                                <option value="DKV">DKV - Desain Komunikasi Visual</option>
                                <option value="APL">APL - Analisis Pengujian Lab</option>
                                <option value="MPK">MPK - Manajemen Perkantoran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rombel</label>
                            <select
                                v-model="form.rombel"
                                :disabled="!isRombelFilterActive"
                                :class="[
                                    'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent',
                                    !isRombelFilterActive ? 'bg-gray-100 cursor-not-allowed opacity-60' : ''
                                ]"
                            >
                                <option value="">{{ isRombelFilterActive ? 'Semua Rombel' : 'Tidak tersedia' }}</option>
                                <option v-for="n in availableRombel" :key="n" :value="n">
                                    {{ getRombelLabel(n) }}
                                </option>
                            </select>
                            <p v-if="!isRombelFilterActive && form.jurusan" class="mt-1 text-xs text-gray-500">
                                {{ form.jurusan }} hanya memiliki 1 rombel
                            </p>
                            <p v-else-if="!form.jurusan" class="mt-1 text-xs text-gray-500">
                                Pilih jurusan terlebih dahulu
                            </p>
                        </div>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="mt-4 flex items-center justify-between">
                        <div v-if="hasActiveFilters" class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Filter aktif</span>
                            <button
                                type="button"
                                @click="resetFilters"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                            >
                                Reset Semua
                            </button>
                        </div>
                        <div v-else></div>
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Students List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(student, index) in students.data" :key="student.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ getRowNumber(index) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ student.nis }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ student.nama }}</div>
                                    <div class="text-xs text-gray-500">NISN: {{ student.nisn || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="student.kelas" class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ student.kelas }}
                                    </span>
                                    <span v-else class="text-sm text-gray-500">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ student.email || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ student.no_hp || '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <!-- Detail Button - Icon Only -->
                                        <Link 
                                            :href="`/students/${student.id}`"
                                            class="group relative inline-flex items-center justify-center p-2 rounded-lg font-medium text-sm overflow-hidden transition-all duration-200 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-sm hover:shadow-md hover:scale-110 transform"
                                            title="Lihat Detail"
                                        >
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </Link>

                                        <!-- Edit Button - Icon Only -->
                                        <Link 
                                            :href="`/students/${student.id}/edit`"
                                            class="group relative inline-flex items-center justify-center p-2 rounded-lg font-medium text-sm overflow-hidden transition-all duration-200 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white shadow-sm hover:shadow-md hover:scale-110 transform"
                                            title="Edit Data"
                                        >
                                            <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </Link>

                                        <!-- Delete Button - Icon Only -->
                                        <button 
                                            @click="deleteStudent(student)"
                                            class="group relative inline-flex items-center justify-center p-2 rounded-lg font-medium text-sm overflow-hidden transition-all duration-200 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white shadow-sm hover:shadow-md hover:scale-110 transform"
                                            title="Hapus Data"
                                        >
                                            <svg class="w-4 h-4 transition-transform group-hover:scale-110 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!students.data || students.data.length === 0">
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="font-medium">Tidak ada data siswa</p>
                                    <p class="text-sm mt-1">Cobalah mengubah filter atau tambah data baru</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <Pagination
                    v-if="students.links && students.links.length > 0"
                    :links="students.links"
                    :current-page="students.current_page"
                    :last-page="students.last_page"
                    :from="students.from"
                    :to="students.to"
                    :total="students.total"
                />
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Siswa?"
            :message="deleteMessage"
            confirm-text="Ya, Hapus"
            cancel-text="Batal"
            @confirm="confirmDeleteStudent"
            @cancel="cancelDelete"
        />

        <!-- Delete All Confirmation Modal -->
        <ConfirmDialog
            :show="showDeleteAllConfirm"
            type="danger"
            title="Hapus Semua Data Siswa?"
            :message="deleteAllMessage"
            confirm-text="Ya, Hapus Semua"
            cancel-text="Batal"
            @confirm="confirmDeleteAllStudents"
            @cancel="showDeleteAllConfirm = false"
        />

        <!-- Notification -->
        <Notification
            :show="showNotification"
            :type="notificationType"
            :title="notificationTitle"
            :message="notificationMessage"
            @close="showNotification = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';
import Notification from '@/Components/Notification.vue';

const props = defineProps({
    students: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const form = reactive({
    search: props.filters.search || '',
    tingkat: props.filters.tingkat || '',
    jurusan: props.filters.jurusan || '',
    rombel: props.filters.rombel || ''
});

// Modal states
const showDeleteConfirm = ref(false);
const showDeleteAllConfirm = ref(false);
const studentToDelete = ref(null);
const deleteMessage = ref('');
const deleteAllMessage = ref('');

// Notification states
const showNotification = ref(false);
const notificationType = ref('info');
const notificationTitle = ref('');
const notificationMessage = ref('');

const breadcrumbItems = [
    { label: 'Daftar Siswa' }
];

const availableRombel = computed(() => {
    if (!form.jurusan && !form.tingkat) return [1, 2, 3, 4, 5];
    
    const jurusan = form.jurusan;
    const tingkat = form.tingkat;
    
    if (jurusan === 'TPM') {
        return tingkat === '12' ? [1, 2, 3, 4, 5] : [1, 2, 3, 4];
    }
    if (jurusan === 'MPK') {
        return [1, 2];
    }
    return [1];
});

// Rombel filter is active only if jurusan has more than 1 rombel
const isRombelFilterActive = computed(() => {
    if (!form.jurusan) return false; // No jurusan selected
    return availableRombel.value.length > 1; // Active if more than 1 rombel
});


const hasActiveFilters = computed(() => {
    return form.search || form.tingkat || form.jurusan || form.rombel;
});

// Generate label for rombel dropdown (e.g., "10 TPM 1", "11 TPM 2")
const getRombelLabel = (rombelNumber) => {
    const tingkat = form.tingkat || 'X'; // Default to X if not selected
    const jurusan = form.jurusan || '';
    
    // Convert tingkat to Roman if not numeric
    const tingkatLabel = tingkat === '10' ? 'X' : 
                        tingkat === '11' ? 'XI' : 
                        tingkat === '12' ? 'XII' : tingkat;
    
    if (!jurusan) {
        return `Rombel ${rombelNumber}`;
    }
    
    return `${tingkatLabel} ${jurusan} ${rombelNumber}`;
};

const applyFilters = () => {
    router.get('/students', {
        search: form.search || undefined,
        tingkat: form.tingkat || undefined,
        jurusan: form.jurusan || undefined,
        rombel: form.rombel || undefined
    }, {
        preserveState: true,
        preserveScroll: true
    });
};

const resetFilters = () => {
    form.search = '';
    form.tingkat = '';
    form.jurusan = '';
    form.rombel = '';
    router.get('/students', {}, {
        preserveState: true,
        preserveScroll: true
    });
};

const getRowNumber = (index) => {
    const currentPage = props.students?.current_page || 1;
    const perPage = props.students?.per_page || 20;
    return (currentPage - 1) * perPage + index + 1;
};

const deleteStudent = (student) => {
    studentToDelete.value = student;
    deleteMessage.value = `Apakah Anda yakin ingin menghapus siswa "${student.nama}" (NIS: ${student.nis})? Tindakan ini tidak dapat dibatalkan.`;
    showDeleteConfirm.value = true;
};

const confirmDeleteStudent = () => {
    showDeleteConfirm.value = false;
    
    router.delete(`/students/${studentToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showNotification.value = true;
            notificationType.value = 'success';
            notificationTitle.value = 'Berhasil!';
            notificationMessage.value = `Siswa "${studentToDelete.value.nama}" berhasil dihapus`;
            studentToDelete.value = null;
        },
        onError: (error) => {
            showNotification.value = true;
            notificationType.value = 'error';
            notificationTitle.value = 'Gagal!';
            notificationMessage.value = error.message || 'Gagal menghapus siswa';
        }
    });
};

const cancelDelete = () => {
    showDeleteConfirm.value = false;
    studentToDelete.value = null;
};

const deleteAllStudents = () => {
    const totalStudents = props.students?.total || 0;
    
    if (totalStudents === 0) {
        showNotification.value = true;
        notificationType.value = 'warning';
        notificationTitle.value = 'Peringatan';
        notificationMessage.value = 'Tidak ada data siswa untuk dihapus';
        return;
    }
    
    deleteAllMessage.value = `Anda akan menghapus SEMUA data siswa (${totalStudents} siswa)! Tindakan ini TIDAK DAPAT dibatalkan. Apakah Anda yakin?`;
    showDeleteAllConfirm.value = true;
};

const confirmDeleteAllStudents = () => {
    showDeleteAllConfirm.value = false;
    
    router.delete('/students/clear', {
        preserveScroll: false,
        onSuccess: () => {
            showNotification.value = true;
            notificationType.value = 'success';
            notificationTitle.value = 'Berhasil!';
            notificationMessage.value = 'Semua data siswa berhasil dihapus';
        },
        onError: (error) => {
            showNotification.value = true;
            notificationType.value = 'error';
            notificationTitle.value = 'Error!';
            notificationMessage.value = error.message || 'Gagal menghapus data siswa';
        }
    });
};

// Auto-apply filters on input (debounced for search)
let searchTimeout;
watch(() => form.search, (newValue) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
});

// Watch jurusan to clear rombel if it becomes inactive
watch(() => form.jurusan, (newValue, oldValue) => {
    // Clear rombel if filter becomes inactive
    if (!isRombelFilterActive.value) {
        form.rombel = '';
    }
    applyFilters();
});

watch([() => form.tingkat, () => form.rombel], () => {
    applyFilters();
});
</script>
