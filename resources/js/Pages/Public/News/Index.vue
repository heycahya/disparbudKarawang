<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/PublicNavbar.vue';
import FooterGlobal from '@/Components/FooterGlobal.vue';

defineProps({
    news: Object,
    categories: {
        type: Array,
        default: () => []
    },
    activeCategory: {
        type: String,
        default: 'all'
    }
});
</script>

<template>
    <Head title="Warta & Berita - Disparbud Karawang" />

    <div class="min-h-screen bg-rice-husk dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex flex-col font-sans">
        <PublicNavbar />

        <header class="bg-gradient-to-r from-[#0F5E3D] via-[#0C4E5B] to-emerald-950 py-16 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#D97706] bg-white/10 backdrop-blur-md px-3.5 py-1 rounded-full border border-white/20">
                    Kabar Terkini
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold mt-3 tracking-tight">Portal Berita & Pengumuman</h1>
                <p class="text-emerald-100/90 max-w-2xl mt-2 text-sm sm:text-base">
                    Informasi resmi seputar agenda kebudayaan, pengembangan objek wisata, regulasi dinas, dan pengumuman publik.
                </p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 w-full">

            <!-- Category Filter Bar if categories exist -->
            <div v-if="categories?.length" class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800 pb-6">
                <Link 
                    :href="route('public.news.index', { category: 'all' })"
                    class="px-5 py-2 text-sm font-semibold transition duration-200 border"
                    :class="activeCategory === 'all' 
                        ? 'bg-[#0F5E3D] text-white border-[#0F5E3D] rounded-tr-xl rounded-bl-xl shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 rounded-tr-xl rounded-bl-xl'"
                >
                    Semua Berita
                </Link>

                <Link 
                    v-for="cat in categories" 
                    :key="cat.id"
                    :href="route('public.news.index', { category: cat.slug })"
                    class="px-5 py-2 text-sm font-semibold transition duration-200 border"
                    :class="activeCategory === cat.slug 
                        ? 'bg-[#0F5E3D] text-white border-[#0F5E3D] rounded-tr-xl rounded-bl-xl shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 rounded-tr-xl rounded-bl-xl'"
                >
                    {{ cat.name }}
                </Link>
            </div>

            <!-- News Grid -->
            <div v-if="news?.data?.length" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div 
                    v-for="item in news.data" 
                    :key="item.id" 
                    class="group bg-white dark:bg-gray-900 rounded-tr-[2.5rem] rounded-bl-[2.5rem] rounded-tl-lg rounded-br-lg shadow-sm hover:shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between"
                >
                    <div>
                        <div class="relative overflow-hidden h-52 bg-slate-200 dark:bg-gray-800">
                            <img 
                                v-if="item.thumbnail" 
                                :src="item.thumbnail" 
                                :alt="item.title" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                Sampul Berita
                            </div>
                        </div>

                        <div class="p-6 space-y-3">
                            <span v-if="item.category" class="text-xs font-bold text-teal-700 bg-teal-50 dark:bg-teal-950 dark:text-teal-300 px-2.5 py-0.5 rounded-full">
                                {{ item.category.name }}
                            </span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-[#0F5E3D] transition">
                                <Link :href="route('public.news.show', item.slug)">{{ item.title }}</Link>
                            </h3>
                            <div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed" v-html="item.content"></div>
                        </div>
                    </div>

                    <div class="p-6 pt-0">
                        <Link 
                            :href="route('public.news.show', item.slug)" 
                            class="text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline"
                        >
                            Baca Selengkapnya &rarr;
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white dark:bg-gray-900 rounded-tr-2xl rounded-bl-2xl p-12 text-center border border-gray-100 dark:border-gray-800 shadow-sm">
                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada berita yang diterbitkan pada kategori ini.</p>
            </div>

            <!-- Pagination links -->
            <div v-if="news?.links?.length > 3" class="flex justify-center space-x-2 mt-8">
                <div v-for="link in news.links" :key="link.label">
                    <Link 
                        v-if="link.url" 
                        :href="link.url" 
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

        <FooterGlobal />
    </div>
</template>
