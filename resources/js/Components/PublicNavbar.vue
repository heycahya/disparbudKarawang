<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const page = usePage();
const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

// Smart anchor navigation: if already on '/', just smooth-scroll.
// If on a detail page, navigate to '/' + hash so browser reloads home then jumps.
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
    isMobileMenuOpen.value = false;
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
    <nav class="sticky top-0 z-50 flex flex-col shadow-sm transition-all duration-300">
        <!-- Row 1: Header Top Bar -->
        <div class="relative z-20 bg-[#005F4A] dark:bg-[#004D3C] border-b border-black/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    
                    <!-- Branding (Left) -->
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <!-- Adjusted Logo for Dark Background -->
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

                    <!-- Right Action Buttons & Mobile Toggle -->
                    <div class="flex items-center gap-3">
                        <!-- Desktop Actions -->
                        <div class="hidden sm:flex items-center space-x-3">
                            <template v-if="page.props.auth?.user">
                                <a :href="route('dashboard')" rel="external" class="px-5 py-2 text-sm font-bold text-[#005F4A] bg-emerald-50 rounded-lg hover:bg-white shadow-sm transition-all duration-200">
                                    Dashboard
                                </a>
                            </template>
                            <template v-else>
                                <a :href="route('login')" rel="external" class="px-4 py-2 text-sm font-semibold text-emerald-50 hover:text-white transition">
                                    Masuk
                                </a>
                                <a :href="route('register')" rel="external" class="px-5 py-2 text-sm font-bold text-[#005F4A] bg-emerald-300 rounded-lg hover:bg-emerald-200 shadow-sm transition-all duration-200">
                                    Daftar
                                </a>
                            </template>
                        </div>

                        <!-- Mobile Action / Toggle -->
                        <div class="flex sm:hidden items-center gap-2">
                            <template v-if="page.props.auth?.user">
                                <a :href="route('dashboard')" rel="external" class="px-3 py-1.5 text-xs font-bold text-[#005F4A] bg-emerald-50 rounded-lg hover:bg-white transition">
                                    Dashboard
                                </a>
                            </template>
                            <template v-else>
                                <a :href="route('login')" rel="external" class="px-3 py-1.5 text-xs font-semibold text-emerald-50 hover:text-white transition">
                                    Masuk
                                </a>
                            </template>

                            <button
                                @click="isMobileMenuOpen = !isMobileMenuOpen"
                                class="p-2 rounded-lg text-emerald-50 hover:bg-black/20 focus:outline-none transition"
                                aria-label="Toggle Navigation"
                            >
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Row 2: Navigation Menu Bar -->
        <div 
            class="relative z-10 transition-all duration-300 ease-in-out overflow-hidden origin-top"
            :class="(isScrolled && !isMobileMenuOpen) ? 'max-h-0 opacity-0 -translate-y-4' : 'max-h-40 opacity-100 translate-y-0'"
        >
            <transition
                enter-active-class="transition duration-300 ease-out origin-top"
                enter-from-class="opacity-0 -translate-y-4 scale-y-95"
                enter-to-class="opacity-100 translate-y-0 scale-y-100"
                leave-active-class="transition duration-200 ease-in origin-top"
                leave-from-class="opacity-100 translate-y-0 scale-y-100"
                leave-to-class="opacity-0 -translate-y-4 scale-y-95"
            >
                <div v-show="isMobileMenuOpen || typeof window !== 'undefined' && window.innerWidth >= 1024" 
                     class="lg:!block bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        
                        <!-- Desktop Menu (Horizontal) - SLIM HEIGHT -->
                        <div class="hidden lg:flex justify-center items-center h-9 space-x-1 lg:space-x-3">
                            <button
                                v-for="item in navItems"
                                :key="item.hash"
                                @click="scrollTo(item.hash)"
                                class="text-xs font-bold transition duration-150 px-2.5 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:text-[#005F4A] dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 whitespace-nowrap"
                            >
                                {{ item.label }}
                            </button>
                        </div>

                    <!-- Mobile Menu (Grid) - SLIM PADDING -->
                    <div class="lg:hidden py-3 grid grid-cols-2 gap-1.5">
                        <button
                            v-for="item in navItems"
                            :key="item.hash + '-mobile'"
                            @click="scrollTo(item.hash)"
                            class="block px-3 py-2 text-xs font-semibold rounded-md bg-gray-50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:text-[#005F4A] dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 text-left transition border border-gray-100 dark:border-gray-700 hover:border-emerald-200 dark:hover:border-emerald-800"
                        >
                            {{ item.label }}
                        </button>
                        
                        <!-- Extra Mobile CTA for Register if not logged in -->
                        <template v-if="!page.props.auth?.user">
                            <a :href="route('register')" rel="external" class="col-span-2 mt-1 block w-full text-center px-4 py-2 text-xs font-bold text-white bg-[#005F4A] rounded-md hover:bg-[#004D3C] transition" @click="isMobileMenuOpen = false">
                                Daftar Akun
                            </a>
                        </template>
                    </div>

                </div>
            </div>
            </transition>
        </div>
    </nav>
</template>
