<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Daftar Guru
                    </h2>
                    <p v-if="selectedIds.length > 0" class="text-sm text-blue-600 mt-1 font-medium">
                        ✓ {{ selectedIds.length }} guru dipilih
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button
                        v-if="selectedIds.length > 0"
                        @click="bulkDeleteTeachers"
                        class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors shadow-sm"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus {{ selectedIds.length }} Guru
                    </button>
                    <button
                        @click="deleteAllTeachers"
                        v-if="teachers?.total > 0"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Semua Data
                    </button>
                    <Link :href="route('teachers.upload')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Guru
                    </Link>
                    <Link :href="route('teachers.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Guru
                    </Link>
                </div>
            </div>
        </template>

        <div class="px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Guru' }
            ]" />

            <!-- Alert Messages -->
            <div v-if="$page.props.flash.success" class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div v-if="$page.props.flash.error" class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Guru</p>
                        <p class="text-4xl font-bold mt-2">{{ teachers?.total || 0 }}</p>
                        <p class="text-blue-100 text-sm mt-1">Terdaftar di sistem</p>
                    </div>
                    <div>
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Teachers List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Data Guru</h3>
                </div>

                <!-- Search and Filters -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Search Input -->
                        <div class="md:col-span-2">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input
                                    v-model="searchQuery"
                                    @input="performSearch"
                                    type="text"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Cari nama, NIK, email, HP, atau mata pelajaran..."
                                />
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select
                                v-model="statusFilter"
                                @change="applyFilters"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Active Filters Display & Clear -->
                    <div v-if="hasActiveFilters" class="mt-3 flex items-center justify-between">
                        <div class="flex flex-wrap gap-2">
                            <span v-if="searchQuery" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                "{{ searchQuery }}"
                            </span>
                            <span v-if="statusFilter" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                Status: {{ statusFilter }}
                            </span>
                        </div>
                        <button
                            @click="clearFilters"
                            class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Hapus Filter
                        </button>
                    </div>
                </div>

                <div v-if="!teachers.data || teachers.data.length === 0" class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum ada data guru</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai tambahkan data guru dengan klik tombol di atas</p>
                    <div class="mt-6 flex justify-center gap-3">
                        <Link :href="route('teachers.create')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Guru Manual
                        </Link>
                        <Link :href="route('teachers.upload')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload dari Excel
                        </Link>
                    </div>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-left">
                                    <input
                                        type="checkbox"
                                        :checked="isAllSelected"
                                        @change="toggleSelectAll"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                        title="Pilih semua"
                                    />
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    Email
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                    No HP
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mata Pelajaran
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                    Status
                                </th>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(teacher, index) in teachers.data" :key="teacher.id" class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 py-4">
                                    <input
                                        type="checkbox"
                                        :value="teacher.id"
                                        v-model="selectedIds"
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                    />
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ teachers.from + index }}
                                </td>
                                <td class="px-3 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ teacher.nama }}</div>
                                    <div class="text-xs text-gray-500">{{ teacher.kode_guru ? 'Kode: ' + teacher.kode_guru : (teacher.nik || 'NIK: -') }}</div>
                                </td>
                                <td class="px-3 py-4 hidden lg:table-cell">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ teacher.email || '-' }}</div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap hidden md:table-cell">
                                    <div class="text-sm text-gray-900">{{ teacher.no_hp || '-' }}</div>
                                </td>
                                <td class="px-3 py-4">
                                    <div class="text-sm text-gray-900">{{ teacher.mata_pelajaran || '-' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ teacher.pendidikan_terakhir || '-' }}</div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap hidden md:table-cell">
                                    <span :class="[
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        teacher.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                    ]">
                                        {{ teacher.status }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <Link 
                                            :href="`/teachers/${teacher.id}`" 
                                            class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition-colors"
                                            title="Detail"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </Link>
                                        <Link 
                                            :href="`/teachers/${teacher.id}/edit`" 
                                            class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded-lg transition-colors"
                                            title="Edit"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </Link>
                                        <button 
                                            @click="deleteTeacher(teacher)" 
                                            class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition-colors"
                                            title="Hapus"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div v-if="teachers?.last_page > 1" class="px-6 py-4 border-t border-gray-200">
                        <Pagination 
                            :links="teachers.links"
                            :currentPage="teachers.current_page"
                            :lastPage="teachers.last_page"
                            :from="teachers.from"
                            :to="teachers.to"
                            :total="teachers.total"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Dialog -->
        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Guru?"
            :message="deleteMessage"
            confirm-text="Ya, Hapus"
            cancel-text="Batal"
            @confirm="confirmDelete"
            @cancel="showDeleteConfirm = false"
        />

        <!-- Bulk Delete Confirmation Dialog -->
        <ConfirmDialog
            :show="showBulkDeleteConfirm"
            type="danger"
            title="Hapus Beberapa Guru?"
            :message="bulkDeleteMessage"
            confirm-text="Ya, Hapus Semua"
            cancel-text="Batal"
            @confirm="confirmBulkDelete"
            @cancel="showBulkDeleteConfirm = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'
import Pagination from '@/Components/Pagination.vue'
import ConfirmDialog from '@/Components/ConfirmDialog.vue'

const props = defineProps({
    teachers: Object,
    filters: Object
})

// Selection state
const selectedIds = ref([]);

// Delete states
const showDeleteConfirm = ref(false);
const teacherToDelete = ref(null);
const deleteMessage = ref('');

// Bulk delete states
const showBulkDeleteConfirm = ref(false);
const bulkDeleteMessage = ref('');

// Search and filter states
const searchQuery = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
let searchTimeout = null;

// Computed property for select all checkbox
const isAllSelected = computed(() => {
    return props.teachers.data?.length > 0 && 
           selectedIds.value.length === props.teachers.data.length;
});

// Computed property to check if any filters are active
const hasActiveFilters = computed(() => {
    return searchQuery.value || statusFilter.value;
});

// Toggle select all
const toggleSelectAll = () => {
    if (isAllSelected.value) {
        selectedIds.value = [];
    } else {
        selectedIds.value = props.teachers.data.map(t => t.id);
    }
};

// Debounced search function
const performSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500); // 500ms delay
};

// Apply filters function
const applyFilters = () => {
    router.get('/teachers', {
        search: searchQuery.value,
        status: statusFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Clear all filters
const clearFilters = () => {
    searchQuery.value = '';
    statusFilter.value = '';
    router.get('/teachers', {}, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Delete single teacher
const deleteTeacher = (teacher) => {
    teacherToDelete.value = teacher;
    deleteMessage.value = `Apakah Anda yakin ingin menghapus guru "${teacher.nama}"? Tindakan ini tidak dapat dibatalkan.`;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/teachers/${teacherToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            teacherToDelete.value = null;
            selectedIds.value = []; // Clear selection after delete
        }
    });
};

// Bulk delete teachers
const bulkDeleteTeachers = () => {
    if (selectedIds.value.length === 0) {
        return;
    }
    
    bulkDeleteMessage.value = `Apakah Anda yakin ingin menghapus ${selectedIds.value.length} guru yang dipilih? Tindakan ini tidak dapat dibatalkan.`;
    showBulkDeleteConfirm.value = true;
};

const confirmBulkDelete = () => {
    router.delete(route('teachers.bulk-delete'), {
        data: {
            ids: selectedIds.value
        },
        preserveScroll: true,
        onSuccess: () => {
            showBulkDeleteConfirm.value = false;
            selectedIds.value = [];
        }
    });
};

// Delete all teachers
const deleteAllTeachers = () => {
    if (!confirm('Apakah Anda yakin ingin menghapus SEMUA data guru? Tindakan ini tidak dapat dibatalkan!')) {
        return
    }
    
    if (!confirm('KONFIRMASI TERAKHIR: Semua data guru akan dihapus permanen. Lanjutkan?')) {
        return
    }
    
    router.delete(route('teachers.clear'), {
        preserveScroll: true,
        onSuccess: () => {
            selectedIds.value = [];
        }
    })
}
</script>
