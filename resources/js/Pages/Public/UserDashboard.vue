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

        <div class="py-12 bg-rice-husk min-h-[calc(100vh-8rem)] dark:bg-gray-900">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <div class="overflow-hidden bg-white shadow-xl rounded-asymmetric dark:bg-gray-800 border-l-8 border-[#0F5E3D]">
                    <div class="p-8 text-gray-900 dark:text-gray-100 space-y-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-6">
                            <div>
                                <h1 class="text-3xl font-extrabold tracking-tight">Selamat datang, <span class="text-[#0F5E3D] dark:text-emerald-400">{{ user.name }}</span>!</h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Portal layanan mandiri masyarakat untuk pariwisata dan kebudayaan Karawang.</p>
                            </div>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-karawang-emerald/10 text-karawang-emerald border border-karawang-emerald/20 dark:bg-emerald-950 dark:text-emerald-300">
                                Status Akun: {{ user.role }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                            <Link :href="route('public.service.complaint')" class="p-6 border border-slate-200/80 rounded-asymmetric-sm bg-white dark:bg-gray-900 hover:border-karawang-emerald shadow-sm hover:shadow-md transition-all duration-300 group">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-[#0F5E3D] dark:text-emerald-400 flex items-center justify-center font-bold text-lg mb-3 group-hover:scale-110 transition-transform">
                                    📢
                                </div>
                                <h4 class="font-bold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Laporan Pengaduan</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Laporkan keluhan atau masukan terkait destinasi wisata dan budaya Karawang.</p>
                            </Link>

                            <Link :href="route('public.service.tourism-submission')" class="p-6 border border-slate-200/80 rounded-asymmetric-sm bg-white dark:bg-gray-900 hover:border-karawang-emerald shadow-sm hover:shadow-md transition-all duration-300 group">
                                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950 text-[#D97706] dark:text-amber-400 flex items-center justify-center font-bold text-lg mb-3 group-hover:scale-110 transition-transform">
                                    🗺️
                                </div>
                                <h4 class="font-bold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Pengajuan Wisata Baru</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Ajukan objek wisata baru di Karawang untuk dipublikasikan di katalog.</p>
                            </Link>

                            <Link :href="route('public.service.event-broadcast')" class="p-6 border border-slate-200/80 rounded-asymmetric-sm bg-white dark:bg-gray-900 hover:border-karawang-emerald shadow-sm hover:shadow-md transition-all duration-300 group">
                                <div class="w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-950 text-[#0C4E5B] dark:text-teal-400 flex items-center justify-center font-bold text-lg mb-3 group-hover:scale-110 transition-transform">
                                    🎉
                                </div>
                                <h4 class="font-bold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Pengajuan Event Broadcasting</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Ajukan peliputan atau publikasi event instansi/organisasi Anda.</p>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
