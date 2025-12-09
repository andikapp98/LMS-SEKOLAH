<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Mata Pelajaran
                </h2>
                <Link :href="`/courses/${course.id}`" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Detail
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Mata Pelajaran', href: '/courses' },
                { label: 'Edit Mata Pelajaran' }
            ]" />

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Form Edit Mata Pelajaran</h3>
                    <p class="text-sm text-gray-600">Ubah data mata pelajaran sesuai kebutuhan</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.kode_mapel"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Contoh: MAT-10"
                            />
                            <p v-if="errors.kode_mapel" class="mt-1 text-sm text-red-600">{{ errors.kode_mapel }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nama_mapel"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Contoh: Matematika"
                            />
                            <p v-if="errors.nama_mapel" class="mt-1 text-sm text-red-600">{{ errors.nama_mapel }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Guru Pengampu <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.teacher_id"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                                <option value="">Pilih Guru</option>
                                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                                    {{ teacher.nama }}
                                </option>
                            </select>
                            <p v-if="errors.teacher_id" class="mt-1 text-sm text-red-600">{{ errors.teacher_id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jam per Minggu <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model.number="form.jam_per_minggu"
                                type="number"
                                min="1"
                                max="10"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Jumlah jam pelajaran"
                            />
                            <p v-if="errors.jam_per_minggu" class="mt-1 text-sm text-red-600">{{ errors.jam_per_minggu }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                            <input
                                v-model="form.kelas"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Contoh: X, XI, XII atau 10 TPM 1"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.semester"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                                <option value="">Pilih Semester</option>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                            <p v-if="errors.semester" class="mt-1 text-sm text-red-600">{{ errors.semester }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tahun Ajaran <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.tahun_ajaran"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Contoh: 2024/2025"
                            />
                            <p v-if="errors.tahun_ajaran" class="mt-1 text-sm text-red-600">{{ errors.tahun_ajaran }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea
                                v-model="form.deskripsi"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Deskripsi mata pelajaran (opsional)"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <Link
                            :href="`/courses/${course.id}`"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
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
    course: {
        type: Object,
        required: true
    },
    teachers: {
        type: Array,
        required: true
    }
});

const page = usePage();
const errors = computed(() => page.props.errors || {});
const isSubmitting = ref(false);

const form = reactive({
    kode_mapel: props.course.kode_mapel || '',
    nama_mapel: props.course.nama_mapel || '',
    teacher_id: props.course.teacher_id || '',
    kelas: props.course.kelas || '',
    jam_per_minggu: props.course.jam_per_minggu || 2,
    deskripsi: props.course.deskripsi || '',
    semester: props.course.semester || '',
    tahun_ajaran: props.course.tahun_ajaran || ''
});

const submitForm = () => {
    isSubmitting.value = true;
    router.put(`/courses/${props.course.id}`, form, {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>
