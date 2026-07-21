<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import PublicNavbar from '@/Components/PublicNavbar.vue';
import FooterGlobal from '@/Components/FooterGlobal.vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const successMessage = ref(null);
const errorMessage = ref(null);

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            successMessage.value = flash.success;
            setTimeout(() => {
                successMessage.value = null;
            }, 5000);
        }
        if (flash?.error) {
            errorMessage.value = flash.error;
            setTimeout(() => {
                errorMessage.value = null;
            }, 5000);
        }
    },
    { immediate: true, deep: true }
);


</script>

<template>
    <div class="min-h-screen bg-rice-husk dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex flex-col font-sans">
        <!-- Navigation Bar -->
        <PublicNavbar />

        <!-- Page Heading -->
        <header
            class="bg-white shadow dark:bg-gray-800 border-b border-emerald-900/10 dark:border-teal-800/40"
            v-if="$slots.header"
        >
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <FooterGlobal />

        <!-- Global Floating Toasts -->
        <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2">
            <!-- Success Toast -->
            <div v-if="successMessage" class="flex items-center p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 border-l-4 border-emerald-500 transition-all duration-300" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg dark:bg-emerald-800 dark:text-emerald-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Success icon</span>
                </div>
                <div class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-100 pr-2">{{ successMessage }}</div>
                <button @click="successMessage = null" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            <!-- Error Toast -->
            <div v-if="errorMessage" class="flex items-center p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 border-l-4 border-red-500 transition-all duration-300" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm1.25 11.5a1.25 1.25 0 1 1-2.5 0v-5a1.25 1.25 0 1 1 2.5 0v5Zm0-8a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Z"/>
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-100 pr-2">{{ errorMessage }}</div>
                <button @click="errorMessage = null" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
