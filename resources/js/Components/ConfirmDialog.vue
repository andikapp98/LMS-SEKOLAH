<template>
    <Modal :show="show" @close="cancel" max-width="md" :close-on-backdrop="false">
        <div class="p-6">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full border-2" :class="[iconBgClass, iconBorderClass]">
                <svg class="h-6 w-6" :class="iconColorClass" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path v-if="type === 'danger'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    <path v-else-if="type === 'warning'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Title -->
            <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ title }}
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500">
                        {{ message }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                <button
                    type="button"
                    @click="confirm"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:col-start-2 sm:text-sm transition-colors"
                    :class="confirmButtonClass"
                >
                    {{ confirmText }}
                </button>
                <button
                    type="button"
                    @click="cancel"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm transition-colors"
                >
                    {{ cancelText }}
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { computed } from 'vue';
import Modal from './Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'danger', // danger, warning, info
        validator: (value) => ['danger', 'warning', 'info'].includes(value)
    },
    title: {
        type: String,
        default: 'Konfirmasi'
    },
    message: {
        type: String,
        required: true
    },
    confirmText: {
        type: String,
        default: 'Hapus'
    },
    cancelText: {
        type: String,
        default: 'Batal'
    }
});

const emit = defineEmits(['confirm', 'cancel']);

const confirm = () => {
    emit('confirm');
};

const cancel = () => {
    emit('cancel');
};

const iconBgClass = computed(() => {
    return {
        'danger': 'bg-red-50',
        'warning': 'bg-yellow-50',
        'info': 'bg-blue-50'
    }[props.type];
});

const iconBorderClass = computed(() => {
    return {
        'danger': 'border-red-200',
        'warning': 'border-yellow-200',
        'info': 'border-blue-200'
    }[props.type];
});

const iconColorClass = computed(() => {
    return {
        'danger': 'text-red-600',
        'warning': 'text-yellow-600',
        'info': 'text-blue-600'
    }[props.type];
});

const confirmButtonClass = computed(() => {
    return {
        'danger': 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
        'warning': 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
        'info': 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
    }[props.type];
});
</script>
