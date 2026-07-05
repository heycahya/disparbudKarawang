<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-asymmetric-sm border border-karawang-emerald bg-white px-6 py-3 text-xs font-semibold uppercase tracking-widest text-karawang-emerald shadow-sm transition duration-200 ease-in-out hover:bg-rice-husk focus:outline-none focus:ring-2 focus:ring-karawang-emerald/50 disabled:opacity-25 dark:border-emerald-500 dark:bg-gray-800 dark:text-emerald-400 dark:hover:bg-gray-700"
    >
        <slot />
    </button>
</template>
