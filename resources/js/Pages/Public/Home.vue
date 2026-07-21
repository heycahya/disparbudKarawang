<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import LeafletMap from '@/Components/LeafletMap.vue';

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
    cultures: {
        type: Array,
        default: () => []
    },
    ekraf: {
        type: Array,
        default: () => []
    },
    accommodations: {
        type: Array,
        default: () => []
    },
    culinary: {
        type: Array,
        default: () => []
    },
    destinations: {
        type: Array,
        default: () => []
    },
    galleries: {
        type: Array,
        default: () => []
    },
    organization_profile: {
        type: Object,
        default: null
    },
    // Backward compatibility props
    news: Array,
    tourism: Array
});

const displayNews = props.latest_news?.length ? props.latest_news : (props.news || []);
const displayTourism = props.featured_destinations?.length ? props.featured_destinations : (props.tourism || []);

const selectedGalleryImage = ref(null);

// --- Show/Hide State per section (default: 3 items) ---
const PREVIEW_COUNT = 3;
const showAllWisata  = ref(false);
const showAllBudaya  = ref(false);
const showAllEkraf   = ref(false);
const showAllBerita  = ref(false);

// Computed: sliced arrays for display
const visibleTourism = computed(() =>
    showAllWisata.value ? displayTourism : displayTourism.slice(0, PREVIEW_COUNT)
);
const visibleCultures = computed(() =>
    showAllBudaya.value ? props.cultures : props.cultures.slice(0, PREVIEW_COUNT)
);
const visibleEkraf = computed(() =>
    showAllEkraf.value ? props.ekraf : props.ekraf.slice(0, PREVIEW_COUNT)
);
const visibleNews = computed(() =>
    showAllBerita.value ? displayNews : displayNews.slice(0, PREVIEW_COUNT)
);

// Smooth scroll to a section by its ID
const scrollToSection = (id) => {
    const el = document.getElementById(id);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

const handleServiceClick = (e, routeName) => {
    if (!page.props.auth?.user) {
        e.preventDefault();
        window.location.href = route('login') + '?intended=' + encodeURIComponent(route(routeName));
    } else {
        window.location.href = route(routeName);
    }
};

onMounted(() => {
    if (window.location.hash) {
        const el = document.querySelector(window.location.hash);
        if (el) {
            setTimeout(() => {
                el.scrollIntoView({ behavior: 'smooth' });
            }, 100);
        }
    }
});
</script>
<template>
    <Head title="Beranda - Portal Resmi Disparbud Karawang" />

    <PublicLayout>

        <!-- 1. HERO SECTION & PROFIL DISPARBUD -->
        <section id="section-hero" class="relative text-white overflow-hidden py-24 sm:py-36 pb-36 sm:pb-44 scroll-mt-20" style="background-image: linear-gradient(to bottom, rgba(15, 94, 61, 0.90), rgba(12, 78, 91, 0.95)), url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center;">
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Text Content -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold tracking-wide uppercase border border-white/20 text-amber-300">
                            <span>🌾 Pesona Karawang Pangkal Perjuangan</span>
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                            Jelajahi Pesona <br />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-emerald-200 to-teal-100">
                                Wisata & Kebudayaan
                            </span>
                        </h1>
                        <p class="text-lg text-emerald-100/90 max-w-2xl font-normal leading-relaxed">
                            Selamat datang di Portal Resmi Dinas Pariwisata dan Kebudayaan Kabupaten Karawang. Pusat katalog pariwisata, sejarah cagar budaya, ragam ekonomi kreatif, dan layanan mandiri masyarakat.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a 
                                href="#section-wisata" 
                                class="px-8 py-4 bg-[#D97706] hover:bg-amber-600 text-white font-bold rounded-lg shadow-lg hover:shadow-amber-500/30 transition-all duration-300 transform hover:-translate-y-0.5"
                            >
                                Jelajahi Wisata
                            </a>
                            <a 
                                href="#section-profil" 
                                class="px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold backdrop-blur-md rounded-lg border border-white/30 transition-all duration-300"
                            >
                                Profil Dinas
                            </a>
                        </div>
                    </div>

                    <!-- Glassmorphism Featured Card -->
                    <div class="lg:col-span-5">
                        <div class="bg-white/15 dark:bg-black/30 backdrop-blur-xl p-8 rounded-2xl border border-white/20 shadow-2xl relative">
                            <div class="absolute -top-3 -right-3 w-12 h-12 bg-[#D97706] rounded-full flex items-center justify-center shadow-lg">
                                <span class="material-symbols-outlined text-white text-xl animate-pulse">auto_awesome</span>
                            </div>
                            <h3 class="text-2xl font-bold mb-3 text-white">Vibe Karawang Portal</h3>
                            <p class="text-sm text-emerald-100/90 mb-6 leading-relaxed">
                                Portal Informasi Terpadu Dinas Pariwisata & Kebudayaan Kabupaten Karawang. Melayani masyarakat dengan transparan, responsif, dan akuntabel.
                            </p>

                            <div class="space-y-3">
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/10">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/30 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-emerald-300 text-lg">account_balance</span>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white">Situs Candi Batujaya</h4>
                                        <p class="text-[11px] text-emerald-100/70">Cagar budaya tertua abad ke 2-6 Masehi</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-md p-3 rounded-xl border border-white/10">
                                    <div class="w-8 h-8 rounded-lg bg-amber-500/30 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-amber-300 text-lg">landscape</span>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-white">Destinasi Bahari & Pegunungan</h4>
                                        <p class="text-[11px] text-emerald-100/70">Pantai Tangkolak, Pakis & Puncak Sanggabuana</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Counter Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 bg-white/10 dark:bg-black/30 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-lg mt-16">
                    <div class="flex items-center justify-between md:border-r border-white/10 last:border-0 pr-4">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Destinasi Wisata</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.destinations || displayTourism.length || 13 }}</h3>
                        </div>
                    </div>

                    <div class="flex items-center justify-between md:border-r border-white/10 last:border-0 px-4">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Warta & Berita</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.news || displayNews.length || 5 }}</h3>
                        </div>
                    </div>

                    <div class="flex items-center justify-between md:border-r border-white/10 last:border-0 px-4">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Seni & Budaya</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.cultures || 8 }}</h3>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pl-4">
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-100 uppercase tracking-wider">Ekonomi Kreatif</p>
                            <h3 class="text-2xl font-black text-amber-300 mt-0.5">{{ hero_stats.ekraf || 12 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SEKSI PROFIL DISPARBUD (Hub Section) -->
        <section id="section-profil" class="py-16 bg-emerald-900/5 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-800 scroll-mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="text-center max-w-3xl mx-auto space-y-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Dinas Pariwisata dan Kebudayaan Karawang</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Bertanggung jawab dalam merumuskan kebijakan, pengembangan objek wisata daerah, pelestarian cagar budaya, serta pemberdayaan ekonomi kreatif lokal Kabupaten Karawang.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-850 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">flag</span>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Visi Pembangunan</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Terwujudnya Kabupaten Karawang yang berdaya saing melalui pariwisata berkelanjutan dan pelestarian seni budaya luhur.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-850 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">museum</span>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Pelestarian Cagar Budaya</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Menginventarisir, merawat, dan mempromosikan artefak sejarah serta cagar budaya situs Batujaya dan Rengasdengklok.
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-850 p-6 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-2xl">storefront</span>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Ekonomi Kreatif &amp; UMKM</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Mendorong kemandirian pelaku kriya, kuliner khas, dan seni pertunjukan rakyat agar mampu bersaing secara nasional.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- MAIN CONTENT SECTIONS -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-24 w-full">
            
            <!-- 2. SEKSI DESTINASI WISATA -->
            <section id="section-wisata" class="space-y-8 scroll-mt-28">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Destinasi Wisata Unggulan</h2>
                    </div>
                    <button
                        v-if="displayTourism.length > 3"
                        @click="showAllWisata = !showAllWisata"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-[#0F5E3D] dark:text-emerald-400 bg-white dark:bg-gray-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg border border-[#0F5E3D]/30 dark:border-emerald-700/50 hover:border-[#0F5E3D] transition-all duration-200 group whitespace-nowrap"
                    >
                        <template v-if="!showAllWisata">
                            Lihat Semua ({{ displayTourism.length }})
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </template>
                        <template v-else>
                            Sembunyikan
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" /></svg>
                        </template>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="item in visibleTourism"
                        :key="item.id" 
                        class="group bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 flex flex-col justify-between"
                    >
                        <div>
                            <a :href="route('public.tourism.show', item.slug)" rel="external" class="block relative overflow-hidden h-48 bg-slate-200 dark:bg-gray-800">
                                <img 
                                    v-if="item.cover_image" 
                                    :src="item.cover_image" 
                                    :alt="item.name" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400 font-medium text-sm">
                                    Foto Destinasi
                                </div>
                            </a>

                            <div class="p-5 space-y-2.5">
                                <div class="flex items-center">
                                    <span v-if="item.category" class="inline-block text-[11px] font-bold text-[#005F4A] dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 dark:border-emerald-800/60 px-2.5 py-0.5 rounded-md">
                                        {{ item.category.name }}
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-[#0F5E3D] transition">
                                    <a :href="route('public.tourism.show', item.slug)" rel="external">{{ item.name }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-gray-400 dark:text-gray-500 mr-0.5 shrink-0 align-middle">location_on</span>
                                    <span class="truncate text-xs">{{ item.address }}</span>
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                    {{ item.description }}
                                </p>
                            </div>
                        </div>

                        <div class="p-5 pt-0">
                            <a
                                :href="route('public.tourism.show', item.slug)"
                                rel="external"
                                class="inline-flex items-center gap-1 text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline"
                            >
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. SEKSI SENI & KEBUDAYAAN -->
            <section id="section-budaya" class="space-y-8 scroll-mt-28">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Seni &amp; Kebudayaan Karawang</h2>
                    </div>
                    <button
                        v-if="cultures.length > 3"
                        @click="showAllBudaya = !showAllBudaya"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-[#0F5E3D] dark:text-emerald-400 bg-white dark:bg-gray-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg border border-[#0F5E3D]/30 dark:border-emerald-700/50 hover:border-[#0F5E3D] transition-all duration-200 group whitespace-nowrap"
                    >
                        <template v-if="!showAllBudaya">
                            Lihat Semua ({{ cultures.length }})
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </template>
                        <template v-else>
                            Sembunyikan
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" /></svg>
                        </template>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="item in visibleCultures"
                        :key="item.id" 
                        class="group bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 flex flex-col justify-between"
                    >
                        <div>
                            <a :href="route('public.culture.show', item.slug)" rel="external" class="block relative overflow-hidden h-48 bg-slate-200 dark:bg-gray-800">
                                <img 
                                    v-if="item.cover_image" 
                                    :src="item.cover_image" 
                                    :alt="item.name" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400 font-medium text-sm">
                                    Foto Kebudayaan
                                </div>
                            </a>

                            <div class="p-5 space-y-2.5">
                                <div class="flex items-center">
                                    <span class="inline-block text-[11px] font-bold text-[#005F4A] dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 dark:border-emerald-800/60 px-2.5 py-0.5 rounded-md">
                                        Seni &amp; Tradisi
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-[#0F5E3D] transition">
                                    <a :href="route('public.culture.show', item.slug)" rel="external">{{ item.name }}</a>
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                    {{ item.description }}
                                </p>
                            </div>
                        </div>

                        <div class="p-5 pt-0">
                            <a
                                :href="route('public.culture.show', item.slug)"
                                rel="external"
                                class="inline-flex items-center gap-1 text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline"
                            >
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. SEKSI EKONOMI KREATIF -->
            <section id="section-ekraf" class="space-y-8 scroll-mt-28">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Ekonomi Kreatif &amp; UMKM Karawang</h2>
                    </div>
                    <button
                        v-if="ekraf.length > 3"
                        @click="showAllEkraf = !showAllEkraf"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-[#0F5E3D] dark:text-emerald-400 bg-white dark:bg-gray-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg border border-[#0F5E3D]/30 dark:border-emerald-700/50 hover:border-[#0F5E3D] transition-all duration-200 group whitespace-nowrap"
                    >
                        <template v-if="!showAllEkraf">
                            Lihat Semua ({{ ekraf.length }})
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </template>
                        <template v-else>
                            Sembunyikan
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" /></svg>
                        </template>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="item in visibleEkraf"
                        :key="item.id" 
                        class="group bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 flex flex-col justify-between"
                    >
                        <div>
                            <a :href="route('public.ekraf.show', item.slug)" rel="external" class="block relative overflow-hidden h-48 bg-slate-200 dark:bg-gray-800">
                                <img 
                                    v-if="item.cover_image" 
                                    :src="item.cover_image" 
                                    :alt="item.name" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400 font-medium text-sm">
                                    Foto Produk Ekraf
                                </div>
                            </a>

                            <div class="p-5 space-y-2.5">
                                <div class="flex items-center">
                                    <span class="inline-block text-[11px] font-bold text-[#005F4A] dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 dark:border-emerald-800/60 px-2.5 py-0.5 rounded-md">
                                        Produk Kriya &amp; Ekraf
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-[#0F5E3D] transition">
                                    <a :href="route('public.ekraf.show', item.slug)" rel="external">{{ item.name }}</a>
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                    {{ item.description }}
                                </p>
                            </div>
                        </div>

                        <div class="p-5 pt-0">
                            <a
                                :href="route('public.ekraf.show', item.slug)"
                                rel="external"
                                class="inline-flex items-center gap-1 text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline"
                            >
                                Lihat Detail &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. SEKSI AKOMODASI & KULINER -->
            <section id="section-akomodasi-kuliner" class="grid grid-cols-1 lg:grid-cols-2 gap-12 pt-4 scroll-mt-28">
                
                <!-- Left: Rekomendasi Akomodasi -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Akomodasi Pilihan</h2>
                        </div>
                        <button @click="scrollToSection('section-akomodasi-kuliner')" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline transition whitespace-nowrap group">
                            Semua Pilihan <svg class="w-3.5 h-3.5 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div 
                            v-for="item in accommodations.slice(0, 3)" 
                            :key="item.id"
                            class="bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-800 flex gap-4 items-center shadow-sm hover:shadow-md transition"
                        >
                            <img v-if="item.cover_image" :src="item.cover_image" :alt="item.name" class="w-20 h-20 rounded-lg object-cover shrink-0">
                            <div v-else class="w-20 h-20 bg-slate-200 dark:bg-gray-800 rounded-lg shrink-0 flex items-center justify-center text-[10px] text-gray-400">Hotel</div>
                            <div class="min-w-0 flex-1 space-y-1">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    <a :href="route('public.accommodation.show', item.slug)" rel="external">{{ item.name }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">{{ item.address }}</p>
                            <a :href="route('public.accommodation.show', item.slug)" rel="external" class="text-[11px] font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline block">Lihat Fasilitas &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Rekomendasi Kuliner -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Kuliner &amp; Tempat Makan</h2>
                        </div>
                        <button @click="scrollToSection('section-akomodasi-kuliner')" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline transition whitespace-nowrap group">
                            Semua Pilihan <svg class="w-3.5 h-3.5 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div 
                            v-for="item in culinary.slice(0, 3)" 
                            :key="item.id"
                            class="bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-800 flex gap-4 items-center shadow-sm hover:shadow-md transition"
                        >
                            <img v-if="item.cover_image" :src="item.cover_image" :alt="item.name" class="w-20 h-20 rounded-lg object-cover shrink-0">
                            <div v-else class="w-20 h-20 bg-slate-200 dark:bg-gray-800 rounded-lg shrink-0 flex items-center justify-center text-[10px] text-gray-400">Kuliner</div>
                            <div class="min-w-0 flex-1 space-y-1">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate">
                                    <a :href="route('public.culinary.show', item.slug)" rel="external">{{ item.name }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">{{ item.address }}</p>
                            <a :href="route('public.culinary.show', item.slug)" rel="external" class="text-[11px] font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline block">Lihat Menu &amp; Lokasi &rarr;</a>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <!-- 6. SEKSI BERITA & PENGUMUMAN -->
            <section id="section-berita" class="space-y-8 scroll-mt-28">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-4">
                    <div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Berita &amp; Pengumuman Terbaru</h2>
                    </div>
                    <button
                        v-if="displayNews.length > 3"
                        @click="showAllBerita = !showAllBerita"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-[#0F5E3D] dark:text-emerald-400 bg-white dark:bg-gray-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-lg border border-[#0F5E3D]/30 dark:border-emerald-700/50 hover:border-[#0F5E3D] transition-all duration-200 group whitespace-nowrap"
                    >
                        <template v-if="!showAllBerita">
                            Lihat Semua ({{ displayNews.length }})
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </template>
                        <template v-else>
                            Sembunyikan
                            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" /></svg>
                        </template>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="item in visibleNews"
                        :key="item.id" 
                        class="bg-white dark:bg-gray-900 rounded-xl shadow-sm hover:shadow-lg border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 flex flex-col justify-between group"
                    >
                        <div>
                            <a :href="route('public.news.show', item.slug)" rel="external" class="block relative overflow-hidden h-44 bg-slate-200 dark:bg-gray-800">
                                <img 
                                    v-if="item.thumbnail" 
                                    :src="item.thumbnail" 
                                    :alt="item.title" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                    Sampul Berita
                                </div>
                            </a>
                            <div class="p-5 space-y-2.5">
                                <div v-if="item.category" class="flex items-center">
                                    <span class="inline-block text-[11px] font-bold text-[#005F4A] dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 dark:border-emerald-800/60 px-2.5 py-0.5 rounded-md">
                                        {{ item.category.name }}
                                    </span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-[#0F5E3D] transition">
                                    <a :href="route('public.news.show', item.slug)" rel="external">{{ item.title }}</a>
                                </h3>
                                <div class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed" v-html="item.content"></div>
                            </div>
                        </div>

                        <div class="p-5 pt-0">
                            <a 
                                :href="route('public.news.show', item.slug)" 
                                rel="external"
                                class="text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline"
                            >
                                Baca Selengkapnya &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 7. SEKSI FORM LAYANAN MASYARAKAT / SERVICE RAKYAT -->
            <section id="section-layanan" class="space-y-8 scroll-mt-28 bg-gradient-to-br from-emerald-900/10 via-teal-900/5 to-transparent p-8 sm:p-12 rounded-3xl border border-emerald-900/20">
                <div class="text-center max-w-2xl mx-auto space-y-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Aspirasi & Layanan Masyarakat</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Pilih jenis layanan masyarakat di bawah ini untuk membuat laporan pengaduan, pengajuan wisata baru, atau pendaftaran event.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div 
                        @click="handleServiceClick($event, 'layanan-masyarakat.complaints.create')"
                        class="bg-white dark:bg-gray-850 p-6 rounded-2xl shadow-sm border-t-4 border-[#005F4A] border-x border-b border-gray-100 dark:border-gray-800 hover:shadow-lg transition duration-300 cursor-pointer group flex flex-col justify-between space-y-4"
                    >
                        <div class="space-y-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">campaign</span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg">Form Pengaduan Publik</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                Sampaikan keluhan terkait kerusakan fasilitas pariwisata atau cagar budaya Karawang.
                            </p>
                        </div>
                        <span class="text-xs font-bold text-[#005F4A] dark:text-emerald-400 group-hover:underline">Buat Pengaduan &rarr;</span>
                    </div>

                    <div 
                        @click="handleServiceClick($event, 'layanan-masyarakat.tourism-submissions.create')"
                        class="bg-white dark:bg-gray-850 p-6 rounded-2xl shadow-sm border-t-4 border-[#005F4A] border-x border-b border-gray-100 dark:border-gray-800 hover:shadow-lg transition duration-300 cursor-pointer group flex flex-col justify-between space-y-4"
                    >
                        <div class="space-y-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">add_location_alt</span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg">Usul Destinasi Baru</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                Daftarkan potensi objek wisata baru di daerah Anda agar dapat diverifikasi oleh admin dinas.
                            </p>
                        </div>
                        <span class="text-xs font-bold text-[#005F4A] dark:text-emerald-400 group-hover:underline">Ajukan Objek Wisata &rarr;</span>
                    </div>

                    <div 
                        @click="handleServiceClick($event, 'layanan-masyarakat.event-broadcasts.create')"
                        class="bg-white dark:bg-gray-850 p-6 rounded-2xl shadow-sm border-t-4 border-[#005F4A] border-x border-b border-gray-100 dark:border-gray-800 hover:shadow-lg transition duration-300 cursor-pointer group flex flex-col justify-between space-y-4"
                    >
                        <div class="space-y-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-2xl">event_available</span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg">Pengajuan Agenda Event</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                Publikasikan event kebudayaan, festival kriya, atau pameran komunitas Anda di agenda resmi.
                            </p>
                        </div>
                        <span class="text-xs font-bold text-[#005F4A] dark:text-emerald-400 group-hover:underline">Daftarkan Event &rarr;</span>
                    </div>
                </div>
            </section>

            <!-- 8. SEKSI GALERI FOTO DOKUMENTASI & PETA INTERAKTIF LEAFLET -->
            <section id="section-galeri-peta" class="space-y-12 scroll-mt-28">
                <!-- Galeri Preview -->
                <div v-if="galleries?.length" class="space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
                        <div>
                            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Galeri Foto Kegiatan &amp; Pariwisata</h2>
                        </div>
                        <button @click="scrollToSection('section-galeri-peta')" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:text-emerald-700 hover:underline transition whitespace-nowrap group">
                            Lihat Semua Foto <svg class="w-3.5 h-3.5 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div 
                            v-for="g in galleries.slice(0, 6)" 
                            :key="g.id"
                            @click="selectedGalleryImage = g"
                            class="relative h-40 rounded-xl overflow-hidden group cursor-pointer shadow-sm border border-gray-100 dark:border-gray-800"
                        >
                            <img :src="g.photo" :alt="g.title" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-center p-2">
                                <p class="text-[11px] font-bold line-clamp-2">{{ g.title }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peta Interaktif Leaflet -->
                <div class="space-y-6">
                    <div class="border-b border-gray-200 dark:border-gray-800 pb-4">
                        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mt-0.5">Peta Interaktif Sebaran Wisata Karawang</h2>
                    </div>

                    <div class="h-96 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-md">
                        <LeafletMap :destinations="destinations" />
                    </div>
                </div>
            </section>

            <!-- PARTNER & INSTANSI TERKAIT -->
            <section class="pt-10 border-t border-gray-200 dark:border-gray-800 space-y-6">
                <div class="text-center">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">Jejaring Instansi &amp; Kemitraan Resmi</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <a 
                        href="https://www.karawangkab.go.id" 
                        target="_blank" 
                        rel="noopener"
                        class="p-4 bg-white dark:bg-gray-850 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:border-[#005F4A] transition-all duration-300 flex flex-col items-center text-center space-y-2 group"
                    >
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">account_balance</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white block group-hover:text-[#005F4A] transition">Pemkab Karawang</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">karawangkab.go.id</span>
                        </div>
                    </a>

                    <a 
                        href="https://jabarprov.go.id" 
                        target="_blank" 
                        rel="noopener"
                        class="p-4 bg-white dark:bg-gray-850 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:border-[#005F4A] transition-all duration-300 flex flex-col items-center text-center space-y-2 group"
                    >
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">domain</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white block group-hover:text-[#005F4A] transition">Disparbud Jabar</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">jabarprov.go.id</span>
                        </div>
                    </a>

                    <a 
                        href="https://kemenparekraf.go.id" 
                        target="_blank" 
                        rel="noopener"
                        class="p-4 bg-white dark:bg-gray-850 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:border-[#005F4A] transition-all duration-300 flex flex-col items-center text-center space-y-2 group"
                    >
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">assured_workload</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white block group-hover:text-[#005F4A] transition">Kemenparekraf RI</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">kemenparekraf.go.id</span>
                        </div>
                    </a>

                    <a 
                        href="https://www.indonesia.travel" 
                        target="_blank" 
                        rel="noopener"
                        class="p-4 bg-white dark:bg-gray-850 rounded-xl border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-md hover:border-[#005F4A] transition-all duration-300 flex flex-col items-center text-center space-y-2 group"
                    >
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/50 dark:border-emerald-800/50 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-xl">travel_explore</span>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-900 dark:text-white block group-hover:text-[#005F4A] transition">Wonderful Indonesia</span>
                            <span class="text-[10px] text-gray-400 block mt-0.5">indonesia.travel</span>
                        </div>
                    </a>
                </div>
            </section>

        </main>

        <!-- Gallery Lightbox Modal -->
        <div v-if="selectedGalleryImage" @click="selectedGalleryImage = null" class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
            <div @click.stop class="bg-white dark:bg-gray-900 max-w-3xl w-full rounded-2xl overflow-hidden shadow-2xl space-y-4 p-4">
                <div class="relative h-96 bg-black rounded-xl overflow-hidden">
                    <img :src="selectedGalleryImage.photo" :alt="selectedGalleryImage.title" class="w-full h-full object-contain">
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">{{ selectedGalleryImage.title }}</h3>
                        <p class="text-xs text-gray-500 capitalize">{{ selectedGalleryImage.category }}</p>
                    </div>
                    <button @click="selectedGalleryImage = null" class="px-4 py-2 bg-gray-200 dark:bg-gray-800 text-xs font-bold rounded-lg hover:bg-gray-300 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </PublicLayout>
</template>
