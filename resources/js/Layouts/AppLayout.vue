<template>
    <div class="min-h-screen bg-gray-100 overflow-x-hidden">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 md:h-20">
                    <div class="flex items-center space-x-4 md:space-x-8">
                        <!-- Logo & Brand -->
                        <Link href="/dashboard" class="flex items-center space-x-3 group">
                            <div class="flex-shrink-0 bg-white rounded-full p-1.5 md:p-2 shadow-md group-hover:shadow-lg transition-shadow">
                                <img src="/images/logo.png" alt="Logo" class="h-8 w-8 md:h-12 md:w-12 object-contain">
                            </div>
                            <!-- Hide text on mobile -->
                            <div class="hidden md:flex flex-col">
                                <span class="text-xl font-bold text-white tracking-tight">SMKS YASMU GRESIK</span>
                                <span class="text-xs text-blue-100 font-medium">Learning Management System</span>
                            </div>
                        </Link>

                        <!-- Menu Toggle Button (Mobile) -->
                        <button 
                            @click="sidebarOpen = !sidebarOpen"
                            class="md:hidden text-white p-2 rounded-lg hover:bg-white/10"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Right Navigation -->
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <!-- User info - icon only on mobile -->
                        <div class="flex items-center space-x-2 md:space-x-3 bg-white/10 backdrop-blur-sm rounded-lg px-2 py-1.5 md:px-4 md:py-2">
                            <div class="bg-white rounded-full p-1 md:p-1.5">
                                <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <!-- Hide name on mobile -->
                            <span class="hidden md:inline text-white font-medium">{{ user.name }}</span>
                        </div>
                        <!-- Logout button -->
                        <Link 
                            href="/logout" 
                            method="post"
                            as="button"
                            class="flex items-center space-x-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-2 py-1.5 md:px-4 md:py-2 rounded-lg transition-all hover:shadow-md"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="hidden md:inline font-medium">Logout</span>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            <aside 
                :class="[
                    'fixed md:sticky top-0 left-0 z-40 h-screen transition-transform',
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
                ]"
                class="w-64 bg-white shadow-lg"
            >
                <div class="h-full px-3 py-4 overflow-y-auto">
                    <!-- Close button (Mobile) -->
                    <button 
                        @click="sidebarOpen = false"
                        class="md:hidden absolute top-4 right-4 text-gray-500 hover:text-gray-700"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <nav class="space-y-2 mt-8 md:mt-0">
                        <!-- Dashboard -->
                        <Link 
                            href="/dashboard"
                            :class="isActive('/dashboard')"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors group"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </Link>

                        <!-- Siswa - Admin Only -->
                        <Link 
                            v-if="user.role === 'admin'"
                            href="/students"
                            :class="isActive('/students')"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors group"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-medium">Siswa</span>
                        </Link>

                        <!-- Guru - Admin Only -->
                        <Link 
                            v-if="user.role === 'admin'"
                            href="/teachers"
                            :class="isActive('/teachers')"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors group"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="font-medium">Guru</span>
                        </Link>

                        <!-- Mata Pelajaran - Admin Only -->
                        <Link 
                            v-if="user.role === 'admin'"
                            href="/courses"
                            :class="isActive('/courses')"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors group"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="font-medium">Mata Pelajaran</span>
                        </Link>

                        <!-- Tugas - Accessible by All -->
                        <Link 
                            href="/assignments"
                            :class="isActive('/assignments')"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors group"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            <span class="font-medium">{{ user.role === 'student' ? 'Tugas Saya' : 'Daftar Tugas' }}</span>
                        </Link>

                        <!-- Materi & Modul - Accessible by All -->
                        <Link 
                            href="/learning-media"
                            :class="isActive('/learning-media')"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors group"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="font-medium">{{ user.role === 'student' ? 'Materi Belajar' : 'Materi & Modul' }}</span>
                        </Link>

                        <!-- Kuis & Ujian - Accessible by All -->
                        <Link 
                            href="/quizzes"
                            :class="isActive('/quizzes')"
                            class="flex items-center px-4 py-3 rounded-lg transition-colors group"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <span class="font-medium">{{ user.role === 'student' ? 'Kuis Tersedia' : 'Kuis & Ujian' }}</span>
                        </Link>
                    </nav>
                </div>
            </aside>

            <!-- Overlay (Mobile) -->
            <div 
                v-if="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
            ></div>

            <!-- Main Content -->
            <div class="flex-1 min-w-0 overflow-hidden">
                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Notification Toast -->
                <Notification
                    :show="showNotification"
                    :type="notificationType"
                    :title="notificationTitle"
                    :message="notificationMessage"
                    @close="showNotification = false"
                />

                <!-- Page Content -->
                <main>
                    <div class="py-6 md:py-12">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <slot />
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Notification from '@/Components/Notification.vue';

defineProps({
    user: Object
});

const page = usePage();
const showNotification = ref(false);
const notificationType = ref('info');
const notificationTitle = ref('');
const notificationMessage = ref('');
const sidebarOpen = ref(false);

// Function to check if current route is active
const isActive = (path) => {
    const currentPath = page.url;
    const isCurrentPath = currentPath === path || currentPath.startsWith(path + '/');
    
    return isCurrentPath 
        ? 'bg-blue-50 text-blue-600 hover:bg-blue-100' 
        : 'text-gray-700 hover:bg-gray-100 hover:text-blue-600';
};

// Watch for flash messages
watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        showNotification.value = true;
        notificationType.value = 'success';
        notificationTitle.value = 'Berhasil!';
        notificationMessage.value = flash.success;
    } else if (flash?.error) {
        showNotification.value = true;
        notificationType.value = 'error';
        notificationTitle.value = 'Error!';
        notificationMessage.value = flash.error;
    }
}, { deep: true, immediate: true });
</script>
