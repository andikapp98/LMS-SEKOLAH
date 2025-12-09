<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-6 sm:py-12 px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-40 w-80 h-80 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-md w-full relative z-10">
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 backdrop-blur-sm bg-opacity-95">
                <div class="text-center">
                    <div class="mx-auto h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24 mb-3 sm:mb-4 flex items-center justify-center">
                        <img src="/images/logo.png" alt="Logo SMKS YASMU GRESIK" class="h-full w-auto object-contain">
                    </div>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                        SMKS YASMU GRESIK
                    </h2>
                    <p class="text-base sm:text-lg font-semibold text-indigo-600 mb-1">
                        Learning Management System
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500">
                        Masuk ke akun Anda
                    </p>
                </div>
                
                <form @submit.prevent="submit" class="mt-6 sm:mt-8 space-y-4 sm:space-y-6">
                    <div class="space-y-4 sm:space-y-5">
                        <div>
                            <label for="email" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                    class="pl-9 sm:pl-10 appearance-none block w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 ease-in-out text-sm sm:text-base"
                                    placeholder="nama@email.com"
                                />
                            </div>
                            <div v-if="form.errors.email" class="text-red-600 text-xs sm:text-sm mt-2 flex items-center">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ form.errors.email }}
                            </div>
                        </div>
                        
                        <div>
                            <label for="password" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    required
                                    class="pl-9 sm:pl-10 appearance-none block w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-300 rounded-lg placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 ease-in-out text-sm sm:text-base"
                                    placeholder="••••••••"
                                />
                            </div>
                            <div v-if="form.errors.password" class="text-red-600 text-xs sm:text-sm mt-2 flex items-center">
                                <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ form.errors.password }}
                            </div>
                        </div>
                    </div>

                    <div v-if="status" class="rounded-lg bg-red-50 border border-red-200 p-3 sm:p-4">
                        <div class="flex">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-red-400 mr-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <div class="text-xs sm:text-sm text-red-800">
                                {{ status }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="group relative w-full flex justify-center py-2.5 sm:py-3 px-4 border border-transparent text-sm sm:text-base font-semibold rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transform transition duration-150 ease-in-out hover:scale-105 active:scale-95 shadow-lg"
                        >
                            <span v-if="form.processing" class="flex items-center text-sm sm:text-base">
                                <svg class="animate-spin -ml-1 mr-2 sm:mr-3 h-4 w-4 sm:h-5 sm:w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                            <span v-else class="flex items-center text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Masuk
                            </span>
                        </button>
                    </div>
                </form>

                <div class="mt-4 sm:mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        © 2024 SMKS YASMU GRESIK. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>
@keyframes blob {
    0%, 100% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
