<template>
    <AppLayout :user="$page.props.auth.user">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Siswa
                </h2>
                <Link href="/dashboard" class="text-sm text-blue-600 hover:text-blue-800">
                    ← Kembali ke Dashboard
                </Link>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Breadcrumb -->
            <Breadcrumb :items="[
                { label: 'Daftar Siswa', href: '/students' },
                { label: 'Tambah Siswa' }
            ]" />

            <!-- Tab Navigation -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button
                            @click="activeTab = 'manual'"
                            :class="[
                                'py-4 px-6 text-sm font-medium border-b-2 transition-colors',
                                activeTab === 'manual'
                                    ? 'border-blue-500 text-blue-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            ]"
                        >
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Input Manual</span>
                            </div>
                        </button>
                        <button
                            @click="activeTab = 'excel'"
                            :class="[
                                'py-4 px-6 text-sm font-medium border-b-2 transition-colors',
                                activeTab === 'excel'
                                    ? 'border-green-500 text-green-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            ]"
                        >
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span>Upload Excel</span>
                            </div>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Manual Input Form -->
            <div v-show="activeTab === 'manual'" class="bg-white rounded-lg shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Form Input Siswa</h3>
                    <p class="text-sm text-gray-600">Isi formulir di bawah ini untuk menambahkan siswa baru</p>
                </div>

                <form @submit.prevent="submitManualForm" class="space-y-6">
                    <!-- Data Pribadi -->
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Pribadi
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIS -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    NIS <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="manualForm.nis"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nomor Induk Siswa"
                                />
                                <p v-if="errors.nis" class="mt-1 text-sm text-red-600">{{ errors.nis[0] }}</p>
                            </div>

                            <!-- NISN -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    NISN
                                </label>
                                <input
                                    v-model="manualForm.nisn"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nomor Induk Siswa Nasional"
                                />
                                <p v-if="errors.nisn" class="mt-1 text-sm text-red-600">{{ errors.nisn[0] }}</p>
                            </div>

                            <!-- Nama -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input
                                    v-model="manualForm.nama"
                                    type="text"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nama lengkap siswa"
                                />
                                <p v-if="errors.nama" class="mt-1 text-sm text-red-600">{{ errors.nama[0] }}</p>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select
                                    v-model="manualForm.jenis_kelamin"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <p v-if="errors.jenis_kelamin" class="mt-1 text-sm text-red-600">{{ errors.jenis_kelamin[0] }}</p>
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tempat Lahir
                                </label>
                                <input
                                    v-model="manualForm.tempat_lahir"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Kota/Kabupaten"
                                />
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <input
                                    v-model="manualForm.tanggal_lahir"
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                />
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Alamat
                                </label>
                                <textarea
                                    v-model="manualForm.alamat"
                                    rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Alamat lengkap siswa"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Akademik -->
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Data Akademik
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Tingkat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tingkat
                                </label>
                                <select
                                    v-model="selectedTingkat"
                                    @change="updateKelas"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                                    <option value="">Pilih Tingkat</option>
                                    <option value="10">Kelas 10 (X)</option>
                                    <option value="11">Kelas 11 (XI)</option>
                                    <option value="12">Kelas 12 (XII)</option>
                                </select>
                            </div>

                            <!-- Jurusan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jurusan
                                </label>
                                <select
                                    v-model="selectedJurusan"
                                    @change="updateKelas"
                                    :disabled="!selectedTingkat"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                >
                                    <option value="">Pilih Jurusan</option>
                                    <option value="TPM">TPM - Teknik Pemesinan</option>
                                    <option value="TKR">TKR - Teknik Kendaraan Ringan</option>
                                    <option value="TL">TL - Teknik Logistik</option>
                                    <option value="DKV">DKV - Desain Komunikasi Visual</option>
                                    <option value="APL">APL - Analisis Pengujian Laboratorium</option>
                                    <option value="MPK">MPK - Manajemen Perkantoran</option>
                                </select>
                            </div>

                            <!-- Rombel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rombel
                                </label>
                                <select
                                    v-model="selectedRombel"
                                    @change="updateKelas"
                                    :disabled="!selectedJurusan"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all disabled:bg-gray-100 disabled:cursor-not-allowed"
                                >
                                    <option value="">Pilih Rombel</option>
                                    <option v-for="n in availableRombel" :key="n" :value="n">{{ n }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Kelas Preview -->
                        <div v-if="manualForm.kelas" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">Kelas yang dipilih:</span> 
                                <span class="font-bold text-blue-900">{{ manualForm.kelas }}</span>
                            </p>
                        </div>

                        <p v-if="errors.kelas" class="mt-2 text-sm text-red-600">{{ errors.kelas[0] }}</p>
                    </div>

                    <!-- Data Kontak -->
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Data Kontak
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- No HP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    No. HP Siswa
                                </label>
                                <input
                                    v-model="manualForm.no_hp"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="08xxxxxxxxxx"
                                />
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input
                                    v-model="manualForm.email"
                                    type="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="email@example.com"
                                />
                                <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email[0] }}</p>
                            </div>

                            <!-- Nama Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Wali/Orang Tua
                                </label>
                                <input
                                    v-model="manualForm.nama_wali"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="Nama orang tua/wali"
                                />
                            </div>

                            <!-- No HP Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    No. HP Wali/Orang Tua
                                </label>
                                <input
                                    v-model="manualForm.no_hp_wali"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    placeholder="08xxxxxxxxxx"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-3">
                        <Link
                            href="/dashboard"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                        >
                            <svg v-if="isSubmitting" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Data Siswa' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Excel Upload Form -->
            <div v-show="activeTab === 'excel'" class="space-y-6">
                <!-- Info Card -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-blue-900 mb-3">📋 Panduan Upload Excel - SMKS Yasmu Gresik</h3>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="font-semibold text-blue-900 mb-1">Langkah-langkah:</p>
                                    <ul class="space-y-1 text-sm text-blue-800 ml-4">
                                        <li>1. Download template Excel di bawah ini</li>
                                        <li>2. Isi data siswa sesuai kolom yang tersedia</li>
                                        <li>3. Upload file yang sudah diisi</li>
                                    </ul>
                                </div>

                                <div>
                                    <p class="font-semibold text-blue-900 mb-1">Format File:</p>
                                    <ul class="space-y-1 text-sm text-blue-800 ml-4">
                                        <li>• Tipe: .xlsx, .xls, atau .csv</li>
                                        <li>• Ukuran maksimal: 5MB</li>
                                    </ul>
                                </div>

                                <div>
                                    <p class="font-semibold text-blue-900 mb-1">⚠️ Format Kelas (PENTING!):</p>
                                    <ul class="space-y-1 text-sm text-blue-800 ml-4">
                                        <li>• Format: <code class="bg-blue-100 px-1 rounded">Tingkat Jurusan Rombel</code></li>
                                        <li>• Contoh: <code class="bg-blue-100 px-1 rounded">10 TPM 1</code>, <code class="bg-blue-100 px-1 rounded">12 TPM 5</code>, <code class="bg-blue-100 px-1 rounded">11 MPK 2</code></li>
                                        <li>• Jurusan: TPM, TKR, TL, DKV, APL, MPK</li>
                                    </ul>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mt-3">
                                    <p class="font-semibold text-yellow-900 mb-1 text-sm">🔔 Perhatian Khusus:</p>
                                    <ul class="space-y-1 text-xs text-yellow-800 ml-4">
                                        <li>• <strong>Kelas 10 & 11 TPM:</strong> Rombel 1-4</li>
                                        <li>• <strong>Kelas 12 TPM:</strong> Rombel 1-5 ⭐</li>
                                        <li>• <strong>MPK (semua tingkat):</strong> Rombel 1-2</li>
                                        <li>• <strong>TKR, TL, DKV, APL:</strong> Rombel 1 saja</li>
                                    </ul>
                                </div>

                                <div>
                                    <p class="font-semibold text-blue-900 mb-1">Data yang Wajib Diisi:</p>
                                    <ul class="space-y-1 text-sm text-blue-800 ml-4">
                                        <li>• NIS (harus unik)</li>
                                        <li>• Nama Lengkap</li>
                                        <li>• Jenis Kelamin (L/P)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Download Template -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                                <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center mr-3 font-bold">1</span>
                                Download Template Excel
                            </h3>
                            <p class="text-sm text-gray-600 mb-3">Template sudah berisi kolom-kolom yang diperlukan dan contoh data</p>
                        </div>
                    </div>

                    <!-- Template Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">📄 Yang ada di template:</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs text-gray-600">
                            <div>✓ NIS</div>
                            <div>✓ NISN</div>
                            <div>✓ Nama</div>
                            <div>✓ Jenis Kelamin</div>
                            <div>✓ Tempat Lahir</div>
                            <div>✓ Tanggal Lahir</div>
                            <div>✓ Alamat</div>
                            <div>✓ Kelas</div>
                            <div>✓ No HP</div>
                            <div>✓ Email</div>
                            <div>✓ Nama Wali</div>
                            <div>✓ No HP Wali</div>
                        </div>
                    </div>

                    <!-- Contoh Format Kelas -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <p class="text-sm font-semibold text-blue-900 mb-2">💡 Contoh Format Kelas yang Benar:</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-xs">
                            <code class="bg-white px-2 py-1 rounded border border-blue-200">10 TPM 1</code>
                            <code class="bg-white px-2 py-1 rounded border border-blue-200">11 TPM 4</code>
                            <code class="bg-white px-2 py-1 rounded border border-green-200 font-bold">12 TPM 5</code>
                            <code class="bg-white px-2 py-1 rounded border border-blue-200">10 TKR 1</code>
                            <code class="bg-white px-2 py-1 rounded border border-blue-200">11 MPK 2</code>
                            <code class="bg-white px-2 py-1 rounded border border-blue-200">12 DKV 1</code>
                        </div>
                    </div>

                    <a
                        href="/api/students/template"
                        download
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all transform hover:scale-105 shadow-md hover:shadow-lg"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Template Excel
                    </a>
                </div>

                <!-- Upload File -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 flex items-center">
                        <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center mr-3 font-bold">2</span>
                        Upload File Excel
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Pilih file Excel yang sudah diisi dengan data siswa</p>
                    
                    <form @submit.prevent="submitExcelUpload">
                        <!-- File Upload Area -->
                        <div
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleFileDrop"
                            :class="[
                                'border-2 border-dashed rounded-lg p-8 text-center transition-colors',
                                isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'
                            ]"
                        >
                            <input
                                ref="fileInput"
                                type="file"
                                accept=".xlsx,.xls,.csv"
                                @change="handleFileSelect"
                                class="hidden"
                            />
                            
                            <div v-if="!selectedFile">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-gray-600 mb-2">Drag & drop file Excel di sini atau</p>
                                <button
                                    type="button"
                                    @click="$refs.fileInput.click()"
                                    class="text-blue-600 hover:text-blue-700 font-medium"
                                >
                                    Pilih File
                                </button>
                                <p class="text-xs text-gray-500 mt-2">Format: .xlsx, .xls, .csv (Max: 5MB)</p>
                            </div>

                            <div v-else class="space-y-3">
                                <div class="flex items-center justify-center space-x-3">
                                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="text-left">
                                        <p class="font-medium text-gray-900">{{ selectedFile.name }}</p>
                                        <p class="text-sm text-gray-500">{{ formatFileSize(selectedFile.size) }}</p>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="removeFile"
                                    class="text-red-600 hover:text-red-700 text-sm font-medium"
                                >
                                    Hapus File
                                </button>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        <div v-if="uploadErrors.length > 0" class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                            <h4 class="text-sm font-semibold text-red-900 mb-2">Terdapat kesalahan dalam file:</h4>
                            <ul class="space-y-1 text-sm text-red-800">
                                <li v-for="(error, index) in uploadErrors" :key="index">
                                    • Baris {{ error.row }}: {{ error.errors.join(', ') }}
                                </li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <button
                                type="button"
                                @click="removeFile"
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                Reset
                            </button>
                            <button
                                type="submit"
                                :disabled="!selectedFile || isUploading"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                            >
                                <svg v-if="isUploading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ isUploading ? 'Mengupload...' : 'Upload & Import Data' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

const activeTab = ref('manual');
const isSubmitting = ref(false);
const isUploading = ref(false);
const isDragging = ref(false);
const selectedFile = ref(null);
const uploadErrors = ref([]);
const errors = ref({});
const selectedTingkat = ref('');
const selectedJurusan = ref('');
const selectedRombel = ref('');

const availableRombel = computed(() => {
    if (!selectedJurusan.value) return [];
    const jurusan = selectedJurusan.value;
    const tingkat = selectedTingkat.value;
    if (jurusan === 'TPM') {
        return tingkat === '12' ? [1, 2, 3, 4, 5] : [1, 2, 3, 4];
    }
    if (jurusan === 'MPK') {
        return [1, 2];
    }
    return [1];
});

const updateKelas = () => {
    if (selectedTingkat.value && selectedJurusan.value && selectedRombel.value) {
        manualForm.kelas = `${selectedTingkat.value} ${selectedJurusan.value} ${selectedRombel.value}`;
    } else {
        manualForm.kelas = '';
    }
    if (selectedJurusan.value && selectedRombel.value) {
        if (!availableRombel.value.includes(parseInt(selectedRombel.value))) {
            selectedRombel.value = '';
        }
    }
};

const manualForm = reactive({
    nis: '',
    nisn: '',
    nama: '',
    jenis_kelamin: '',
    tempat_lahir: '',
    tanggal_lahir: '',
    alamat: '',
    kelas: '',
    no_hp: '',
    email: '',
    nama_wali: '',
    no_hp_wali: ''
});

const submitManualForm = () => {
    isSubmitting.value = true;
    errors.value = {};
    router.post('/students', manualForm, {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (formErrors) => {
            errors.value = formErrors;
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        selectedFile.value = file;
        uploadErrors.value = [];
    }
};

const handleFileDrop = (event) => {
    isDragging.value = false;
    const file = event.dataTransfer.files[0];
    if (file) {
        selectedFile.value = file;
        uploadErrors.value = [];
    }
};

const removeFile = () => {
    selectedFile.value = null;
    uploadErrors.value = [];
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const submitExcelUpload = () => {
    if (!selectedFile.value) return;
    
    isUploading.value = true;
    uploadErrors.value = [];
    
    const formData = new FormData();
    formData.append('file', selectedFile.value);
    
    router.post('/students/import-nominatif', formData, {
        preserveScroll: true,
        onSuccess: () => {
            selectedFile.value = null;
        },
        onError: (formErrors) => {
            if (formErrors.errors) {
                uploadErrors.value = formErrors.errors;
            }
        },
        onFinish: () => {
            isUploading.value = false;
        }
    });
};
</script>
