<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Upload Data Mata Pelajaran
                </h2>
                <Link :href="route('courses.index')" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Daftar Mata Pelajaran
                </Link>
            </div>
        </template>

        <div class="space-y-4 sm:space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Mata Pelajaran', href: '/courses' },
                { label: 'Upload Data Mata Pelajaran' }
            ]" />

            <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 px-2 sm:px-0">
                <!-- Alert Messages -->
            <div v-if="$page.props.flash.success" class="bg-green-50 border-l-4 border-green-400 p-3 sm:p-4 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-green-800">{{ $page.props.flash.success }}</p>
                </div>
            </div>

            <div v-if="$page.props.flash.error" class="bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="ml-3 text-sm font-medium text-red-800">{{ $page.props.flash.error }}</p>
                </div>
            </div>

            <!-- Upload Card -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 sm:px-6 py-3 sm:py-4">
                    <h3 class="text-base sm:text-lg font-semibold text-white">Upload File Excel atau CSV</h3>
                    <p class="text-xs sm:text-sm text-blue-100 mt-1">Import data mata pelajaran dari file Excel (.xlsx, .xls) atau CSV</p>
                </div>

                <div class="p-4 sm:p-6">
                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-xs sm:text-sm font-semibold text-blue-900 mb-2">Format File:</h4>
                                <ul class="text-xs sm:text-sm text-blue-800 space-y-1 list-disc list-inside">
                                    <li>Baris pertama adalah header</li>
                                    <li><strong>Kode Mapel | Nama Mapel | Kelas | Jam Per Minggu | Deskripsi | Semester | Tahun Ajaran | Status | NIK/Kode Guru</strong></li>
                                    <li>Format file: <strong>.xls, .xlsx, atau .csv</strong></li>
                                    <li>Maksimal ukuran: 5 MB</li>
                                    <li>Data dengan Kode Mapel sama akan di-update</li>
                                    <li>NIK/Kode Guru optional - untuk assign guru pengampu</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Example Format - Hidden on mobile, shown on tablet+ -->
                    <div class="mb-4 sm:mb-6 hidden md:block">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Contoh Format Excel:</h4>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Mapel</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mapel</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelas</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Per Minggu</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semester</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIK/Kode Guru</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">MAT-001</td>
                                        <td class="px-4 py-3 text-gray-700">Matematika</td>
                                        <td class="px-4 py-3 text-gray-700">10 TPM 1</td>
                                        <td class="px-4 py-3 text-gray-700">4</td>
                                        <td class="px-4 py-3 text-gray-700">Matematika Dasar</td>
                                        <td class="px-4 py-3 text-gray-700">ganjil</td>
                                        <td class="px-4 py-3 text-gray-700">2025/2026</td>
                                        <td class="px-4 py-3 text-gray-700">aktif</td>
                                        <td class="px-4 py-3 text-gray-700">3524010101900001</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">BIN-001</td>
                                        <td class="px-4 py-3 text-gray-700">Bahasa Indonesia</td>
                                        <td class="px-4 py-3 text-gray-700">10 TPM 1</td>
                                        <td class="px-4 py-3 text-gray-700">3</td>
                                        <td class="px-4 py-3 text-gray-700"></td>
                                        <td class="px-4 py-3 text-gray-700">ganjil</td>
                                        <td class="px-4 py-3 text-gray-700">2025/2026</td>
                                        <td class="px-4 py-3 text-gray-700">aktif</td>
                                        <td class="px-4 py-3 text-gray-700">GURU-001</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <form @submit.prevent="submitUpload" class="space-y-4 sm:space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih File Excel <span class="text-red-500">*</span>
                            </label>
                            
                            <!-- File Drop Zone -->
                            <div 
                                @dragover.prevent="dragOver = true"
                                @dragleave.prevent="dragOver = false"
                                @drop.prevent="handleFileDrop"
                                :class="[
                                    'relative border-2 border-dashed rounded-lg p-6 sm:p-8 text-center transition-colors cursor-pointer',
                                    dragOver ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400 bg-gray-50'
                                ]"
                                @click="$refs.fileInput.click()"
                            >
                                <input
                                    ref="fileInput"
                                    type="file"
                                    accept=".xls,.xlsx,.csv"
                                    @change="handleFileSelect"
                                    class="hidden"
                                />
                                
                                <div class="space-y-2">
                                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <div class="text-xs sm:text-sm text-gray-600">
                                        <span class="font-semibold text-blue-600">Klik untuk upload</span>
                                        <span class="hidden sm:inline"> atau drag and drop</span>
                                    </div>
                                    <p class="text-xs text-gray-500">XLS, XLSX, atau CSV (Max. 5MB)</p>
                                </div>

                                <!-- Selected File Info -->
                                <div v-if="form.file" class="mt-4 p-3 bg-white border border-gray-200 rounded-lg flex items-center justify-between">
                                    <div class="flex items-center space-x-2 sm:space-x-3 flex-1 min-w-0">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-left flex-1 min-w-0">
                                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ form.file.name }}</p>
                                            <p class="text-xs text-gray-500">{{ formatFileSize(form.file.size) }}</p>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click.stop="removeFile"
                                        class="text-red-500 hover:text-red-700 transition-colors flex-shrink-0 ml-2"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <p v-if="errors.file" class="mt-2 text-sm text-red-600">{{ errors.file }}</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-4 border-t border-gray-200">
                            <Link :href="route('courses.index')" class="text-sm text-center sm:text-left text-gray-600 hover:text-gray-800">
                                ← Kembali
                            </Link>
                            <button
                                type="submit"
                                :disabled="!form.file || form.processing"
                                :class="[
                                    'w-full sm:w-auto px-6 py-2.5 rounded-lg font-medium text-white transition-all flex items-center justify-center space-x-2',
                                    form.file && !form.processing
                                        ? 'bg-blue-600 hover:bg-blue-700 hover:shadow-lg transform hover:-translate-y-0.5'
                                        : 'bg-gray-300 cursor-not-allowed'
                                ]"
                            >
                                <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span class="text-sm sm:text-base">{{ form.processing ? 'Mengupload...' : 'Upload & Import' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-2 sm:mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-xs sm:text-sm font-semibold text-yellow-900 mb-1">Perhatian:</h4>
                        <ul class="text-xs sm:text-sm text-yellow-800 space-y-1">
                            <li>• Format file sesuai contoh</li>
                            <li>• Kode Mapel sama akan di-update</li>
                            <li>• Kolom Kode Mapel dan Nama Mapel wajib diisi</li>
                            <li>• Semester harus "ganjil" atau "genap"</li>
                            <li>• Status harus "aktif" atau "non-aktif"</li>
                            <li>• NIK/Kode Guru optional - bisa NIK (16 digit) atau Kode Guru</li>
                            <li>• Guru akan otomatis di-assign jika NIK/Kode Guru valid</li>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Breadcrumb from '@/Components/Breadcrumb.vue'

const dragOver = ref(false)
const errors = ref({})

const form = useForm({
    file: null
})

const handleFileSelect = (event) => {
    const file = event.target.files[0]
    if (file) {
        validateAndSetFile(file)
    }
}

const handleFileDrop = (event) => {
    dragOver.value = false
    const file = event.dataTransfer.files[0]
    if (file) {
        validateAndSetFile(file)
    }
}

const validateAndSetFile = (file) => {
    errors.value = {}
    
    // Validate file type
    const validTypes = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv',
        'application/csv'
    ]
    if (!validTypes.includes(file.type) && !file.name.match(/\.(xls|xlsx|csv)$/i)) {
        errors.value.file = 'File harus berformat XLS, XLSX, atau CSV'
        return
    }
    
    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
        errors.value.file = 'Ukuran file maksimal 5MB'
        return
    }
    
    form.file = file
}

const removeFile = () => {
    form.file = null
    errors.value = {}
}

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes'
    const k = 1024
    const sizes = ['Bytes', 'KB', 'MB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i]
}

const submitUpload = () => {
    if (!form.file) {
        errors.value.file = 'Pilih file terlebih dahulu'
        return
    }
    
    form.post(route('courses.import'), {
        preserveScroll: true,
        onError: (err) => {
            errors.value = err
        }
    })
}
</script>
