<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-2"
        >
            <div
                v-if="show"
                class="fixed top-4 right-4 z-50 max-w-sm w-full"
            >
                <div class="bg-white rounded-lg shadow-lg border-l-4" :class="borderClass">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg v-if="type === 'success'" class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <svg v-else-if="type === 'error'" class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <svg v-else-if="type === 'warning'" class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <svg v-else class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1">
                                <p class="text-sm font-medium" :class="titleClass">
                                    {{ title }}
                                </p>
                                <p v-if="message" class="mt-1 text-sm text-gray-500">
                                    {{ message }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    @click="close"
                                    class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'info', // success, error, warning, info
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    title: {
        type: String,
        required: true
    },
    message: {
        type: String,
        default: ''
    },
    duration: {
        type: Number,
        default: 5000 // Auto close after 5 seconds
    }
});

const emit = defineEmits(['close']);

let timeout = null;

const close = () => {
    emit('close');
};

const borderClass = computed(() => {
    return {
        'success': 'border-green-400',
        'error': 'border-red-400',
        'warning': 'border-yellow-400',
        'info': 'border-blue-400'
    }[props.type];
});

const titleClass = computed(() => {
    return {
        'success': 'text-green-900',
        'error': 'text-red-900',
        'warning': 'text-yellow-900',
        'info': 'text-blue-900'
    }[props.type];
});

watch(() => props.show, (newValue) => {
    if (newValue && props.duration > 0) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            close();
        }, props.duration);
    }
});
</script>
