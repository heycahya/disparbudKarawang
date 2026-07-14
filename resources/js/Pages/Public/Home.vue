<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const page = usePage();

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

const handleServiceClick = (e, routeName) => {
    if (!page.props.auth?.user) {
        e.preventDefault();
        router.visit(route('login'), {
            data: { intended: route(routeName) }
        });
    }
};
</script>

<template>
    <Head title="Beranda - Portal Resmi Disparbud Karawang" />

    <PublicLayout>

        <!-- Hero Section with Photo Cover & Gradient overlay -->
        <section class="relative text-white overflow-hidden py-24 sm:py-36 pb-36 sm:pb-44" style="background-image: linear-gradient(to bottom, rgba(15, 94, 61, 0.85), rgba(12, 78, 91, 0.95)), url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
            <!-- Background Decorative Pattern overlay -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Text Content -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase border border-white/20 text-amber-300">
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
                                class="px-8 py-4 bg-[#D97706] hover:bg-amber-600 text-white font-bold rounded-lg shadow-lg hover:shadow-amber-500/30 transition-all duration-300 transform hover:-translate-y-0.5"
                            >
                                Jelajahi Destinasi
                            </Link>
                            <Link 
                                :href="route('public.profile')" 
                                class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold backdrop-blur-md rounded-lg border border-white/30 transition-all duration-300"
                            >
                                Profil Dinas
                            </Link>
                        </div>
                    </div>

                    <!-- Glassmorphism Featured Card Overlay -->
                    <div class="lg:col-span-5">
                        <div class="bg-white/15 dark:bg-black/30 backdrop-blur-xl p-8 rounded-2xl border border-white/20 shadow-2xl relative">
                            <div class="absolute -top-3 -right-3 w-12 h-12 bg-[#D97706] rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.286L13 21l-2.286-6.857L5 12l5.714-2.286L13 3z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-white">Vibe Karawang Portal</h3>
                            <p class="text-sm text-emerald-100/90 mb-6 leading-relaxed">
                                Nikmati integrasi data informasi pariwisata terpadu, sejarah cagar budaya, layanan mandiri masyarakat, hingga pendaftaran kegiatan kebudayaan.
                            </p>
 
                            <!-- Mini Badge highlights -->
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/10">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white">Situs Percandian Batujaya</h4>
                                        <p class="text-[11px] text-emerald-100/70">Peninggalan Tarumanagara abad 2-6 M</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/10">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-6a9 9 0 019 9m-9-9a9 9 0 00-9 9" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white">Wisata Bahari & Mangrove</h4>
                                        <p class="text-[11px] text-emerald-100/70">Pantai Tangkolak & Tanjung Pakis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cohesive Counter Stats inside Hero at the bottom -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 bg-white/10 dark:bg-black/30 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-lg mt-16">
                    <!-- Stat 1 -->
                    <div class="flex items-center justify-between md:border-r border-white/10 last:border-0 pr-4 last:pr-0">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Destinasi Wisata</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.destinations || displayTourism.length || 13 }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="flex items-center justify-between md:border-r border-white/10 last:border-0 px-4 last:pr-0">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Warta & Berita</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.news || displayNews.length || 5 }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h3" />
                            </svg>
                        </div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="flex items-center justify-between md:border-r border-white/10 last:border-0 px-4 last:pr-0">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Seni & Budaya</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.cultures || 8 }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Stat 4 -->
                    <div class="flex items-center justify-between last:border-0 pl-4">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Ekonomi Kreatif</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.ekraf || 12 }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Overlapping Service Rakyat Widgets (Sitting over Hero bottom border) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Widget 1: Pengaduan -->
                <div 
                    @click="handleServiceClick($event, 'layanan-masyarakat.complaints.create')"
                    class="bg-white dark:bg-gray-850 p-6 rounded-xl shadow-lg border-t-4 border-emerald-600 hover:shadow-2xl transition duration-300 cursor-pointer group flex items-start space-x-4 transform hover:-translate-y-1"
                >
                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 rounded-lg shrink-0 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition text-base">Laporan Pengaduan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Laporkan keluhan fasilitas pariwisata atau cagar budaya Karawang.</p>
                    </div>
                </div>

                <!-- Widget 2: Usulan Wisata -->
                <div 
                    @click="handleServiceClick($event, 'layanan-masyarakat.tourism-submissions.create')"
                    class="bg-white dark:bg-gray-850 p-6 rounded-xl shadow-lg border-t-4 border-amber-500 hover:shadow-2xl transition duration-300 cursor-pointer group flex items-start space-x-4 transform hover:-translate-y-1"
                >
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/40 rounded-lg shrink-0 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-amber-600 dark:group-hover:text-amber-400 transition text-base">Usul Wisata Baru</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Ajukan rekomendasi objek wisata menarik baru agar masuk katalog.</p>
                    </div>
                </div>

                <!-- Widget 3: Pengajuan Event -->
                <div 
                    @click="handleServiceClick($event, 'layanan-masyarakat.event-broadcasts.create')"
                    class="bg-white dark:bg-gray-850 p-6 rounded-xl shadow-lg border-t-4 border-teal-500 hover:shadow-2xl transition duration-300 cursor-pointer group flex items-start space-x-4 transform hover:-translate-y-1"
                >
                    <div class="p-3 bg-teal-50 dark:bg-teal-950/40 rounded-lg shrink-0 text-teal-600 dark:text-teal-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition text-base">Pengajuan Event</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">Ajukan publikasi atau peliputan untuk event instansi/komunitas Anda.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Body Grid Layout (2 Columns) -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column (8/12): Main Content -->
                <div class="lg:col-span-8 space-y-16">
                    
                    <!-- Berita & Pengumuman Terbaru -->
                    <section class="space-y-8">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                            <div>
                                <span class="text-xs font-extrabold uppercase tracking-wider text-[#0C4E5B] dark:text-teal-400">Kabar Informasi</span>
                                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Berita & Pengumuman Terbaru</h2>
                            </div>
                            <Link 
                                :href="route('public.news.index')" 
                                class="text-sm font-bold text-[#0C4E5B] dark:text-teal-400 hover:underline inline-flex items-center gap-1"
                            >
                                Lihat Berita Lainnya &rarr;
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div 
                                v-for="item in displayNews" 
                                :key="item.id" 
                                class="bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 flex flex-col justify-between"
                            >
                                <div>
                                    <div class="relative overflow-hidden h-44 bg-slate-200 dark:bg-gray-800">
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
                                    <div class="p-5 space-y-2.5">
                                        <span v-if="item.category" class="text-[10px] font-bold text-teal-700 bg-teal-50 dark:bg-teal-950 dark:text-teal-300 px-2.5 py-0.5 rounded-full">
                                            {{ item.category.name }}
                                        </span>
                                        <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-2 hover:text-[#0F5E3D] transition">
                                            <Link :href="route('public.news.show', item.slug)">{{ item.title }}</Link>
                                        </h3>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed" v-html="item.content"></div>
                                    </div>
                                </div>

                                <div class="p-5 pt-0">
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

                    <!-- Destinasi Wisata Unggulan -->
                    <section class="space-y-8">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div 
                                v-for="item in displayTourism.slice(0, 4)" 
                                :key="item.id" 
                                class="group bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300"
                            >
                                <div class="relative overflow-hidden h-44 bg-slate-200 dark:bg-gray-800">
                                    <img 
                                        v-if="item.cover_image" 
                                        :src="item.cover_image" 
                                        :alt="item.name" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                    >
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 font-medium text-sm">
                                        Foto Destinasi
                                    </div>
                                    <span v-if="item.category" class="absolute top-3 left-3 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm text-[#0F5E3D] dark:text-emerald-400 text-[10px] font-bold px-2.5 py-0.5 rounded-full shadow">
                                        {{ item.category.name }}
                                    </span>
                                </div>

                                <div class="p-5 space-y-2">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-[#0F5E3D] transition">
                                        <Link :href="route('public.tourism.show', item.slug)">{{ item.name }}</Link>
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 mr-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="truncate text-xs">{{ item.address }}</span>
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
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

                </div>

                <!-- Right Column (4/12): Sidebar -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Kategori Direktori Widget -->
                    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl p-6 shadow-sm space-y-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-[#0F5E3D] dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span>Kategori Direktori</span>
                        </h3>
                        
                        <div class="flex flex-col space-y-2">
                            <Link 
                                :href="route('public.destinasi')" 
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-gray-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 hover:border-[#0F5E3D]/30 transition group cursor-pointer"
                            >
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400">🏞️ Wisata Alam & Destinasi</span>
                                <span class="text-xs text-gray-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </Link>
                            
                            <Link 
                                :href="route('public.destinasi') + '?tab=kebudayaan'" 
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-gray-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 hover:border-[#0F5E3D]/30 transition group cursor-pointer"
                            >
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400">🎭 Seni & Warisan Budaya</span>
                                <span class="text-xs text-gray-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </Link>

                            <Link 
                                :href="route('public.destinasi') + '?tab=ekraf'" 
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-gray-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 hover:border-[#0F5E3D]/30 transition group cursor-pointer"
                            >
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400">🎨 Ekonomi Kreatif (Ekraf)</span>
                                <span class="text-xs text-gray-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </Link>

                            <Link 
                                :href="route('public.destinasi') + '?tab=akomodasi'" 
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-gray-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 hover:border-[#0F5E3D]/30 transition group cursor-pointer"
                            >
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400">🏨 Hotel & Akomodasi</span>
                                <span class="text-xs text-gray-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </Link>

                            <Link 
                                :href="route('public.destinasi') + '?tab=kuliner'" 
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-gray-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 hover:border-[#0F5E3D]/30 transition group cursor-pointer"
                            >
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400">🍽️ Kuliner & Rumah Makan</span>
                                <span class="text-xs text-gray-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </Link>

                            <Link 
                                :href="route('public.gallery.index')" 
                                class="flex items-center justify-between p-3 rounded-lg border border-slate-100 dark:border-gray-800 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 hover:border-[#0F5E3D]/30 transition group cursor-pointer"
                            >
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400">📸 Galeri Foto & Kegiatan</span>
                                <span class="text-xs text-gray-400 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Hotline / Hubungi Kami Widget -->
                    <div class="bg-gradient-to-br from-[#0F5E3D] to-[#0C4E5B] rounded-xl p-6 text-white shadow-sm space-y-4 relative overflow-hidden">
                        <div class="absolute -right-12 -bottom-12 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
                        <h3 class="text-lg font-bold border-b border-white/20 pb-3 flex items-center space-x-2 relative z-10">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Layanan Informasi</span>
                        </h3>
                        <div class="space-y-3 relative z-10 text-emerald-100/90 text-sm">
                            <p>Sampaikan pertanyaan darurat atau koordinasi dinas pariwisata Karawang secara langsung:</p>
                            <div class="bg-white/10 p-3 rounded-lg border border-white/10 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-amber-300 tracking-wider">Call Center Dinas</p>
                                    <p class="text-lg font-extrabold text-white mt-0.5">115 / (0267) 12345</p>
                                </div>
                                <span class="text-xl">📞</span>
                            </div>
                            <p class="text-xs text-emerald-200/80">Jam Operasional: Senin - Jumat (08.00 - 16.00 WIB)</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Partner / Instansi Logos Section (Mockup Bottom Links) -->
            <section class="mt-20 pt-10 border-t border-gray-200 dark:border-gray-800 space-y-6">
                <div class="text-center">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400">Instansi Resmi Terkait</h3>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16 opacity-60 dark:opacity-40 hover:opacity-100 transition duration-300">
                    <a href="https://www.karawangkab.go.id" target="_blank" class="flex flex-col items-center group">
                        <div class="h-12 px-4 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center font-bold text-xs text-gray-600 dark:text-gray-300 group-hover:bg-emerald-500/10 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400 transition">
                            PEMKAB KARAWANG
                        </div>
                    </a>
                    <a href="https://jabarprov.go.id" target="_blank" class="flex flex-col items-center group">
                        <div class="h-12 px-4 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center font-bold text-xs text-gray-600 dark:text-gray-300 group-hover:bg-emerald-500/10 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400 transition">
                            DISPARBUD JABAR
                        </div>
                    </a>
                    <a href="https://kemenparekraf.go.id" target="_blank" class="flex flex-col items-center group">
                        <div class="h-12 px-4 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center font-bold text-xs text-gray-600 dark:text-gray-300 group-hover:bg-emerald-500/10 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400 transition">
                            KEMENPAREKRAF
                        </div>
                    </a>
                    <a href="https://www.indonesia.travel" target="_blank" class="flex flex-col items-center group">
                        <div class="h-12 px-4 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center font-bold text-xs text-gray-600 dark:text-gray-300 group-hover:bg-emerald-500/10 group-hover:text-[#0F5E3D] dark:group-hover:text-emerald-400 transition">
                            WONDERFUL INDONESIA
                        </div>
                    </a>
                </div>
            </section>

        </main>

    </PublicLayout>
</template>
