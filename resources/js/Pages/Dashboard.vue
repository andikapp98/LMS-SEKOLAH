<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <svg class="w-6 h-6 md:w-7 md:h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="hidden md:block ml-3">
                        <h2 class="font-bold text-2xl text-gray-800 leading-tight">Dashboard</h2>
                        <p class="text-sm text-gray-500">SMKS Yasmu Gresik - LMS</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-blue-50 px-3 py-1.5 md:px-4 md:py-2 rounded-lg">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs md:text-sm font-medium text-gray-700">{{ currentDate }}</span>
                </div>
            </div>
        </template>

        <div class="space-y-4 md:space-y-6">
            <!-- Welcome Card -->
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-2xl shadow-xl p-5 md:p-8 text-white">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 opacity-10 hidden md:block">
                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-3 md:mb-4">
                        <span class="text-3xl md:text-4xl">👋</span>
                        <div>
                            <p class="text-blue-200 text-xs md:text-sm">Selamat Datang,</p>
                            <h3 class="text-lg md:text-2xl font-bold">{{ truncatedName }}!</h3>
                        </div>
                    </div>
                    <p class="text-blue-100 text-sm md:text-base mb-4">
                        Semangat untuk hari ini! Anda memiliki 
                        <span class="font-bold text-yellow-300">{{ stats.pending_tasks || 0 }}</span> 
                        tugas yang belum diselesaikan.
                    </p>
                    <div class="flex items-center gap-2 text-xs md:text-sm text-blue-200 bg-white/10 rounded-lg px-3 py-2 w-fit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>Mari kita mulai hari yang produktif!</span>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Admin Cards -->
                <template v-if="isAdmin">
                    <!-- Total Courses -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Mata Pelajaran</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.total_courses }}</p>
                                    <p class="text-xs text-green-600 mt-2">+{{ stats.new_courses }} baru minggu ini</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Students -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Siswa</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.total_students }}</p>
                                    <p class="text-xs text-green-600 mt-2">+{{ stats.new_students }} siswa baru</p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Tasks -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tugas Pending</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.pending_tasks }}</p>
                                    <p class="text-xs text-orange-600 mt-2">{{ stats.due_today }} jatuh tempo hari ini</p>
                                </div>
                                <div class="bg-orange-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Tasks -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tugas Selesai</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.completed_tasks }}</p>
                                    <p class="text-xs text-green-600 mt-2">{{ completionRate }}% completion rate</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Teacher Cards -->
                <template v-else-if="$page.props.auth.user.role === 'teacher'">
                    <!-- My Courses -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Mata Pelajaran Saya</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.my_courses || 0 }}</p>
                                    <p class="text-xs text-blue-600 mt-2">Yang saya ampu</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Assignments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Total Tugas</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.total_assignments || 0 }}</p>
                                    <p class="text-xs text-purple-600 mt-2">Yang saya buat</p>
                                </div>
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Tasks -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tugas Pending</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.pending_tasks }}</p>
                                    <p class="text-xs text-orange-600 mt-2">{{ stats.due_today }} jatuh tempo hari ini</p>
                                </div>
                                <div class="bg-orange-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Tasks -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tugas Selesai</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.completed_tasks }}</p>
                                    <p class="text-xs text-green-600 mt-2">{{ completionRate }}% completion rate</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Student Cards -->
                <template v-else>
                    <!-- Tugas Tersedia -->
                    <Link href="/assignments" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all hover:scale-[1.02] cursor-pointer group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Tugas Tersedia</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.total_assignments || 0 }}</p>
                                    <p class="text-xs text-orange-600 mt-2 flex items-center">
                                        <span class="w-2 h-2 bg-orange-500 rounded-full mr-1 animate-pulse"></span>
                                        {{ stats.pending_tasks || 0 }} belum dikerjakan
                                    </p>
                                </div>
                                <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-3 rounded-full group-hover:scale-110 transition-transform shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Kuis Tersedia -->
                    <Link href="/quizzes" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all hover:scale-[1.02] cursor-pointer group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Kuis Tersedia</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.total_quizzes || 0 }}</p>
                                    <p class="text-xs text-purple-600 mt-2 flex items-center">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-1 animate-pulse"></span>
                                        {{ stats.available_quizzes || 0 }} bisa dikerjakan
                                    </p>
                                </div>
                                <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-3 rounded-full group-hover:scale-110 transition-transform shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Materi Pembelajaran -->
                    <Link href="/learning-media" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all hover:scale-[1.02] cursor-pointer group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600 mb-1">Materi Belajar</p>
                                    <p class="text-3xl font-bold text-gray-900">{{ stats.total_materials || 0 }}</p>
                                    <p class="text-xs text-blue-600 mt-2">Modul & dokumen</p>
                                </div>
                                <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-3 rounded-full group-hover:scale-110 transition-transform shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <!-- Nilai Tertinggi Kuis -->
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-all hover:scale-[1.02] cursor-pointer group">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-100 mb-1">Tugas Selesai</p>
                                    <p class="text-3xl font-bold text-white">{{ stats.completed_tasks || 0 }}</p>
                                    <p class="text-xs text-green-100 mt-2">
                                        🎉 {{ completionRate }}% completion
                                    </p>
                                </div>
                                <div class="bg-white/20 p-3 rounded-full group-hover:scale-110 transition-transform">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Quick Actions & Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-white to-blue-50 overflow-hidden shadow-lg sm:rounded-lg border border-blue-100">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Quick Actions
                            </h3>
                            <p class="text-blue-100 text-xs mt-1">Akses cepat ke fitur utama</p>
                        </div>
                        <div class="p-6 space-y-3">
                            <!-- Admin Only Actions -->
                            <template v-if="isAdmin">
                                <Link href="/courses/create" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-blue-100">
                                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-bold text-gray-800 block">Buat Mata Pelajaran</span>
                                        <span class="text-xs text-gray-500">Tambah mapel baru</span>
                                    </div>
                                    <svg class="w-5 h-5 text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </Link>

                                <Link href="/students/create" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-green-50 hover:to-green-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-green-100">
                                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-bold text-gray-800 block">Tambah Siswa</span>
                                        <span class="text-xs text-gray-500">Daftarkan siswa baru</span>
                                    </div>
                                    <svg class="w-5 h-5 text-green-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </Link>

                                <Link href="/students" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-indigo-50 hover:to-indigo-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-indigo-100">
                                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-bold text-gray-800 block">Daftar Siswa</span>
                                        <span class="text-xs text-gray-500">Kelola data siswa</span>
                                    </div>
                                    <svg class="w-5 h-5 text-indigo-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </Link>
                            </template>

                            <!-- Teacher/Admin Only: Create Assignment -->
                            <Link v-if="$page.props.auth.user.role !== 'student'" href="/assignments/create" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-purple-100">
                                <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-800 block">Buat Tugas Baru</span>
                                    <span class="text-xs text-gray-500">Tambahkan assignment</span>
                                </div>
                                <svg class="w-5 h-5 text-purple-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>

                            <!-- Teacher/Admin Only: Create Quiz -->
                            <Link v-if="$page.props.auth.user.role !== 'student'" href="/quizzes/create" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-green-50 hover:to-green-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-green-100">
                                <div class="bg-gradient-to-br from-green-500 to-green-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-800 block">Buat Kuis Baru</span>
                                    <span class="text-xs text-gray-500">Tambah quiz</span>
                                </div>
                                <svg class="w-5 h-5 text-green-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>

                            <!-- Everyone: Assignment List -->
                            <Link href="/assignments" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-orange-50 hover:to-orange-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-orange-100">
                                <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-800 block">{{ $page.props.auth.user.role === 'student' ? 'Tugas Saya' : 'Daftar Tugas' }}</span>
                                    <span class="text-xs text-gray-500">Lihat semua assignment</span>
                                </div>
                                <svg class="w-5 h-5 text-orange-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>

                            <!-- Everyone: Quizzes List -->
                            <Link href="/quizzes" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-blue-100">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-800 block">{{ $page.props.auth.user.role === 'student' ? 'Kuis Tersedia' : 'Daftar Kuis' }}</span>
                                    <span class="text-xs text-gray-500">Akses Kuis</span>
                                </div>
                                <svg class="w-5 h-5 text-blue-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>

                            <!-- Everyone: Materials List -->
                            <Link href="/learning-media" class="flex items-center p-4 bg-white hover:bg-gradient-to-r hover:from-pink-50 hover:to-pink-100 rounded-xl transition-all shadow-sm hover:shadow-md group border border-pink-100">
                                <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-3 rounded-xl mr-4 group-hover:scale-110 transition-transform shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-bold text-gray-800 block">Materi & Modul</span>
                                    <span class="text-xs text-gray-500">Download materi</span>
                                </div>
                                <svg class="w-5 h-5 text-pink-400 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>

                            <!-- Student Motivational Card -->
                            <div v-if="$page.props.auth.user.role === 'student'" class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-2 border-blue-200">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-blue-400 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">🎯 Tetap Semangat!</p>
                                        <p class="text-xs text-gray-600 mt-1">Kerjakan tugas tepat waktu dan raih prestasi terbaik!</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher Motivational Card -->
                            <div v-if="$page.props.auth.user.role === 'teacher'" class="mt-4 p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border-2 border-yellow-200">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-yellow-400 p-2 rounded-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">💡 Tips Produktif</p>
                                        <p class="text-xs text-gray-600 mt-1">Buat tugas yang terstruktur untuk hasil pembelajaran yang lebih baik!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
                            <div class="space-y-4">
                                <div v-for="activity in recentActivities" :key="activity.id" class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                    <div :class="[
                                        'p-2 rounded-full',
                                        activity.type === 'course' ? 'bg-blue-100' :
                                        activity.type === 'assignment' ? 'bg-green-100' :
                                        activity.type === 'student' ? 'bg-purple-100' : 'bg-gray-100'
                                    ]">
                                        <svg class="w-4 h-4" :class="[
                                            activity.type === 'course' ? 'text-blue-600' :
                                            activity.type === 'assignment' ? 'text-green-600' :
                                            activity.type === 'student' ? 'text-purple-600' : 'text-gray-600'
                                        ]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ activity.description }}</p>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ activity.time }}
                                    </div>
                                </div>

                                <div v-if="recentActivities.length === 0" class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p>Belum ada aktivitas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Tasks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Tugas yang Akan Datang</h3>
                        <Link href="#" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua →</Link>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenggat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="task in upcomingTasks" :key="task.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ task.title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ task.course }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ task.due_date }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            task.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                            task.status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                                            'bg-green-100 text-green-800'
                                        ]">
                                            {{ task.status === 'pending' ? 'Pending' : task.status === 'in_progress' ? 'In Progress' : 'Completed' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link href="#" class="text-blue-600 hover:text-blue-900 mr-3">Lihat</Link>
                                        <Link href="#" class="text-green-600 hover:text-green-900">Edit</Link>
                                    </td>
                                </tr>
                                <tr v-if="upcomingTasks.length === 0">
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada tugas yang akan datang
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_courses: 12,
            new_courses: 2,
            total_students: 145,
            new_students: 8,
            pending_tasks: 7,
            due_today: 2,
            completed_tasks: 28
        })
    },
    recentActivities: {
        type: Array,
        default: () => [
            {
                id: 1,
                type: 'course',
                title: 'Mata Pelajaran Baru',
                description: 'Matematika Lanjutan telah ditambahkan',
                time: '5 menit lalu'
            },
            {
                id: 2,
                type: 'assignment',
                title: 'Tugas Diserahkan',
                description: 'Ahmad Fauzi mengumpulkan tugas Matematika',
                time: '15 menit lalu'
            },
            {
                id: 3,
                type: 'student',
                title: 'Siswa Baru',
                description: '3 siswa baru bergabung di kelas 10A',
                time: '1 jam lalu'
            },
            {
                id: 4,
                type: 'course',
                title: 'Materi Diperbarui',
                description: 'Materi Fisika Bab 3 telah diperbarui',
                time: '2 jam lalu'
            }
        ]
    },
    upcomingTasks: {
        type: Array,
        default: () => [
            {
                id: 1,
                title: 'Essay Sejarah Indonesia',
                course: 'Sejarah',
                due_date: '29 Nov 2025',
                status: 'pending'
            },
            {
                id: 2,
                title: 'Praktikum Lab Kimia',
                course: 'Kimia',
                due_date: '30 Nov 2025',
                status: 'in_progress'
            },
            {
                id: 3,
                title: 'Presentasi Bahasa Inggris',
                course: 'Bahasa Inggris',
                due_date: '2 Des 2025',
                status: 'pending'
            }
        ]
    },
    isAdmin: {
        type: Boolean,
        default: false
    }
});

const currentDate = computed(() => {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date().toLocaleDateString('id-ID', options);
});

const completionRate = computed(() => {
    const total = props.stats.pending_tasks + props.stats.completed_tasks;
    if (total === 0) return 0;
    return Math.round((props.stats.completed_tasks / total) * 100);
});

// Get user name for welcome message
const truncatedName = computed(() => {
    return usePage().props.auth?.user?.name || 'User';
});
</script>
