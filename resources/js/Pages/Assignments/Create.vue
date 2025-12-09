<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Buat Tugas Baru
                </h2>
                <Link href="/assignments" class="text-sm text-gray-600 hover:text-gray-900">
                    ← Kembali ke Daftar Tugas
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Tugas', href: '/assignments' },
                { label: 'Buat Tugas Baru' }
            ]" />

            <div class="max-w-3xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form @submit.prevent="submit">
                        <!-- Title -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Tugas <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan judul tugas"
                                required
                            />
                            <div v-if="form.errors.title" class="text-red-600 text-sm mt-1">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Masukkan deskripsi tugas"
                            ></textarea>
                            <div v-if="form.errors.description" class="text-red-600 text-sm mt-1">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Course -->
                        <div class="mb-6">
                            <label for="course" class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="course"
                                v-model="form.course"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                                <option value="">Pilih Mata Pelajaran</option>
                                <option v-for="course in courses" :key="course.id" :value="course.nama_mapel">
                                    [{{ course.kode_mapel }}] {{ course.nama_mapel }}
                                    <span v-if="course.kelas"> - {{ course.kelas }}</span>
                                </option>
                            </select>
                            <p v-if="courses && courses.length === 0" class="text-xs text-red-500 mt-1">
                                Anda belum mengampu mata pelajaran apapun. Hubungi admin untuk assign mata pelajaran.
                            </p>
                            <div v-if="form.errors.course" class="text-red-600 text-sm mt-1">
                                {{ form.errors.course }}
                            </div>
                        </div>

                        <!-- Kelas (Mobile-Friendly Multiple Select) -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas Tujuan
                            </label>
                            
                            <!-- Desktop: Native multiselect -->
                            <select
                                v-if="!isMobile"
                                id="kelas"
                                v-model="form.kelas"
                                multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                style="min-height: 120px;"
                            >
                                <option v-for="kls in availableKelas" :key="kls" :value="kls">
                                    {{ kls }}
                                </option>
                            </select>
                            
                            <!-- Mobile: Custom tap-to-select -->
                            <div v-else class="border border-gray-300 rounded-md p-2 max-h-64 overflow-y-auto">
                                <div v-if="availableKelas && availableKelas.length > 0" class="space-y-1">
                                    <button
                                        v-for="kls in availableKelas"
                                        :key="kls"
                                        type="button"
                                        @click="toggleKelas(kls)"
                                        :class="[
                                            'w-full text-left px-3 py-2 rounded transition-colors',
                                            form.kelas.includes(kls)
                                                ? 'bg-blue-500 text-white font-medium'
                                                : 'bg-gray-50 text-gray-700 hover:bg-gray-100'
                                        ]"
                                    >
                                        <span v-if="form.kelas.includes(kls)" class="mr-2">✓</span>
                                        {{ kls }}
                                    </button>
                                </div>
                                <p v-else class="text-sm text-gray-500 text-center py-4">Belum ada data kelas</p>
                            </div>
                            
                            <!-- Selected classes display -->
                            <div v-if="form.kelas && form.kelas.length > 0" class="mt-2">
                                <p class="text-xs text-gray-600 mb-1">Kelas terpilih:</p>
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        v-for="(kls, idx) in form.kelas"
                                        :key="idx"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800"
                                    >
                                        {{ kls }}
                                        <button
                                            type="button"
                                            @click="removeKelas(kls)"
                                            class="ml-1 hover:text-blue-900"
                                        >
                                            ×
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <p v-else class="text-xs text-gray-500 mt-1">
                                {{ isMobile ? '📱 Tap kelas untuk memilih/membatalkan' : '💻 Tahan Ctrl (Windows) atau Cmd (Mac) lalu klik untuk memilih beberapa kelas' }}
                            </p>
                            
                            <div v-if="form.errors.kelas" class="text-red-600 text-sm mt-1">
                                {{ form.errors.kelas }}
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div class="mb-6">
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tenggat Waktu <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="due_date"
                                v-model="form.due_date"
                                type="date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            />
                            <div v-if="form.errors.due_date" class="text-red-600 text-sm mt-1">
                                {{ form.errors.due_date }}
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required
                            >
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                            <div v-if="form.errors.status" class="text-red-600 text-sm mt-1">
                                {{ form.errors.status }}
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-6">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload File (Opsional)
                            </label>
                            <div class="mt-1 flex items-center">
                                <input
                                    id="file"
                                    type="file"
                                    @change="handleFileUpload"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                                    class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100
                                        cursor-pointer"
                                />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR (Max: 10MB)
                            </p>
                            <div v-if="fileName" class="mt-2 text-sm text-green-600">
                                📎 {{ fileName }}
                            </div>
                            <div v-if="form.errors.file" class="text-red-600 text-sm mt-1">
                                {{ form.errors.file }}
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <Link
                                href="/assignments"
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                Batal
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 transition-colors"
                            >
                                <span v-if="form.processing">Menyimpan...</span>
                                <span v-else>Simpan Tugas</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const props = defineProps({
    courses: {
        type: Array,
        default: () => []
    },
    availableKelas: {
        type: Array,
        default: () => []
    }
});

const fileName = ref('');
const isMobile = ref(false);

// Detect mobile device
onMounted(() => {
    isMobile.value = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) 
        || window.innerWidth < 768;
});

const form = useForm({
    title: '',
    description: '',
    course: '',
    kelas: [],
    due_date: '',
    status: 'pending',
    file: null
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.file = file;
        fileName.value = file.name;
    }
};

const toggleKelas = (kls) => {
    const index = form.kelas.indexOf(kls);
    if (index > -1) {
        form.kelas.splice(index, 1);
    } else {
        form.kelas.push(kls);
    }
};

const removeKelas = (kls) => {
    const index = form.kelas.indexOf(kls);
    if (index > -1) {
        form.kelas.splice(index, 1);
    }
};

const submit = () => {
    form.post('/assignments', {
        onSuccess: () => {
            form.reset();
            fileName.value = '';
        }
    });
};
</script>
