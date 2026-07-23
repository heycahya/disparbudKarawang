<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const searchQuery = ref('');
const isSearchOpen = ref(false);

const navItems = [
    { label: 'Beranda',            hash: 'section-hero' },
    { label: 'Profil',             hash: 'section-profil' },
    { label: 'Wisata',             hash: 'section-wisata' },
    { label: 'Budaya',             hash: 'section-budaya' },
    { label: 'Ekraf',              hash: 'section-ekraf' },
    { label: 'Akomodasi & Kuliner', hash: 'section-akomodasi-kuliner' },
    { label: 'Berita',             hash: 'section-berita' },
    { label: 'Layanan',            hash: 'section-layanan' },
    { label: 'Galeri & Peta',      hash: 'section-galeri-peta' },
];

function scrollTo(hash) {
    const isHomePage = window.location.pathname === '/';
    if (isHomePage) {
        const el = document.getElementById(hash);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    } else {
        window.location.href = '/#' + hash;
    }
}

// Search Index Compilation from Page Props
const allSearchableItems = computed(() => {
    const items = [];
    
    // Wisata
    const destinations = page.props.featured_destinations || page.props.destinations || [];
    destinations.forEach(item => {
        items.push({
            id: 'dest-' + (item.id || item.slug),
            name: item.name,
            category: 'Wisata',
            categoryColor: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300/40',
            hash: 'section-wisata',
            url: item.slug ? route('public.tourism.show', item.slug) : null,
            icon: 'landscape'
        });
    });

    // Budaya
    const cultures = page.props.cultures || [];
    cultures.forEach(item => {
        items.push({
            id: 'cult-' + (item.id || item.slug),
            name: item.name,
            category: 'Budaya',
            categoryColor: 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-300/40',
            hash: 'section-budaya',
            url: item.slug ? route('public.culture.show', item.slug) : null,
            icon: 'theater_comedy'
        });
    });

    // Ekraf
    const ekraf = page.props.ekraf || [];
    ekraf.forEach(item => {
        items.push({
            id: 'ekraf-' + (item.id || item.slug),
            name: item.name,
            category: 'Ekraf',
            categoryColor: 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 border border-purple-300/40',
            hash: 'section-ekraf',
            url: item.slug ? route('public.ekraf.show', item.slug) : null,
            icon: 'storefront'
        });
    });

    // Akomodasi
    const accommodations = page.props.accommodations || [];
    accommodations.forEach(item => {
        items.push({
            id: 'acc-' + (item.id || item.slug),
            name: item.name,
            category: 'Akomodasi',
            categoryColor: 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border border-blue-300/40',
            hash: 'section-akomodasi-kuliner',
            url: item.slug ? route('public.accommodation.show', item.slug) : null,
            icon: 'hotel'
        });
    });

    // Kuliner
    const culinary = page.props.culinary || [];
    culinary.forEach(item => {
        items.push({
            id: 'cul-' + (item.id || item.slug),
            name: item.name,
            category: 'Kuliner',
            categoryColor: 'bg-orange-100 text-orange-800 dark:bg-orange-950 dark:text-orange-300 border border-orange-300/40',
            hash: 'section-akomodasi-kuliner',
            url: item.slug ? route('public.culinary.show', item.slug) : null,
            icon: 'restaurant'
        });
    });

    // Berita
    const news = page.props.latest_news || page.props.news || [];
    news.forEach(item => {
        items.push({
            id: 'news-' + (item.id || item.slug),
            name: item.title,
            category: 'Berita',
            categoryColor: 'bg-teal-100 text-teal-800 dark:bg-teal-950 dark:text-teal-300 border border-teal-300/40',
            hash: 'section-berita',
            url: item.slug ? route('public.news.show', item.slug) : null,
            icon: 'newspaper'
        });
    });

    // Fallback search index items if page props empty
    if (items.length === 0) {
        return [
            { id: 'f1', name: 'Curug Cigentis', category: 'Wisata', categoryColor: 'bg-emerald-100 text-emerald-800', hash: 'section-wisata', icon: 'landscape' },
            { id: 'f2', name: 'Green Canyon Karawang', category: 'Wisata', categoryColor: 'bg-emerald-100 text-emerald-800', hash: 'section-wisata', icon: 'landscape' },
            { id: 'f3', name: 'Tari Goyang Karawang', category: 'Budaya', categoryColor: 'bg-amber-100 text-amber-800', hash: 'section-budaya', icon: 'theater_comedy' },
            { id: 'f4', name: 'Topeng Banjet', category: 'Budaya', categoryColor: 'bg-amber-100 text-amber-800', hash: 'section-budaya', icon: 'theater_comedy' },
            { id: 'f5', name: 'Batik Taza Karawang', category: 'Ekraf', categoryColor: 'bg-purple-100 text-purple-800', hash: 'section-ekraf', icon: 'storefront' },
            { id: 'f6', name: 'Resinda Hotel Karawang', category: 'Akomodasi', categoryColor: 'bg-blue-100 text-blue-800', hash: 'section-akomodasi-kuliner', icon: 'hotel' },
            { id: 'f7', name: 'Soto Tangkar Mang Nean', category: 'Kuliner', categoryColor: 'bg-orange-100 text-orange-800', hash: 'section-akomodasi-kuliner', icon: 'restaurant' },
        ];
    }

    return items;
});

const searchResults = computed(() => {
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return [];
    return allSearchableItems.value.filter(item => 
        item.name.toLowerCase().includes(q) || item.category.toLowerCase().includes(q)
    );
});

function handleSelectSearchItem(item) {
    searchQuery.value = '';
    isSearchOpen.value = false;
    if (item.url) {
        window.location.href = item.url;
    } else if (item.hash) {
        scrollTo(item.hash);
    }
}
</script>

<template>
    <nav class="sticky top-0 z-50 flex flex-col shadow-sm">
        <!-- Row 1: Top Bar (Branding & Live Search Input/Icon) -->
        <div class="relative z-20 bg-[#005F4A] dark:bg-[#004D3C] border-b border-black/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center gap-4">
                    
                    <!-- Branding (Left) -->
                    <div class="flex items-center space-x-3 shrink-0">
                        <div class="flex-shrink-0">
                            <svg class="w-9 h-9" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="2" y="2" width="36" height="36" rx="9" class="fill-white/10 dark:fill-gray-900/50 stroke-white/30" stroke-width="1.5"/>
                                <path d="M12 13C14 17 16 27 20 27C24 27 26 17 28 13" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 18C18 20 20 22.5 20 22.5C20 22.5 22 20 24 18" stroke="#F59E0B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="20" cy="31" r="2" fill="#F59E0B" />
                            </svg>
                        </div>
                        <div>
                            <a :href="route('public.home')" rel="external" class="text-xl font-extrabold tracking-tight text-white hover:text-emerald-100 transition">
                                Vibe <span class="text-emerald-300">Karawang</span>
                            </a>
                            <p class="text-[10px] uppercase font-bold tracking-wider text-amber-400">Pariwisata &amp; Kebudayaan</p>
                        </div>
                    </div>

                    <!-- Right Side: Live Search Input & Dropdown -->
                    <div class="relative flex-1 max-w-md">
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-emerald-200 pointer-events-none flex items-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>

                            <input
                                v-model="searchQuery"
                                @focus="isSearchOpen = true"
                                type="text"
                                placeholder="Cari wisata, budaya, kuliner, akomodasi..."
                                class="w-full pl-9 pr-8 py-1.5 text-xs rounded-full bg-white/15 dark:bg-black/30 border border-white/20 text-white placeholder-emerald-100/70 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:bg-white/25 transition duration-200"
                            />

                            <button
                                v-if="searchQuery"
                                @click="searchQuery = ''; isSearchOpen = false"
                                class="absolute right-2.5 text-emerald-200 hover:text-white transition"
                                aria-label="Clear Search"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Live Search Results Dropdown -->
                        <div
                            v-if="isSearchOpen && searchQuery.trim().length > 0"
                            class="absolute left-0 right-0 mt-2 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden z-50 transition-all duration-200 max-h-96 overflow-y-auto"
                        >
                            <!-- Matching Items -->
                            <div v-if="searchResults.length > 0" class="p-2 space-y-1">
                                <div class="px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                    Hasil Pencarian ({{ searchResults.length }})
                                </div>

                                <div
                                    v-for="item in searchResults"
                                    :key="item.id"
                                    @click="handleSelectSearchItem(item)"
                                    class="flex items-center justify-between p-2.5 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-950/40 cursor-pointer transition group"
                                >
                                    <div class="flex items-center space-x-2.5 min-w-0">
                                        <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-[#005F4A] dark:text-emerald-300 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-base">{{ item.icon }}</span>
                                        </span>
                                        <span class="text-xs font-bold text-gray-800 dark:text-gray-200 group-hover:text-[#005F4A] dark:group-hover:text-emerald-300 truncate">
                                            {{ item.name }}
                                        </span>
                                    </div>

                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-md shrink-0 ml-2" :class="item.categoryColor">
                                        {{ item.category }}
                                    </span>
                                </div>
                            </div>

                            <!-- Not Found Response State -->
                            <div v-else class="p-6 text-center space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-xs font-extrabold text-gray-900 dark:text-white">Tidak Ada Hasil Ditemukan</h4>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">
                                    Maaf, keyword "<span class="font-bold text-amber-600 dark:text-amber-400">{{ searchQuery }}</span>" tidak cocok dengan destinasi, budaya, atau layanan apapun.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Row 2: Menu Bar (Nav Links Left, Login/Register or Dashboard Right) - Always Visible -->
        <div class="relative z-10 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shadow-sm py-1.5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Desktop & Mobile Layout (Horizontal Scrollable Menu & Auth Buttons) -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-2 sm:gap-4 min-h-10">
                    <!-- Navigation Links (Left) -->
                    <div class="flex items-center space-x-1 sm:space-x-2 overflow-x-auto w-full sm:w-auto py-1 scrollbar-none">
                        <button
                            v-for="item in navItems"
                            :key="item.hash"
                            @click="scrollTo(item.hash)"
                            class="text-xs font-bold transition duration-150 px-2.5 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:text-[#005F4A] dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 whitespace-nowrap shrink-0"
                        >
                            {{ item.label }}
                        </button>
                    </div>

                    <!-- Auth Actions (Right) -->
                    <div class="flex items-center space-x-2 shrink-0 w-full sm:w-auto justify-end">
                        <template v-if="page.props.auth?.user">
                            <a :href="page.props.auth?.user?.role === 'admin' ? route('admin.dashboard') : route('dashboard')" rel="external" class="px-4 py-1.5 text-xs font-bold text-white bg-[#005F4A] hover:bg-[#004D3C] rounded-lg shadow-sm transition whitespace-nowrap">
                                Dashboard
                            </a>
                        </template>
                        <template v-else>
                            <a :href="route('login')" rel="external" class="px-3.5 py-1.5 text-xs font-bold text-[#005F4A] dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-800 rounded-lg transition border border-emerald-600/30 whitespace-nowrap">
                                Masuk
                            </a>
                            <a :href="route('register')" rel="external" class="px-4 py-1.5 text-xs font-bold text-white bg-[#005F4A] hover:bg-[#004D3C] rounded-lg shadow-sm transition whitespace-nowrap">
                                Daftar
                            </a>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </nav>
</template>
