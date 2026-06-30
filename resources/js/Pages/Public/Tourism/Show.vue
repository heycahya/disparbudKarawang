<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    destination: Object
});
</script>

<template>
    <Head :title="destination.name" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <!-- Navigation bar -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-6">
                        <Link :href="route('public.home')" class="text-xl font-bold text-[#0F5E3D]">Disparbud Karawang</Link>
                        <Link :href="route('public.news.index')" class="text-sm font-medium hover:text-[#0F5E3D]">Berita</Link>
                        <Link :href="route('public.tourism.index')" class="text-sm font-medium text-[#0F5E3D]">Wisata</Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden p-8">
                <header class="mb-6">
                    <span v-if="destination.category" class="text-xs font-semibold text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 px-2.5 py-0.5 rounded-full">{{ destination.category.name }}</span>
                    <h1 class="text-3xl font-extrabold mt-2 mb-4 text-gray-900 dark:text-white">{{ destination.name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Lokasi: {{ destination.address }}</p>
                </header>

                <img v-if="destination.cover_image" :src="destination.cover_image" alt="cover" class="w-full h-96 object-cover rounded-lg mb-6">

                <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                    {{ destination.description }}
                </div>
            </article>
        </main>
    </div>
</template>
