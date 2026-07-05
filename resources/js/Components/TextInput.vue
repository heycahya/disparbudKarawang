<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-asymmetric-sm border-gray-300 shadow-sm focus:border-karawang-emerald focus:ring-karawang-emerald dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-emerald-500 dark:focus:ring-emerald-500"
        v-model="model"
        ref="input"
    />
</template>
