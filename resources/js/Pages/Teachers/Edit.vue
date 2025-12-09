<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Guru
                </h2>
                <Link :href="`/teachers/${teacher.id}`" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Detail
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[
                { label: 'Daftar Guru', href: '/teachers' },
                { label: 'Edit Guru' }
            ]" />

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Form Edit Guru</h3>
                    <p class="text-sm text-gray-600">Ubah data guru sesuai kebutuhan</p>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NIK</label>
                            <input
                                v-model="form.nik"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="NIK"
                            />
                            <p v-if="errors.nik" class="mt-1 text-sm text-red-600">{{ errors.nik }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Nama lengkap"
                            />
                            <p v-if="errors.nama" class="mt-1 text-sm text-red-600">{{ errors.nama }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="email@example.com"
                            />
                            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. HP</label>
                            <input
                                v-model="form.no_hp"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="08xxxxxxxxxx"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                            <input
                                v-model="form.mata_pelajaran"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Contoh: Matematika, Bahasa Indonesia"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                            <input
                                v-model="form.pendidikan_terakhir"
                                type="text"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="S1, S2, S3"
                            />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                            <textarea
                                v-model="form.alamat"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                placeholder="Alamat lengkap"
                            ></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <Link
                            :href="`/teachers/${teacher.id}`"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
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
    teacher: {
        type: Object,
        required: true
    }
});

const page = usePage();
const errors = computed(() => page.props.errors || {});
const isSubmitting = ref(false);

const form = reactive({
    nik: props.teacher.nik || '',
    nama: props.teacher.nama || '',
    email: props.teacher.email || '',
    no_hp: props.teacher.no_hp || '',
    mata_pelajaran: props.teacher.mata_pelajaran || '',
    alamat: props.teacher.alamat || '',
    pendidikan_terakhir: props.teacher.pendidikan_terakhir || ''
});

const submitForm = () => {
    isSubmitting.value = true;
    router.put(`/teachers/${props.teacher.id}`, form, {
        preserveScroll: true,
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>
