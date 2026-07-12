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
        class="inline-flex items-center justify-center rounded-md border border-emerald-600 bg-white px-5 py-2.5 text-sm font-medium text-emerald-700 shadow-sm transition-colors duration-200 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 disabled:opacity-25 gap-2 dark:border-emerald-500 dark:bg-gray-800 dark:text-emerald-400 dark:hover:bg-gray-700"
    >
        <slot />
    </button>
</template>
