<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    news: Object
});
</script>

<template>
    <Head title="Katalog Berita" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <!-- Navigation bar -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-6">
                        <Link :href="route('public.home')" class="text-xl font-bold text-[#0F5E3D]">Disparbud Karawang</Link>
                        <Link :href="route('public.news.index')" class="text-sm font-medium text-[#0F5E3D]">Berita</Link>
                        <Link :href="route('public.tourism.index')" class="text-sm font-medium hover:text-[#0F5E3D]">Wisata</Link>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-gray-200">Katalog Berita</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div v-for="item in news.data" :key="item.id" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
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

            <!-- Pagination links -->
            <div class="flex justify-center space-x-2 mt-8">
                <div v-for="link in news.links" :key="link.label">
                    <Link 
                        v-if="link.url" 
                        :href="link.url" 
                        class="px-4 py-2 border rounded-lg text-sm transition"
                        :class="link.active ? 'bg-[#0F5E3D] text-white border-[#0F5E3D]' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                        v-html="link.label"
                    />
                    <span 
                        v-else 
                        class="px-4 py-2 border rounded-lg text-sm text-gray-400 dark:text-gray-600 cursor-not-allowed" 
                        v-html="link.label"
                    />
                </div>
            </div>
        </main>
    </div>
</template>
