<template>
    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 md:h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <img src="/images/logo.png" alt="Logo" class="h-10 w-10 md:h-12 md:w-12 object-contain">
                        <div class="hidden sm:block">
                            <h1 class="text-lg md:text-xl font-bold text-gray-800">SMKS YASMU</h1>
                            <p class="text-xs text-gray-500">Learning Management System</p>
                        </div>
                    </div>

                    <!-- Nav Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#beranda" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Beranda</a>
                        <a href="#fitur" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Fitur</a>
                        <a href="#tentang" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Tentang</a>
                        <a href="#kontak" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Kontak</a>
                    </div>

                    <!-- Auth Buttons -->
                    <div class="flex items-center space-x-3">
                        <Link 
                            href="/login"
                            class="px-5 py-2.5 md:px-6 md:py-3 text-sm md:text-base font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-full hover:from-green-600 hover:to-emerald-700 transition-all duration-300 shadow-lg shadow-green-200 hover:shadow-green-300 hover:scale-105"
                        >
                            Masuk
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section id="beranda" class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-orange-50 py-12 md:py-20 lg:py-28">
            <!-- Decorative Elements -->
            <div class="absolute top-20 left-10 w-20 h-20 bg-yellow-300 rounded-full opacity-60 blur-xl"></div>
            <div class="absolute bottom-20 right-10 w-32 h-32 bg-blue-300 rounded-full opacity-50 blur-xl"></div>
            <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-green-300 rounded-full opacity-40 blur-lg"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                    <!-- Hero Content -->
                    <div class="text-center md:text-left order-2 md:order-1">
                        <div class="inline-flex items-center bg-green-100 rounded-full px-4 py-2 mb-6">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            <span class="text-green-700 text-sm font-medium">Platform Pembelajaran Digital #1</span>
                        </div>
                        
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-800 leading-tight mb-6">
                            Belajar Jadi Lebih
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500">
                                Menyenangkan!
                            </span>
                        </h1>
                        
                        <p class="text-base md:text-lg lg:text-xl text-gray-600 mb-8 max-w-xl mx-auto md:mx-0">
                            Platform LMS modern untuk siswa dan guru SMKS YASMU GRESIK. 
                            Akses materi, kerjakan tugas, dan pantau perkembangan belajar dengan mudah.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <Link 
                                href="/login"
                                class="inline-flex items-center justify-center px-8 py-4 text-base md:text-lg font-bold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-full shadow-xl shadow-green-200 hover:shadow-green-300 hover:from-green-600 hover:to-emerald-700 transition-all duration-300 hover:scale-105"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Mulai Belajar
                            </Link>
                            <a 
                                href="#fitur"
                                class="inline-flex items-center justify-center px-8 py-4 text-base md:text-lg font-semibold text-gray-700 bg-white border-2 border-gray-200 rounded-full hover:border-green-400 hover:text-green-600 transition-all duration-300"
                            >
                                Pelajari Fitur
                            </a>
                        </div>
                    </div>

                    <!-- Hero Illustration -->
                    <div class="order-1 md:order-2 flex justify-center">
                        <div class="relative w-full max-w-md">
                            <!-- Main Card -->
                            <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-8 border border-gray-100">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-gray-800 font-bold">LMS Dashboard</div>
                                            <div class="text-gray-400 text-sm">Siswa Aktif</div>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm font-medium">Online</span>
                                </div>

                                <!-- Stats Cards -->
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-4">
                                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center mb-3">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <div class="text-2xl font-bold text-gray-800">12</div>
                                        <div class="text-gray-500 text-sm">Tugas Baru</div>
                                    </div>
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-4">
                                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mb-3">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="text-2xl font-bold text-gray-800">85%</div>
                                        <div class="text-gray-500 text-sm">Progress</div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="bg-gray-100 rounded-2xl p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-700 font-medium">Matematika</span>
                                        <span class="text-blue-600 font-bold">75%</span>
                                    </div>
                                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full w-3/4 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Elements -->
                            <div class="absolute -top-4 -right-4 bg-yellow-400 rounded-2xl p-3 shadow-lg animate-bounce-slow">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div class="absolute -bottom-2 -left-4 bg-green-400 rounded-2xl p-3 shadow-lg animate-bounce-slow animation-delay-1000">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 mt-16 md:mt-24">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 text-center hover:shadow-xl transition-shadow">
                        <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">500+</div>
                        <div class="text-gray-600 text-sm md:text-base">Siswa Aktif</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 text-center hover:shadow-xl transition-shadow">
                        <div class="text-3xl md:text-4xl font-bold text-blue-500 mb-2">40+</div>
                        <div class="text-gray-600 text-sm md:text-base">Guru Profesional</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 text-center hover:shadow-xl transition-shadow">
                        <div class="text-3xl md:text-4xl font-bold text-green-500 mb-2">100+</div>
                        <div class="text-gray-600 text-sm md:text-base">Materi Tersedia</div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 text-center hover:shadow-xl transition-shadow">
                        <div class="text-3xl md:text-4xl font-bold text-purple-500 mb-2">24/7</div>
                        <div class="text-gray-600 text-sm md:text-base">Akses Kapanpun</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="fitur" class="py-16 md:py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 md:mb-16">
                    <span class="inline-block px-4 py-2 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold mb-4">FITUR UNGGULAN</span>
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                        Semua yang Kamu Butuhkan untuk Belajar
                    </h2>
                    <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                        Platform lengkap untuk mendukung proses belajar mengajar yang efektif dan menyenangkan
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    <!-- Feature 1 -->
                    <div class="group bg-white rounded-3xl p-6 md:p-8 border-2 border-gray-100 hover:border-green-200 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-green-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Materi & Modul</h3>
                        <p class="text-gray-600">
                            Akses materi pembelajaran dan modul lengkap dari guru kapan saja dan di mana saja.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group bg-white rounded-3xl p-6 md:p-8 border-2 border-gray-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Tugas Online</h3>
                        <p class="text-gray-600">
                            Kumpulkan tugas secara online dengan sistem deadline otomatis dan feedback dari guru.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group bg-white rounded-3xl p-6 md:p-8 border-2 border-gray-100 hover:border-green-200 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-green-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Kuis & Ujian</h3>
                        <p class="text-gray-600">
                            Kerjakan kuis dan ujian online dengan penilaian otomatis dan hasil yang transparan.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="group bg-white rounded-3xl p-6 md:p-8 border-2 border-gray-100 hover:border-purple-200 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-purple-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Dashboard Interaktif</h3>
                        <p class="text-gray-600">
                            Pantau progress belajar, nilai, dan tugas yang harus dikerjakan secara real-time.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="group bg-white rounded-3xl p-6 md:p-8 border-2 border-gray-100 hover:border-pink-200 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-pink-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-pink-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Multi Jurusan</h3>
                        <p class="text-gray-600">
                            Mendukung semua program keahlian dengan materi yang disesuaikan untuk setiap jurusan.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="group bg-white rounded-3xl p-6 md:p-8 border-2 border-gray-100 hover:border-cyan-200 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-cyan-400 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-cyan-200 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Akses Mobile</h3>
                        <p class="text-gray-600">
                            Belajar dari mana saja dengan tampilan responsif yang nyaman di smartphone.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="tentang" class="py-16 md:py-24 bg-gradient-to-br from-green-50 via-white to-emerald-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="inline-block px-4 py-2 bg-green-100 text-green-600 rounded-full text-sm font-semibold mb-4">TENTANG KAMI</span>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-6">
                            Platform Pembelajaran Modern untuk Generasi Digital
                        </h2>
                        <p class="text-gray-600 mb-6">
                            LMS SMKS YASMU GRESIK adalah platform pembelajaran digital yang dirancang khusus untuk mendukung kegiatan belajar mengajar di era modern. Dengan antarmuka yang intuitif dan fitur lengkap, kami memudahkan siswa dan guru dalam proses pembelajaran.
                        </p>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Akses materi pembelajaran 24 jam sehari</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Sistem penilaian otomatis dan transparan</span>
                            </li>
                            <li class="flex items-start">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Komunikasi mudah antara siswa dan guru</span>
                            </li>
                        </ul>
                    </div>
                    <div class="flex justify-center">
                        <div class="relative">
                            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm">
                                <div class="text-center mb-6">
                                    <img src="/images/logo.png" alt="Logo" class="w-20 h-20 mx-auto mb-4 object-contain">
                                    <h3 class="text-xl font-bold text-gray-800">SMKS YASMU GRESIK</h3>
                                    <p class="text-gray-500 text-sm">Jawa Timur, Indonesia</p>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                        <span class="text-gray-600">Total Siswa</span>
                                        <span class="font-bold text-gray-800">500+</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                        <span class="text-gray-600">Total Guru</span>
                                        <span class="font-bold text-gray-800">40+</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                                        <span class="text-gray-600">Jurusan</span>
                                        <span class="font-bold text-gray-800">5</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Decorative -->
                            <div class="absolute -top-4 -right-4 w-24 h-24 bg-green-200 rounded-full opacity-50 blur-xl -z-10"></div>
                            <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-emerald-200 rounded-full opacity-50 blur-xl -z-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 md:py-24 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-6">
                    Siap Memulai Perjalanan Belajar?
                </h2>
                <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Masuk ke akun LMS Anda dan mulai akses semua materi pembelajaran, tugas, dan kuis dengan mudah.
                </p>
                <Link 
                    href="/login"
                    class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-green-600 bg-white rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300"
                >
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk Sekarang
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer id="kontak" class="bg-gray-900 text-white py-12 md:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8 mb-8">
                    <!-- Brand -->
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="/images/logo.png" alt="Logo" class="h-12 w-12 object-contain">
                            <div>
                                <h3 class="text-lg font-bold">SMKS YASMU GRESIK</h3>
                                <p class="text-gray-400 text-sm">Learning Management System</p>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm max-w-md">
                            Platform pembelajaran digital untuk mendukung kegiatan belajar mengajar di SMKS YASMU Gresik. Akses mudah, kapan saja dan di mana saja.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h4 class="font-semibold mb-4">Tautan Cepat</h4>
                        <ul class="space-y-2">
                            <li><a href="#beranda" class="text-gray-400 hover:text-white transition-colors text-sm">Beranda</a></li>
                            <li><a href="#fitur" class="text-gray-400 hover:text-white transition-colors text-sm">Fitur</a></li>
                            <li><a href="#tentang" class="text-gray-400 hover:text-white transition-colors text-sm">Tentang</a></li>
                            <li><Link href="/login" class="text-gray-400 hover:text-white transition-colors text-sm">Login</Link></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h4 class="font-semibold mb-4">Kontak</h4>
                        <ul class="space-y-3 text-gray-400 text-sm">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Gresik, Jawa Timur
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                info@smkyasmugresik.sch.id
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-800 text-center">
                    <p class="text-gray-500 text-sm">
                        © 2025 SMKS YASMU GRESIK. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.animate-bounce-slow {
    animation: bounce-slow 3s ease-in-out infinite;
}

.animation-delay-1000 {
    animation-delay: 1s;
}

html {
    scroll-behavior: smooth;
}
</style>
