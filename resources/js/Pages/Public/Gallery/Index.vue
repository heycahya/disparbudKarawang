<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    galleries: Object,
    categories: {
        type: Array,
        default: () => ['wisata', 'budaya', 'ekraf', 'event', 'lainnya']
    },
    activeCategory: {
        type: String,
        default: 'all'
    }
});

const selectedImage = ref(null);

const getCategoryLabel = (cat) => {
    switch (cat) {
        case 'wisata': return 'Wisata';
        case 'budaya': return 'Kebudayaan';
        case 'ekraf': return 'Ekonomi Kreatif';
        case 'event': return 'Acara & Festival';
        case 'lainnya': return 'Lainnya';
        default: return cat;
    }
};
</script>

<template>
    <Head title="Galeri Dokumentasi - Disparbud Karawang" />

    <PublicLayout>

        <header class="bg-gradient-to-r from-[#0F5E3D] via-[#0C4E5B] to-emerald-950 py-16 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#D97706] bg-white/10 backdrop-blur-md px-3.5 py-1 rounded-full border border-white/20">
                    Dokumentasi Visual
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold mt-3 tracking-tight">Galeri Foto Pariwisata & Kebudayaan</h1>
                <p class="text-emerald-100/90 max-w-2xl mt-2 text-sm sm:text-base">
                    Koleksi potret keindahan keanekaragaman destinasi wisata, atraksi budaya, event daerah, dan produk ekonomi kreatif Karawang.
                </p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 w-full">
            
            <!-- Category Filter Pills -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800 pb-6">
                <a 
                    :href="route('public.gallery.index', { category: 'all' })"
                    rel="external"
                    class="px-5 py-2 text-sm font-semibold transition duration-200 border"
                    :class="activeCategory === 'all' 
                        ? 'bg-[#0F5E3D] text-white border-[#0F5E3D] rounded-md shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 rounded-md'"
                >
                    Semua Foto
                </a>

                <a 
                    v-for="cat in categories" 
                    :key="cat"
                    :href="route('public.gallery.index', { category: cat })"
                    rel="external"
                    class="px-5 py-2 text-sm font-semibold transition duration-200 border"
                    :class="activeCategory === cat 
                        ? 'bg-[#0F5E3D] text-white border-[#0F5E3D] rounded-md shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 rounded-md'"
                >
                    {{ getCategoryLabel(cat) }}
                </a>
            </div>

            <!-- Image Grid -->
            <div v-if="galleries?.data?.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="item in galleries.data" 
                    :key="item.id"
                    @click="selectedImage = item"
                    class="group bg-white dark:bg-gray-900 rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden cursor-pointer transition-all duration-300 transform hover:-translate-y-1"
                >
                    <div class="relative overflow-hidden h-64 bg-slate-200 dark:bg-gray-800">
                        <img 
                            :src="item.photo" 
                            :alt="item.title" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 text-white">
                            <span class="text-xs font-bold text-amber-300 uppercase tracking-wider mb-1">
                                {{ getCategoryLabel(item.category) }}
                            </span>
                            <h3 class="text-base font-bold">{{ item.title }}</h3>
                        </div>
                    </div>
                    <div class="p-4 flex items-center justify-between border-t border-gray-100 dark:border-gray-800">
                        <span class="text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-2.5 py-0.5 rounded-full">
                            {{ getCategoryLabel(item.category) }}
                        </span>
                        <span class="text-xs text-gray-500 font-medium">🔍 Perbesar</span>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white dark:bg-gray-900 rounded-2xl p-12 text-center border border-gray-100 dark:border-gray-800 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada koleksi foto untuk kategori ini.</p>
            </div>

            <!-- Pagination Links -->
            <div v-if="galleries?.links?.length > 3" class="flex justify-center space-x-2 mt-8">
                <div v-for="link in galleries.links" :key="link.label">
                    <a 
                        v-if="link.url" 
                        :href="link.url" 
                        rel="external"
                        class="px-4 py-2 border rounded-lg text-sm font-semibold transition"
                        :class="link.active 
                            ? 'bg-[#0F5E3D] text-white border-[#0F5E3D]' 
                            : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100'"
                        v-html="link.label"
                    />
                    <span 
                        v-else 
                        class="px-4 py-2 border rounded-lg text-sm text-gray-400 dark:text-gray-600 bg-gray-100 dark:bg-gray-900 cursor-not-allowed" 
                        v-html="link.label"
                    />
                </div>
            </div>

        </main>

        <!-- Lightbox Modal -->
        <div 
            v-if="selectedImage" 
            class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4"
            @click.self="selectedImage = null"
        >
            <div class="relative bg-white dark:bg-gray-900 max-w-3xl w-full rounded-2xl overflow-hidden shadow-2xl border border-white/20">
                <button 
                    @click="selectedImage = null" 
                    class="absolute top-4 right-4 z-10 w-10 h-10 bg-black/60 text-white rounded-full flex items-center justify-center text-lg font-bold hover:bg-black/80 transition"
                >
                    ✕
                </button>
                <img :src="selectedImage.photo" :alt="selectedImage.title" class="w-full max-h-[70vh] object-contain bg-black">
                <div class="p-6 space-y-2">
                    <span class="text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-2.5 py-0.5 rounded-full">
                        {{ getCategoryLabel(selectedImage.category) }}
                    </span>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ selectedImage.title }}</h3>
                </div>
            </div>
        </div>

    </PublicLayout>
</template>
