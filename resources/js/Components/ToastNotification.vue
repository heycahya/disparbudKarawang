<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('success'); // 'success' | 'error'
let timer = null;

function triggerToast(msg, toastType = 'success') {
    message.value = msg;
    type.value = toastType;
    show.value = true;

    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        show.value = false;
    }, 4000);
}

// Watch Inertia flash messages & errors
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            triggerToast(flash.success, 'success');
        } else if (flash?.error) {
            triggerToast(flash.error, 'error');
        }
    },
    { deep: true, immediate: true }
);

watch(
    () => page.props.errors,
    (errors) => {
        if (errors?.error) {
            triggerToast(errors.error, 'error');
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <Transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3.5 rounded-2xl shadow-2xl border text-xs font-bold backdrop-blur-md max-w-md w-full"
            :class="
                type === 'success'
                    ? 'bg-emerald-900/90 text-emerald-100 border-emerald-700/80 ring-1 ring-emerald-500/30'
                    : 'bg-rose-900/90 text-rose-100 border-rose-700/80 ring-1 ring-rose-500/30'
            "
        >
            <!-- Success Icon -->
            <div v-if="type === 'success'" class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Error Icon -->
            <div v-else class="w-7 h-7 rounded-xl bg-rose-500/20 text-rose-300 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            <!-- Message -->
            <div class="flex-1 leading-snug">
                <span>{{ message }}</span>
            </div>

            <!-- Close Button -->
            <button @click="show = false" class="text-white/60 hover:text-white transition p-1 font-bold text-base">&times;</button>
        </div>
    </Transition>
</template>
