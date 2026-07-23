<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const isMenuOpen = ref(true);

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
</script>

<template>
    <nav class="sticky top-0 z-50 flex flex-col shadow-sm">
        <!-- Row 1: Top Bar (Logo & Hamburger Only) -->
        <div class="relative z-20 bg-[#005F4A] dark:bg-[#004D3C] border-b border-black/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    <!-- Branding (Left) -->
                    <div class="flex items-center space-x-3">
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

                    <!-- Right Side: Hamburger Toggle Button Only -->
                    <div class="flex items-center">
                        <button
                            @click="isMenuOpen = !isMenuOpen"
                            class="p-2 rounded-lg text-emerald-50 hover:bg-black/20 focus:outline-none transition"
                            aria-label="Toggle Navigation"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path v-if="!isMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Row 2: Menu Bar (Nav Links Left, Login/Register or Dashboard Right) -->
        <div 
            class="relative z-10 transition-all duration-300 ease-in-out overflow-hidden origin-top"
            :class="isMenuOpen ? 'max-h-56 opacity-100 translate-y-0' : 'max-h-0 opacity-0 -translate-y-4'"
        >
            <div class="bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shadow-sm py-1.5">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    <!-- Desktop Layout (Horizontal) -->
                    <div class="hidden lg:flex justify-between items-center h-10">
                        <!-- Navigation Links (Left) -->
                        <div class="flex items-center space-x-1 lg:space-x-2 overflow-x-auto">
                            <button
                                v-for="item in navItems"
                                :key="item.hash"
                                @click="scrollTo(item.hash)"
                                class="text-xs font-bold transition duration-150 px-2.5 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:text-[#005F4A] dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 whitespace-nowrap"
                            >
                                {{ item.label }}
                            </button>
                        </div>

                        <!-- Auth Actions (Right) -->
                        <div class="flex items-center space-x-2 shrink-0 ml-4">
                            <template v-if="page.props.auth?.user">
                                <a :href="page.props.auth?.user?.role === 'admin' ? route('admin.dashboard') : route('dashboard')" rel="external" class="px-4 py-1.5 text-xs font-bold text-white bg-[#005F4A] hover:bg-[#004D3C] rounded-lg shadow-sm transition">
                                    Dashboard
                                </a>
                            </template>
                            <template v-else>
                                <a :href="route('login')" rel="external" class="px-3.5 py-1.5 text-xs font-bold text-[#005F4A] dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-800 rounded-lg transition border border-emerald-600/30">
                                    Masuk
                                </a>
                                <a :href="route('register')" rel="external" class="px-4 py-1.5 text-xs font-bold text-white bg-[#005F4A] hover:bg-[#004D3C] rounded-lg shadow-sm transition">
                                    Daftar
                                </a>
                            </template>
                        </div>
                    </div>

                    <!-- Mobile Layout (Vertical/Grid) -->
                    <div class="lg:hidden py-2 space-y-3">
                        <div class="grid grid-cols-2 gap-1.5">
                            <button
                                v-for="item in navItems"
                                :key="item.hash + '-mobile'"
                                @click="scrollTo(item.hash)"
                                class="block px-3 py-2 text-xs font-semibold rounded-md bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:text-[#005F4A] dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 text-left transition border border-gray-100 dark:border-gray-700"
                            >
                                {{ item.label }}
                            </button>
                        </div>

                        <!-- Mobile Auth Actions -->
                        <div class="pt-2 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end space-x-2">
                            <template v-if="page.props.auth?.user">
                                <a :href="page.props.auth?.user?.role === 'admin' ? route('admin.dashboard') : route('dashboard')" rel="external" class="w-full text-center px-4 py-2 text-xs font-bold text-white bg-[#005F4A] rounded-md hover:bg-[#004D3C] transition">
                                    Dashboard
                                </a>
                            </template>
                            <template v-else>
                                <a :href="route('login')" rel="external" class="flex-1 text-center px-3.5 py-2 text-xs font-bold text-[#005F4A] dark:text-emerald-400 bg-gray-50 dark:bg-gray-800 rounded-md border border-emerald-600/30 transition">
                                    Masuk
                                </a>
                                <a :href="route('register')" rel="external" class="flex-1 text-center px-4 py-2 text-xs font-bold text-white bg-[#005F4A] rounded-md hover:bg-[#004D3C] transition">
                                    Daftar
                                </a>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </nav>
</template>
