<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-karawang-emerald/10 hover:text-karawang-emerald focus:bg-karawang-emerald/10 focus:outline-none dark:text-gray-300 dark:hover:bg-emerald-900/40 dark:hover:text-emerald-300 dark:focus:bg-emerald-900/40"
    >
        <slot />
    </Link>
</template>
