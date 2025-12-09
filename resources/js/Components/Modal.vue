<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0"
                @click="closeOnBackdrop && close()"
            >
                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-900 opacity-75"></div>

                <!-- Modal Content -->
                <div
                    class="relative min-h-screen flex items-center justify-center"
                    @click.stop
                >
                    <Transition
                        enter-active-class="transition ease-out duration-200"
                        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-active-class="transition ease-in duration-150"
                        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <div
                            v-if="show"
                            class="bg-white rounded-lg shadow-xl transform transition-all w-full mx-auto"
                            :class="maxWidthClass"
                        >
                            <slot />
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    maxWidth: {
        type: String,
        default: 'md'
    },
    closeOnBackdrop: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

const maxWidthClass = computed(() => {
    return {
        'sm': 'max-w-[calc(100%-2rem)] sm:max-w-sm',
        'md': 'max-w-[calc(100%-2rem)] sm:max-w-md',
        'lg': 'max-w-[calc(100%-2rem)] sm:max-w-lg',
        'xl': 'max-w-[calc(100%-2rem)] sm:max-w-xl',
        '2xl': 'max-w-[calc(100%-2rem)] sm:max-w-2xl'
    }[props.maxWidth];
});

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));
</script>
