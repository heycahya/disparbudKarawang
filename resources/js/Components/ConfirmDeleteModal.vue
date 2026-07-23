<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Konfirmasi Hapus Data'
    },
    itemName: {
        type: String,
        default: ''
    },
    processing: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-slate-200 dark:border-slate-800 text-center font-sans">
                    
                    <!-- Warning Icon -->
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center border border-rose-200 dark:border-rose-900/50">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <!-- Title & Warning Text -->
                    <div class="space-y-1">
                        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ title }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Apakah Anda yakin ingin menghapus data
                            <strong v-if="itemName" class="text-slate-800 dark:text-slate-200">"{{ itemName }}"</strong>?
                            Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-center gap-3 pt-2">
                        <button
                            type="button"
                            @click="emit('close')"
                            class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            @click="emit('confirm')"
                            :disabled="processing"
                            class="px-6 py-2.5 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 disabled:opacity-50 rounded-xl transition shadow-md shadow-rose-600/20"
                        >
                            {{ processing ? 'Menghapus...' : 'Ya, Hapus Data' }}
                        </button>
                    </div>

                </div>
            </div>
        </Transition>
    </Teleport>
</template>
