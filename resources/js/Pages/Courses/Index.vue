<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Daftar Mata Pelajaran
                </h2>
                <div class="flex gap-2">
                    <Link :href="route('courses.upload')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Excel
                    </Link>
                    <Link href="/courses/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Mata Pelajaran
                    </Link>
                    <button @click="clearAllCourses" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Semua Data
                    </button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <Breadcrumb :items="[{ label: 'Mata Pelajaran' }]" />

            <!-- Stats Card -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Mata Pelajaran</p>
                        <p class="text-4xl font-bold mt-2">{{ groupedCourses?.length || 0 }}</p>
                        <p class="text-purple-100 text-sm mt-1">Terdaftar di sistem</p>
                    </div>
                    <div>
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Courses List -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Data Mata Pelajaran</h3>
                </div>

                <div v-if="!courses || courses.length === 0" class="p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum ada mata pelajaran</h3>
                    <p class="mt-2 text-sm text-gray-500">Mulai tambahkan mata pelajaran dengan klik tombol di atas</p>
                    <div class="mt-6">
                        <Link href="/courses/create" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Mata Pelajaran
                        </Link>
                    </div>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JP/Minggu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(course, index) in groupedCourses" :key="course.nama_mapel" class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ course.nama_mapel }}</div>
                                    <div v-if="course.deskripsi" class="text-xs text-gray-500 truncate max-w-xs">{{ course.deskripsi }}</div>
                                    <div v-if="course.teachers && course.teachers.length > 0" class="text-xs text-indigo-600 mt-1">
                                        <svg class="w-3 h-3 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                        {{ course.teachers.length }} guru pengampu
                                    </div>
                                    <div v-else class="text-xs text-gray-400 mt-1">Belum ada guru</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ course.jam_per_minggu }} JP
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 capitalize">
                                        {{ course.semester }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <Link :href="`/courses/${course.course_ids[0]}`" class="text-blue-600 hover:text-blue-900 mr-3">Detail</Link>
                                    <Link :href="`/courses/${course.course_ids[0]}/edit`" class="text-green-600 hover:text-green-900 mr-3">Edit</Link>
                                    <button @click="deleteCourse(course)" class="text-red-600 hover:text-red-900">Hapus</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Dialog -->
        <ConfirmDialog
            :show="showDeleteConfirm"
            type="danger"
            title="Hapus Mata Pelajaran?"
            :message="deleteMessage"
            confirm-text="Ya, Hapus"
            cancel-text="Batal"
            @confirm="confirmDelete"
            @cancel="showDeleteConfirm = false"
        />
        
        <!-- Clear All Confirmation Dialog -->
        <ConfirmDialog
            :show="showClearConfirm"
            type="danger"
            title="Hapus Semua Data Mata Pelajaran?"
            message="Apakah Anda yakin ingin menghapus SEMUA data mata pelajaran? Tindakan ini akan menghapus semua mata pelajaran dan relasi dengan guru. Tindakan ini tidak dapat dibatalkan!"
            confirm-text="Ya, Hapus Semua"
            cancel-text="Batal"
            @confirm="confirmClearAll"
            @cancel="showClearConfirm = false"
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
    courses: {
        type: Array,
        required: true
    }
});

// Group courses by nama_mapel untuk compact display
const groupedCourses = computed(() => {
    const groups = {};
    
    props.courses.forEach(course => {
        const key = course.nama_mapel;
        
        if (!groups[key]) {
            groups[key] = {
                nama_mapel: course.nama_mapel,
                kelas: course.kelas,
                jam_per_minggu: course.jam_per_minggu,
                semester: course.semester,
                deskripsi: course.deskripsi,
                teachers: [],
                course_ids: []
            };
        }
        
        // Tambahkan guru dari course ini
        if (course.teachers && course.teachers.length > 0) {
            course.teachers.forEach(teacher => {
                // Avoid duplicate guru
                if (!groups[key].teachers.some(t => t.id === teacher.id)) {
                    groups[key].teachers.push(teacher);
                }
            });
        }
        
        groups[key].course_ids.push(course.id);
    });
    
    return Object.values(groups);
});

const showDeleteConfirm = ref(false);
const courseToDelete = ref(null);
const deleteMessage = ref('');
const showClearConfirm = ref(false);

const deleteCourse = (course) => {
    courseToDelete.value = course;
    deleteMessage.value = `Apakah Anda yakin ingin menghapus mata pelajaran "${course.nama_mapel}"? Tindakan ini tidak dapat dibatalkan.`;
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    // Hapus semua courses dengan nama_mapel yang sama
    const deletePromises = courseToDelete.value.course_ids.map(id => {
        return new Promise((resolve) => {
            router.delete(`/courses/${id}`, {
                preserveScroll: true,
                onSuccess: () => resolve(),
                onError: () => resolve()
            });
        });
    });
    
    Promise.all(deletePromises).then(() => {
        showDeleteConfirm.value = false;
        courseToDelete.value = null;
        router.reload();
    });
};

const clearAllCourses = () => {
    showClearConfirm.value = true;
};

const confirmClearAll = () => {
    router.post(route('courses.clear'), {}, {
        preserveScroll: true,
        onSuccess: () => {
            showClearConfirm.value = false;
        }
    });
};
</script>
