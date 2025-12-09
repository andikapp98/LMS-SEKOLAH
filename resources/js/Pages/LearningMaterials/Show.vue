<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Materi
            </h2>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Materi & Modul', href: '/learning-media' },
                { label: 'Detail' }
            ]" />

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span :class="getTypeClass(material.type)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                    {{ getTypeLabel(material.type) }}
                                </span>
                                <span v-if="material.visibility === 'private'" class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                    <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Private
                                </span>
                            </div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ material.title }}</h1>
                        </div>
                        
                        <div class="flex gap-2">
                            <template v-if="$page.props.auth.user.role !== 'student'">
                                <Link
                                    :href="`/learning-media/${material.id}/edit`"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    Edit
                                </Link>
                                <button
                                    @click="deleteMaterial"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                >
                                    Hapus
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Description -->
                    <div v-if="material.description" class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi</h3>
                        <p class="text-gray-600 whitespace-pre-line">{{ material.description }}</p>
                    </div>

                    <!-- PDF Preview -->
                    <div v-if="isPdf && fileUrl" class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Preview Dokumen</h3>
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-gray-100 p-2 h-[600px]">
                            <iframe 
                                :src="fileUrl" 
                                class="w-full h-full rounded bg-white" 
                                frameborder="0"
                            ></iframe>
                        </div>
                        <div class="mt-2 text-right">
                            <a :href="fileUrl" target="_blank" class="text-sm text-blue-600 hover:text-blue-800">
                                Buka di tab baru &nearr;
                            </a>
                        </div>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- ... unchanged ... -->
                    </div>
                    
                    <!-- (Existing Info Grid Content skipped for brevity, make sure to keep existing structure below) -->

                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Mata Pelajaran</span>
                            </div>
                            <p class="text-gray-900">{{ material.course?.nama_mapel || material.course }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Diupload Oleh</span>
                            </div>
                            <p class="text-gray-900">{{ material.uploader?.name || 'Unknown' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ formatDate(material.created_at) }}</p>
                        </div>

                        <div v-if="material.file_name" class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">File Info</span>
                            </div>
                            <p class="text-gray-900 text-sm truncate">{{ material.file_name }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ formatFileSize(material.file_size) }}
                                <span v-if="material.file_type" class="ml-2">{{ material.file_type }}</span>
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700">Statistik</span>
                            </div>
                            <p class="text-gray-900">{{ material.download_count }} kali didownload</p>
                        </div>
                    </div>

                    <!-- Target Kelas -->
                    <div v-if="material.kelas && material.kelas.length > 0" class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Target Kelas</h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="kelas in material.kelas"
                                :key="kelas"
                                class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full"
                            >
                                {{ kelas }}
                            </span>
                        </div>
                    </div>

                    <!-- URL Link -->
                    <div v-if="material.url" class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Link</h3>
                        <a
                            :href="material.url"
                            target="_blank"
                            class="inline-flex items-center text-blue-600 hover:text-blue-800"
                        >
                            {{ material.url }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Download Button -->
                    <div v-if="material.file_path" class="flex gap-3">
                        <a
                            :href="`/learning-media/${material.id}/download`"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Download File
                        </a>
                        <Link
                            href="/learning-media"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Kembali
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Dialog -->
        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Materi?"
            :message="`Apakah Anda yakin ingin menghapus materi &quot;${material.title}&quot;? Tindakan ini tidak dapat dibatalkan.`"
            confirm-text="Ya, Hapus"
            cancel-text="Batal"
            @confirm="confirmDelete"
            @cancel="showDeleteConfirm = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import ConfirmDialog from '@/Components/ConfirmDialog.vue';

const props = defineProps({
    material: {
        type: Object,
        required: true
    }
});

const fileUrl = computed(() => {
    if (!props.material.file_path) return null;
    return `/storage/${props.material.file_path}`;
});

const isPdf = computed(() => {
    if (!props.material.file_name) return false;
    // Check by file type or extension
    const type = props.material.file_type || '';
    const name = props.material.file_name.toLowerCase();
    
    return type === 'application/pdf' || name.endsWith('.pdf');
});

const showDeleteConfirm = ref(false);

const getTypeClass = (type) => {
    const classes = {
        modul: 'bg-blue-100 text-blue-800',
        video: 'bg-red-100 text-red-800',
        audio: 'bg-purple-100 text-purple-800',
        dokumen: 'bg-green-100 text-green-800',
        presentasi: 'bg-yellow-100 text-yellow-800',
        link: 'bg-indigo-100 text-indigo-800',
        lainnya: 'bg-gray-100 text-gray-800'
    };
    return classes[type] || classes.lainnya;
};

const getTypeLabel = (type) => {
    const labels = {
        modul: '📚 Modul',
        video: '🎥 Video',
        audio: '🎵 Audio',
        dokumen: '📄 Dokumen',
        presentasi: '📊 Presentasi',
        link: '🔗 Link',
        lainnya: '📦 Lainnya'
    };
    return labels[type] || type;
};

const formatFileSize = (bytes) => {
    if (!bytes) return '-';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const deleteMaterial = () => {
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/learning-media/${props.material.id}`, {
        onSuccess: () => {
            // Redirect handled by controller
        }
    });
};
</script>
