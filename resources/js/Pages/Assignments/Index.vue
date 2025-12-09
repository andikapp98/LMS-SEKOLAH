<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Tugas
                </h2>
                <Link
                    v-if="$page.props.auth.user.role !== 'student'"
                    href="/assignments/create"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm sm:text-base whitespace-nowrap"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden sm:inline">Tambah Tugas Baru</span>
                    <span class="sm:hidden">Tambah Tugas</span>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Tugas' }
            ]" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Success Message -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-green-50 border-l-4 border-green-500">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ $page.props.flash.success }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Filter & Search -->
                <div class="mb-6 flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari tugas..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div class="w-full sm:w-48">
                        <select
                            v-model="filterStatus"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Judul
                                </th>
                                <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mata Pelajaran
                                </th>
                                <th class="hidden lg:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kelas
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tenggat
                                </th>
                                <th class="hidden sm:table-cell px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="hidden xl:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dibuat Oleh
                                </th>
                                <th class="px-3 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="assignment in filteredAssignments" :key="assignment.id" class="hover:bg-gray-50">
                                <td class="px-3 md:px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ assignment.title }}</div>
                                    <div v-if="assignment.description" class="text-xs text-gray-500 truncate max-w-xs mt-1">
                                        {{ assignment.description }}
                                    </div>
                                    <!-- Show on mobile only -->
                                    <div class="md:hidden mt-2 space-y-1">
                                        <div class="text-xs text-gray-600">
                                            <span class="font-medium">Mapel:</span> {{ assignment.course }}
                                        </div>
                                        <div v-if="assignment.kelas && assignment.kelas.length > 0" class="flex flex-wrap gap-1">
                                            <span v-for="(kls, idx) in assignment.kelas" :key="idx" class="px-2 py-0.5 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                {{ kls }}
                                            </span>
                                        </div>
                                        <div class="sm:hidden">
                                            <span :class="getStatusClass(assignment.status)" class="text-xs">
                                                {{ getStatusText(assignment.status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div v-if="assignment.file_path" class="mt-2">
                                        <a 
                                            :href="`/assignments/${assignment.id}/download`"
                                            class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800"
                                        >
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            File
                                        </a>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ assignment.course }}</div>
                                </td>
                                <td class="hidden lg:table-cell px-6 py-4">
                                    <div v-if="assignment.kelas && assignment.kelas.length > 0" class="flex flex-wrap gap-1">
                                        <span v-for="(kls, idx) in assignment.kelas" :key="idx" class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ kls }}
                                        </span>
                                    </div>
                                    <span v-else class="text-sm text-gray-400">Semua kelas</span>
                                </td>
                                <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs md:text-sm text-gray-900">{{ formatDate(assignment.due_date) }}</div>
                                </td>
                                <td class="hidden sm:table-cell px-3 md:px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusClass(assignment.status)">
                                        {{ getStatusText(assignment.status) }}
                                    </span>
                                </td>
                                <td class="hidden xl:table-cell px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ assignment.creator?.name || 'Unknown' }}</div>
                                </td>
                                <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <!-- Mobile: Icon buttons -->
                                        <div class="flex md:hidden space-x-1">
                                            <Link
                                                :href="`/assignments/${assignment.id}`"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded"
                                                title="Detail"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </Link>
                                            <template v-if="$page.props.auth.user.role !== 'student' && ($page.props.auth.user.role === 'admin' || assignment.created_by === $page.props.auth.user.id)">
                                                <Link
                                                    :href="`/assignments/${assignment.id}/edit`"
                                                    class="p-2 text-green-600 hover:bg-green-50 rounded"
                                                    title="Edit"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </Link>
                                                <button
                                                    @click="deleteAssignment(assignment.id)"
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded"
                                                    title="Hapus"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                        
                                        <!-- Desktop: Text buttons -->
                                        <div class="hidden md:flex space-x-2 text-sm font-medium">
                                            <Link
                                                :href="`/assignments/${assignment.id}`"
                                                class="text-blue-600 hover:text-blue-900"
                                            >
                                                Detail
                                            </Link>
                                            <template v-if="$page.props.auth.user.role !== 'student' && ($page.props.auth.user.role === 'admin' || assignment.created_by === $page.props.auth.user.id)">
                                                <Link
                                                    :href="`/assignments/${assignment.id}/edit`"
                                                    class="text-green-600 hover:text-green-900"
                                                >
                                                    Edit
                                                </Link>
                                                <button
                                                    @click="deleteAssignment(assignment.id)"
                                                    class="text-red-600 hover:text-red-900"
                                                >
                                                    Hapus
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredAssignments.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p>Tidak ada tugas yang ditemukan</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props = defineProps({
    assignments: Array
});

const search = ref('');
const filterStatus = ref('');

const filteredAssignments = computed(() => {
    let filtered = props.assignments || [];

    if (search.value) {
        filtered = filtered.filter(assignment =>
            assignment.title.toLowerCase().includes(search.value.toLowerCase()) ||
            assignment.course.toLowerCase().includes(search.value.toLowerCase())
        );
    }

    if (filterStatus.value) {
        filtered = filtered.filter(assignment => assignment.status === filterStatus.value);
    }

    return filtered;
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getStatusClass = (status) => {
    const classes = {
        pending: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800',
        in_progress: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800',
        completed: 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'
    };
    return classes[status] || classes.pending;
};

const getStatusText = (status) => {
    const texts = {
        pending: 'Pending',
        in_progress: 'In Progress',
        completed: 'Completed'
    };
    return texts[status] || status;
};

const deleteAssignment = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus tugas ini?')) {
        router.delete(`/assignments/${id}`);
    }
};
</script>
