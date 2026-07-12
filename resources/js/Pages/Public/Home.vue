<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/PublicNavbar.vue';
import LeafletMap from '@/Components/LeafletMap.vue';
import FooterGlobal from '@/Components/FooterGlobal.vue';

const props = defineProps({
    hero_stats: {
        type: Object,
        default: () => ({ destinations: 0, news: 0, cultures: 0, ekraf: 0 })
    },
    latest_news: {
        type: Array,
        default: () => []
    },
    featured_destinations: {
        type: Array,
        default: () => []
    },
    destinations: {
        type: Array,
        default: () => []
    },
    // Backward compatibility props
    news: Array,
    tourism: Array
});

const displayNews = props.latest_news?.length ? props.latest_news : (props.news || []);
const displayTourism = props.featured_destinations?.length ? props.featured_destinations : (props.tourism || []);
</script>

<template>
    <Head title="Beranda - Portal Resmi Disparbud Karawang" />

    <div class="min-h-screen bg-rice-husk dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex flex-col font-sans">
        <!-- Navigation Bar -->
        <PublicNavbar />

        <!-- Hero Section with Glassmorphism & Golok Lubuk Karawang Cut -->
        <section class="relative bg-gradient-to-br from-[#0F5E3D] via-[#0C4E5B] to-emerald-950 text-white overflow-hidden py-24 sm:py-32">
            <!-- Background Decorative Pattern -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Text Content -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase border border-white/20 text-[#D97706]">
                            <span>🌾 Persona Karawang Pangkal Perjuangan</span>
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                            Jelajahi Pesona <br />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-emerald-200 to-teal-100">
                                Wisata & Kebudayaan
                            </span>
                        </h1>
                        <p class="text-lg text-emerald-100/90 max-w-2xl font-normal leading-relaxed">
                            Selamat datang di Portal Resmi Dinas Pariwisata dan Kebudayaan Kabupaten Karawang. Temukan destinasi alam memukau, jejak sejarah luhur, seni tradisi, dan ragam ekonomi kreatif.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <Link 
                                :href="route('public.destinasi')" 
                                class="px-8 py-4 bg-[#D97706] hover:bg-amber-600 text-white font-bold rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-md rounded-br-md shadow-lg hover:shadow-amber-500/30 transition-all duration-300 transform hover:-translate-y-0.5"
                            >
                                Jelajahi Destinasi
                            </Link>
                            <Link 
                                :href="route('public.profile')" 
                                class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold backdrop-blur-md rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-md rounded-br-md border border-white/30 transition-all duration-300"
                            >
                                Profil Dinas
                            </Link>
                        </div>
                    </div>

                    <!-- Glassmorphism Featured Card Overlay -->
                    <div class="lg:col-span-5">
                        <div class="bg-white/15 dark:bg-black/30 backdrop-blur-xl p-8 rounded-tr-[3.5rem] rounded-bl-[3.5rem] rounded-tl-xl rounded-br-xl border border-white/20 shadow-2xl relative">
                            <div class="absolute -top-3 -right-3 w-12 h-12 bg-[#D97706] rounded-full flex items-center justify-center font-bold text-lg shadow-lg">
                                ✨
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-white">Karawang Emerald Portal</h3>
                            <p class="text-sm text-emerald-100/90 mb-6 leading-relaxed">
                                Nikmati integrasi data informasi spasial pariwisata terpadu, sejarah cagar budaya, layanan masyarakat, hingga pendaftaran kegiatan kebudayaan.
                            </p>

                            <!-- Mini Badge highlights -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/10">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/30 flex items-center justify-center text-emerald-300">🏛️</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white">Situs Percandian Batujaya</h4>
                                        <p class="text-[11px] text-emerald-100/70">Peninggalan Tarumanagara abad 2-6 M</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/10">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/30 flex items-center justify-center text-amber-300">🌊</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white">Wisata Bahari & Mangrove</h4>
                                        <p class="text-[11px] text-emerald-100/70">Pantai Tangkolak & Tanjung Pakis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Counter Stats Section (Glassmorphism Floating Bar) -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 w-full">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <!-- Stat 1 -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-6 rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-lg rounded-br-lg shadow-xl border border-emerald-900/10 dark:border-emerald-500/10 transition-transform duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Destinasi Wisata</p>
                            <h3 class="text-3xl font-black text-[#0F5E3D] dark:text-emerald-400 mt-1">{{ hero_stats.destinations || displayTourism.length || 13 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950 text-[#0F5E3D] rounded-full flex items-center justify-center text-xl font-bold">
                            🏔️
                        </div>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-6 rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-lg rounded-br-lg shadow-xl border border-emerald-900/10 dark:border-emerald-500/10 transition-transform duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Warta & Berita</p>
                            <h3 class="text-3xl font-black text-[#0C4E5B] dark:text-teal-400 mt-1">{{ hero_stats.news || displayNews.length || 5 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-teal-100 dark:bg-teal-950 text-[#0C4E5B] rounded-full flex items-center justify-center text-xl font-bold">
                            📰
                        </div>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-6 rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-lg rounded-br-lg shadow-xl border border-emerald-900/10 dark:border-emerald-500/10 transition-transform duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Seni & Budaya</p>
                            <h3 class="text-3xl font-black text-[#D97706] dark:text-amber-400 mt-1">{{ hero_stats.cultures || 8 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-950 text-[#D97706] rounded-full flex items-center justify-center text-xl font-bold">
                            🎭
                        </div>
                    </div>
                </div>

                <!-- Stat 4 -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md p-6 rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-lg rounded-br-lg shadow-xl border border-emerald-900/10 dark:border-emerald-500/10 transition-transform duration-300 hover:-translate-y-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ekonomi Kreatif</p>
                            <h3 class="text-3xl font-black text-[#0F5E3D] dark:text-emerald-400 mt-1">{{ hero_stats.ekraf || 12 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950 text-[#0F5E3D] rounded-full flex items-center justify-center text-xl font-bold">
                            🎨
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Body Sections -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-20 w-full">

            <!-- Interactive Leaflet.js Spatial Map Section -->
            <section class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-wider text-[#D97706]">Peta Spasial Interaktif</span>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Pemetaan Objek Wisata Karawang</h2>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md">
                        Eksplorasi persebaran geografis destinasi sejarah, alam, religi, dan objek vital pariwisata di seluruh wilayah Kabupaten Karawang.
                    </p>
                </div>

                <!-- Leaflet Map Container -->
                <LeafletMap :destinations="destinations" height="480px" />
            </section>

            <!-- Featured Destinations Grid -->
            <section class="space-y-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-wider text-[#0F5E3D]">Rekomendasi Utama</span>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Destinasi Wisata Unggulan</h2>
                    </div>
                    <Link 
                        :href="route('public.destinasi')" 
                        class="text-sm font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline inline-flex items-center gap-1"
                    >
                        Lihat Seluruh Direktori &rarr;
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div 
                        v-for="item in displayTourism" 
                        :key="item.id" 
                        class="group bg-white dark:bg-gray-900 rounded-tr-[2.5rem] rounded-bl-[2.5rem] rounded-tl-lg rounded-br-lg shadow-sm hover:shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 transform hover:-translate-y-1"
                    >
                        <div class="relative overflow-hidden h-52 bg-slate-200 dark:bg-gray-800">
                            <img 
                                v-if="item.cover_image" 
                                :src="item.cover_image" 
                                :alt="item.name" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                            >
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400 font-medium text-sm">
                                Foto Destinasi
                            </div>
                            <span v-if="item.category" class="absolute top-4 left-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-[#0F5E3D] dark:text-emerald-400 text-xs font-bold px-3 py-1 rounded-full shadow">
                                {{ item.category.name }}
                            </span>
                        </div>

                        <div class="p-6 space-y-3">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-[#0F5E3D] transition">
                                <Link :href="route('public.tourism.show', item.slug)">{{ item.name }}</Link>
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1">
                                📍 {{ item.address }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed">
                                {{ item.description }}
                            </p>
                            <div class="pt-2">
                                <Link 
                                    :href="route('public.tourism.show', item.slug)" 
                                    class="inline-flex items-center text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:text-[#0C4E5B]"
                                >
                                    Eksplorasi Detail &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Latest News Section -->
            <section class="space-y-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-wider text-[#0C4E5B]">Kabar Informasi</span>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Berita & Pengumuman Terbaru</h2>
                    </div>
                    <Link 
                        :href="route('public.news.index')" 
                        class="text-sm font-bold text-[#0C4E5B] dark:text-teal-400 hover:underline inline-flex items-center gap-1"
                    >
                        Lihat Berita Lainnya &rarr;
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div 
                        v-for="item in displayNews" 
                        :key="item.id" 
                        class="bg-white dark:bg-gray-900 rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-lg rounded-br-lg shadow-sm hover:shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 transform hover:-translate-y-1 flex flex-col justify-between"
                    >
                        <div>
                            <div class="relative overflow-hidden h-48 bg-slate-200 dark:bg-gray-800">
                                <img 
                                    v-if="item.thumbnail" 
                                    :src="item.thumbnail" 
                                    :alt="item.title" 
                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                    Sampul Berita
                                </div>
                            </div>
                            <div class="p-6 space-y-3">
                                <span v-if="item.category" class="text-xs font-bold text-teal-700 bg-teal-50 dark:bg-teal-950 dark:text-teal-300 px-2.5 py-0.5 rounded-full">
                                    {{ item.category.name }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2 hover:text-[#0F5E3D] transition">
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
            </section>

        </main>

        <!-- Footer -->
        <FooterGlobal />
    </div>
</template>
