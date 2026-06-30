<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Dashboard Publik" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard Publik
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 border-l-4 border-[#0F5E3D]">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-2xl font-bold mb-4">Selamat datang, <span class="text-[#0F5E3D]">{{ user.name }}</span>!</h1>
                        <p class="mb-6">Selamat datang kembali di portal layanan pariwisata dan budaya Disparbud Kabupaten Karawang.</p>
                        
                        <div class="mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-[#0F5E3D]/10 text-[#0F5E3D] mb-4">
                                Role: {{ user.role }}
                            </span>
                        </div>

                        <div class="mt-6 border-t pt-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Menu Layanan (Service Rakyat)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-900 hover:border-[#0F5E3D] transition-colors">
                                    <h4 class="font-bold text-[#0F5E3D] mb-2">Laporan Pengaduan</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Laporkan keluhan atau masukan terkait destinasi wisata dan budaya Karawang.</p>
                                </div>
                                <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-900 hover:border-[#0F5E3D] transition-colors">
                                    <h4 class="font-bold text-[#0F5E3D] mb-2">Pengajuan Wisata Baru</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Ajukan objek wisata baru di Karawang untuk dipublikasikan di katalog.</p>
                                </div>
                                <div class="p-4 border rounded-lg bg-gray-50 dark:bg-gray-900 hover:border-[#0F5E3D] transition-colors">
                                    <h4 class="font-bold text-[#0F5E3D] mb-2">Pengajuan Event Broadcasting</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Ajukan peliputan atau publikasi event instansi/organisasi Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
