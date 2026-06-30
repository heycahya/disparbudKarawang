<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Manajemen Akun" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Manajemen Akun
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 border-l-4 border-[#0F5E3D]">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-2xl font-bold mb-4 text-[#0F5E3D]">Manajemen Akun Pengelola</h1>
                        <p class="mb-4">Halaman khusus Super Admin untuk mengelola akun internal (Admin & Super Admin).</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
