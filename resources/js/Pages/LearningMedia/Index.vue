<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Media Pembelajaran
                </h2>
                <Link
                    href="/learning-media/create"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm sm:text-base whitespace-nowrap"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden sm:inline">Upload Media Baru</span>
                    <span class="sm:hidden">Upload Media</span>
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Media Pembelajaran' }
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

                <!-- Error Message -->
                <div v-if="$page.props.flash?.error" class="p-4 bg-red-50 border-l-4 border-red-500">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $page.props.flash.error }}</p>
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
                                placeholder="Cari media pembelajaran..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                        </div>
                        <div class="w-full sm:w-48">
                            <select
                                v-model="filterType"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Semua Tipe</option>
                                <option value="modul">Modul</option>
                                <option value="video">Video</option>
                                <option value="audio">Audio</option>
                                <option value="dokumen">Dokumen</option>
                                <option value="presentasi">Presentasi</option>
                                <option value="link">Link</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="w-full sm:w-48">
                            <select
                                v-model="filterCourse"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                <option value="">Semua Mata Pelajaran</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">
                                    {{ course.nama_mapel }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Grid Layout for Media Cards -->
                    <div v-if="filteredMedia.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="item in filteredMedia" :key="item.id" class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <!-- Card Header -->
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ item.title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ item.course?.nama_mapel || 'N/A' }}</p>
                                    </div>
                                    <span :class="getTypeClass(item.type)" class="ml-2 px-2 py-1 text-xs font-semibold rounded-full whitespace-nowrap">
                                        {{ getTypeLabel(item.type) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-4 space-y-3">
                                <p v-if="item.description" class="text-sm text-gray-700 line-clamp-3">{{ item.description }}</p>
                                
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ item.uploader?.name || 'Unknown' }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        {{ item.download_count }} download
                                    </div>
                                </div>

                                <div v-if="item.file_name" class="text-xs text-gray-500">
                                    <span class="font-medium">File:</span> {{ item.file_name }}
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                                <a 
                                    v-if="item.file_path"
                                    :href="`/learning-media/${item.id}/download`"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download
                                </a>
                                <a 
                                    v-else-if="item.url"
                                    :href="item.url"
                                    target="_blank"
                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Buka Link
                                </a>

                                <div class="flex items-center space-x-2">
                                    <Link
                                        :href="`/learning-media/${item.id}`"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded"
                                        title="Detail"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </Link>
                                    
                                    <template v-if="canEdit(item)">
                                        <Link
                                            :href="`/learning-media/${item.id}/edit`"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded"
                                            title="Edit"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </Link>
                                        <button
                                            @click="deleteMedia(item.id)"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded"
                                            title="Hapus"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else class="text-center py-12">
                        <svg class="mx-auto w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500">Tidak ada media pembelajaran yang ditemukan</p>
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
    media: Object,
    courses: Array,
    filters: Object
});

const search = ref('');
const filterType = ref(props.filters?.type || '');
const filterCourse = ref(props.filters?.course_id || '');

const filteredMedia = computed(() => {
    let filtered = props.media?.data || [];

    if (search.value) {
        filtered = filtered.filter(item =>
            item.title.toLowerCase().includes(search.value.toLowerCase()) ||
            item.description?.toLowerCase().includes(search.value.toLowerCase())
        );
    }

    if (filterType.value) {
        filtered = filtered.filter(item => item.type === filterType.value);
    }

    if (filterCourse.value) {
        filtered = filtered.filter(item => item.course_id == filterCourse.value);
    }

    return filtered;
});

const getTypeClass = (type) => {
    const classes = {
        modul: 'bg-purple-100 text-purple-800',
        video: 'bg-red-100 text-red-800',
        audio: 'bg-yellow-100 text-yellow-800',
        dokumen: 'bg-blue-100 text-blue-800',
        presentasi: 'bg-green-100 text-green-800',
        link: 'bg-indigo-100 text-indigo-800',
        lainnya: 'bg-gray-100 text-gray-800'
    };
    return classes[type] || classes.lainnya;
};

const getTypeLabel = (type) => {
    const labels = {
        modul: 'Modul',
        video: 'Video',
        audio: 'Audio',
        dokumen: 'Dokumen',
        presentasi: 'Presentasi',
        link: 'Link',
        lainnya: 'Lainnya'
    };
    return labels[type] || type;
};

const canEdit = (item) => {
    const user = router.page.props.auth.user;
    return user.role === 'admin' || item.uploaded_by === user.id;
};

const deleteMedia = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus media pembelajaran ini?')) {
        router.delete(`/learning-media/${id}`);
    }
};
</script>
