<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 border-l-4 border-[#0F5E3D]">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-2xl font-bold mb-4">Selamat datang, <span class="text-[#0F5E3D]">{{ user.name }}</span>!</h1>
                        <p class="mb-4">Anda masuk ke panel administrasi resmi Disparbud Kabupaten Karawang.</p>
                        
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-[#0F5E3D]/10 text-[#0F5E3D]">
                            Role: {{ user.role }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
