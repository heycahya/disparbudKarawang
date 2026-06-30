<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    news: Array,
    tourism: Array
});
</script>

<template>
    <Head title="Beranda Portal Publik" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <!-- Navigation bar -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-6">
                        <Link :href="route('public.home')" class="text-xl font-bold text-[#0F5E3D]">Disparbud Karawang</Link>
                        <Link :href="route('public.news.index')" class="text-sm font-medium hover:text-[#0F5E3D]">Berita</Link>
                        <Link :href="route('public.tourism.index')" class="text-sm font-medium hover:text-[#0F5E3D]">Wisata</Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <Link :href="route('dashboard')" class="text-sm font-medium hover:text-[#0F5E3D]">Dashboard</Link>
                        </template>
                        <template v-else>
                            <Link :href="route('login')" class="text-sm font-medium hover:text-[#0F5E3D]">Masuk</Link>
                            <Link :href="route('register')" class="text-sm font-medium hover:text-[#0F5E3D]">Daftar</Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Banner -->
        <header class="bg-gradient-to-r from-[#0F5E3D] to-emerald-700 py-20 text-white text-center">
            <div class="max-w-4xl mx-auto px-4">
                <h1 class="text-4xl font-extrabold sm:text-5xl mb-4">Portal Informasi Pariwisata & Budaya</h1>
                <p class="text-xl opacity-90 mb-8">Kabupaten Karawang</p>
                <div class="flex justify-center space-x-4">
                    <Link :href="route('public.tourism.index')" class="px-6 py-3 bg-white text-[#0F5E3D] font-bold rounded-lg shadow hover:bg-gray-100 transition">Jelajahi Wisata</Link>
                    <Link :href="route('public.news.index')" class="px-6 py-3 bg-emerald-800 text-white font-bold rounded-lg shadow hover:bg-emerald-900 transition">Baca Berita</Link>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- News Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200 border-b pb-2 border-[#0F5E3D]">Berita Terbaru</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="item in news" :key="item.id" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <img v-if="item.thumbnail" :src="item.thumbnail" alt="thumbnail" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <span v-if="item.category" class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-0.5 rounded-full">{{ item.category.name }}</span>
                            <h3 class="text-lg font-bold mt-2 mb-2 line-clamp-2">
                                <Link :href="route('public.news.show', item.slug)" class="hover:text-[#0F5E3D]">{{ item.title }}</Link>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3" v-html="item.content"></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tourism Section -->
            <section>
                <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200 border-b pb-2 border-[#0F5E3D]">Destinasi Wisata Pilihan</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="item in tourism" :key="item.id" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                        <img v-if="item.cover_image" :src="item.cover_image" alt="cover" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <span v-if="item.category" class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-0.5 rounded-full">{{ item.category.name }}</span>
                            <h3 class="text-lg font-bold mt-2 mb-2 line-clamp-2">
                                <Link :href="route('public.tourism.show', item.slug)" class="hover:text-[#0F5E3D]">{{ item.name }}</Link>
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 font-medium">{{ item.address }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">{{ item.description }}</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
