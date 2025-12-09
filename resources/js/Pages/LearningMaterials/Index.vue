<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Materi & Modul Pembelajaran
                </h2>
                <Link 
                    v-if="$page.props.auth.user.role !== 'student'"
                    href="/learning-media/create" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Upload Materi
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[{ label: 'Materi & Modul' }]" />

            <!-- Stats Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Materi</p>
                        <p class="text-4xl font-bold mt-2">{{ materials?.length || 0 }}</p>
                        <p class="text-blue-100 text-sm mt-1">File tersedia</p>
                    </div>
                    <div>
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Materi</label>
                        <input 
                            v-model="filters.search"
                            type="text" 
                            placeholder="Cari judul atau deskripsi..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Materi</label>
                        <select 
                            v-model="filters.type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                        <select 
                            v-model="filters.course"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">Semua Mata Pelajaran</option>
                            <option v-for="course in uniqueCourses" :key="course" :value="course">
                                {{ course }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Materials List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Materi</h3>
                </div>

                <div v-if="!filteredMaterials || filteredMaterials.length === 0" class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum ada materi</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai upload materi pembelajaran dengan klik tombol di atas</p>
                </div>

                <div v-else class="divide-y divide-gray-200">
                    <div v-for="material in filteredMaterials" :key="material.id" class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span :class="getTypeClass(material.type)" class="px-3 py-1 text-xs font-semibold rounded-full">
                                        {{ getTypeLabel(material.type) }}
                                    </span>
                                    <span v-if="material.visibility === 'private'" class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Private
                                    </span>
                                </div>
                                
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                    {{ material.title }}
                                </h4>
                                
                                <p v-if="material.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ material.description }}
                                </p>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                        </svg>
                                        {{ material.course?.nama_mapel || material.course }}
                                    </span>
                                    
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ material.uploader?.name || 'Unknown' }}
                                    </span>
                                    
                                    <span v-if="material.download_count > 0" class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ material.download_count }} downloads
                                    </span>
                                </div>

                                <div v-if="material.kelas && material.kelas.length > 0" class="mt-2">
                                    <span class="text-xs text-gray-500 mr-2">Kelas:</span>
                                    <span v-for="kelas in material.kelas" :key="kelas" class="inline-block px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded mr-1">
                                        {{ kelas }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2 ml-4">
                                <Link :href="`/learning-media/${material.id}`" class="px-3 py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 text-center">
                                    Detail
                                </Link>
                                <Link 
                                    v-if="$page.props.auth.user.role !== 'student'"
                                    :href="`/learning-media/${material.id}/edit`" 
                                    class="px-3 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700 text-center"
                                >
                                    Edit
                                </Link>
                                <a v-if="material.file_path" :href="`/learning-media/${material.id}/download`" class="px-3 py-2 text-sm bg-purple-600 text-white rounded hover:bg-purple-700 text-center">
                                    Download
                                </a>
                                <button 
                                    v-if="$page.props.auth.user.role !== 'student'"
                                    @click="deleteMaterial(material)" 
                                    class="px-3 py-2 text-sm bg-red-600 text-white rounded hover:bg-red-700"
                                >
                                    Hapus
                                </button>
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
            title="Hapus Materi?"
            :message="deleteMessage"
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
    materials: {
        type: Array,
        required: true
    }
});

const filters = ref({
    search: '',
    type: '',
    course: ''
});

const showDeleteConfirm = ref(false);
const materialToDelete = ref(null);
const deleteMessage = ref('');

const filteredMaterials = computed(() => {
    return props.materials.filter(material => {
        const matchSearch = !filters.value.search || 
            material.title.toLowerCase().includes(filters.value.search.toLowerCase()) ||
            (material.description && material.description.toLowerCase().includes(filters.value.search.toLowerCase()));
        
        const matchType = !filters.value.type || material.type === filters.value.type;
        
        const courseName = material.course?.nama_mapel || material.course;
        const matchCourse = !filters.value.course || courseName === filters.value.course;
        
        return matchSearch && matchType && matchCourse;
    });
});

const uniqueCourses = computed(() => {
    const courses = props.materials.map(m => m.course?.nama_mapel || m.course);
    return [...new Set(courses)].filter(Boolean).sort();
});

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

const deleteMaterial = (material) => {
    materialToDelete.value = material;
    deleteMessage.value = `Apakah Anda yakin ingin menghapus materi "${material.title}"? Tindakan ini tidak dapat dibatalkan.`;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    router.delete(`/learning-media/${materialToDelete.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
            materialToDelete.value = null;
        }
    });
};
</script>
