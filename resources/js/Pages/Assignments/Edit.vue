<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Tugas
                </h2>
                <Link :href="`/assignments/${assignment.id}`" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Detail
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Daftar Tugas', href: '/assignments' },
                { label: 'Edit Tugas' }
            ]" />

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Form Edit Tugas</h3>
                    <p class="text-sm text-gray-600">Ubah informasi tugas sesuai kebutuhan</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Tugas <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.title"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Masukkan judul tugas"
                            />
                            <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.course"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Contoh: Matematika, Bahasa Indonesia"
                            />
                            <p v-if="errors.course" class="mt-1 text-sm text-red-600">{{ errors.course }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Batas Waktu <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.due_date"
                                type="date"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            />
                            <p v-if="errors.due_date" class="mt-1 text-sm text-red-600">{{ errors.due_date }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.status"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            >
                                <option value="">Pilih Status</option>
                                <option value="pending">Menunggu</option>
                                <option value="in_progress">Sedang Dikerjakan</option>
                                <option value="completed">Selesai</option>
                            </select>
                            <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Deskripsi detail tugas (opsional)"
                            ></textarea>
                            <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File Lampiran</label>
                            
                            <!-- Current file info -->
                            <div v-if="assignment.file_path && !form.file" class="mb-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">File saat ini</p>
                                            <p class="text-xs text-gray-500">{{ getFileName(assignment.file_path) }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-500">Upload file baru untuk mengubah</span>
                                </div>
                            </div>

                            <!-- File input -->
                            <input
                                ref="fileInput"
                                type="file"
                                @change="handleFileChange"
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            />
                            <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Max: 10MB)</p>
                            <p v-if="errors.file" class="mt-1 text-sm text-red-600">{{ errors.file }}</p>
                            
                            <!-- New file preview -->
                            <div v-if="form.file" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-green-900">File baru dipilih</p>
                                            <p class="text-xs text-green-700">{{ form.file.name }} ({{ formatFileSize(form.file.size) }})</p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeFile"
                                        class="text-red-600 hover:text-red-800 text-sm"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <Link
                            :href="`/assignments/${assignment.id}`"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
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
import { ref, reactive, computed } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props = defineProps({
    assignment: {
        type: Object,
        required: true
    }
});

const page = usePage();
const errors = computed(() => page.props.errors || {});
const isSubmitting = ref(false);
const fileInput = ref(null);

const form = reactive({
    title: props.assignment.title || '',
    description: props.assignment.description || '',
    course: props.assignment.course || '',
    due_date: props.assignment.due_date || '',
    status: props.assignment.status || 'pending',
    file: null
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
    }
};

const removeFile = () => {
    form.file = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const getFileName = (filePath) => {
    if (!filePath) return '';
    const parts = filePath.split('/');
    return parts[parts.length - 1];
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const submitForm = () => {
    isSubmitting.value = true;
    
    const formData = new FormData();
    formData.append('title', form.title);
    formData.append('description', form.description || '');
    formData.append('course', form.course);
    formData.append('due_date', form.due_date);
    formData.append('status', form.status);
    
    if (form.file) {
        formData.append('file', form.file);
    }
    
    formData.append('_method', 'PUT');
    
    router.post(`/assignments/${props.assignment.id}`, formData, {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>
