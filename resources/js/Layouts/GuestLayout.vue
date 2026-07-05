<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-rice-husk p-4 sm:p-6 dark:bg-slate-900"
    >
        <div class="mb-4">
            <Link href="/" class="flex flex-col items-center space-y-2">
                <ApplicationLogo class="h-16 w-16 fill-karawang-emerald dark:fill-emerald-400" />
                <span class="text-xl font-extrabold tracking-tight text-sanggabuana-slate dark:text-white">
                    Disparbud <span class="text-karawang-emerald dark:text-emerald-400">Karawang</span>
                </span>
            </Link>
        </div>

        <div
            class="w-full overflow-hidden bg-white px-8 py-6 shadow-xl border border-slate-200/80 rounded-asymmetric sm:max-w-md dark:bg-slate-800 dark:border-slate-700"
        >
            <slot />
        </div>
    </div>
</template>
