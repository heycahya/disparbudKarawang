<script setup>
defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    icon: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <div class="flex flex-col items-center justify-center px-6 py-3.5 border border-sanggabuana-hairline rounded-lg bg-white min-w-[130px]">
        <span class="text-2xl font-bold text-sanggabuana tracking-tight">
            {{ (typeof value === 'number') ? value.toLocaleString('id-ID') : value }}
        </span>
        <span class="text-xs text-sanggabuana-muted mt-1 text-center font-medium">
            {{ label }}
        </span>
    </div>
</template>
