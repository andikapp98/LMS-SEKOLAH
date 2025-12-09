<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Materi
            </h2>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Materi & Modul', href: '/learning-media' },
                { label: 'Edit' }
            ]" />

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Informasi Materi</h3>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Materi <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                        <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        ></textarea>
                    </div>

                    <!-- Course & Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.course_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Pilih Mata Pelajaran</option>
                                <option v-for="course in courses" :key="course.id" :value="course.id">
                                    {{ course.nama_mapel }}
                                </option>
                            </select>
                            <p v-if="errors.course_id" class="mt-1 text-sm text-red-600">{{ errors.course_id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Materi <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.type"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="modul">📚 Modul</option>
                                <option value="video">🎥 Video</option>
                                <option value="audio">🎵 Audio</option>
                                <option value="dokumen">📄 Dokumen</option>
                                <option value="presentasi">📊 Presentasi</option>
                                <option value="link">🔗 Link</option>
                                <option value="lainnya">📦 Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Target Kelas
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            <label v-for="kelas in availableKelas" :key="kelas" class="flex items-center">
                                <input
                                    type="checkbox"
                                    :value="kelas"
                                    v-model="form.kelas"
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-700">{{ kelas }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Current File Info -->
                    <div v-if="material.file_name && form.type !== 'link'" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">File Saat Ini</p>
                                    <p class="text-xs text-gray-600">{{ material.file_name }}</p>
                                </div>
                            </div>
                            <a
                                :href="`/learning-media/${material.id}/download`"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                            >
                                Download
                            </a>
                        </div>
                    </div>

                    <!-- URL (for link type) -->
                    <div v-if="form.type === 'link'">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            URL Link <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.url"
                            type="url"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="https://example.com/video"
                        />
                        <p v-if="errors.url" class="mt-1 text-sm text-red-600">{{ errors.url }}</p>
                    </div>

                    <!-- Replace File -->
                    <div v-else>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ganti File (Opsional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                            <input
                                type="file"
                                @change="handleFileChange"
                                ref="fileInput"
                                class="hidden"
                            />
                            <div v-if="!form.file" class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V8"/>
                                </svg>
                                <button
                                    type="button"
                                    @click="$refs.fileInput.click()"
                                    class="mt-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    Pilih File Baru
                                </button>
                                <p class="mt-2 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti file</p>
                            </div>
                            <div v-else class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ form.file.name }}</p>
                                        <p class="text-xs text-gray-500">{{ formatFileSize(form.file.size) }}</p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="removeFile"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Visibility -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Visibilitas <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    value="public"
                                    v-model="form.visibility"
                                    class="border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-700">
                                    <span class="font-medium">Public</span> - Semua siswa bisa akses
                                </span>
                            </label>
                            <label class="flex items-center">
                                <input
                                    type="radio"
                                    value="private"
                                    v-model="form.visibility"
                                    class="border-gray-300 text-blue-600 focus:ring-blue-500"
                                />
                                <span class="ml-2 text-sm text-gray-700">
                                    <span class="font-medium">Private</span> - Hanya yang dipilih
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t">
                        <Link href="/learning-media" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="processing">Menyimpan...</span>
                            <span v-else">Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
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
    material: Object,
    courses: Array,
    availableKelas: Array
});

const form = reactive({
    title: props.material.title,
    description: props.material.description || '',
    course_id: props.material.course_id,
    type: props.material.type,
    kelas: props.material.kelas || [],
    file: null,
    url: props.material.url || '',
    visibility: props.material.visibility
});

const errors = ref({});
const processing = ref(false);
const fileInput = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 50 * 1024 * 1024) {
            errors.value.file = 'File terlalu besar. Maksimal 50MB';
            return;
        }
        form.file = file;
        errors.value.file = null;
    }
};

const removeFile = () => {
    form.file = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const formatFileSize = (bytes) => {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const submit = () => {
    processing.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('title', form.title);
    formData.append('description', form.description);
    formData.append('course_id', form.course_id);
    formData.append('type', form.type);
    formData.append('visibility', form.visibility);
    
    if (form.kelas.length > 0) {
        form.kelas.forEach((kelas, index) => {
            formData.append(`kelas[${index}]`, kelas);
        });
    }

    if (form.type === 'link') {
        formData.append('url', form.url);
    } else if (form.file) {
        formData.append('file', form.file);
    }

    router.post(`/learning-media/${props.material.id}`, formData, {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by redirect
        },
        onError: (errs) => {
            errors.value = errs;
            processing.value = false;
        },
        onFinish: () => {
            processing.value = false;
        }
    });
};
</script>
