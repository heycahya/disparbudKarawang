<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const isLayananOpen = ref(false);
const isMobileMenuOpen = ref(false);
const isMobileLayananOpen = ref(false);
</script>

<template>
    <nav class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-md sticky top-0 z-50 border-b border-emerald-900/10 dark:border-emerald-100/10 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Branding -->
                <div class="flex items-center space-x-3">
                    <!-- Logo Vibe Karawang -->
                    <div class="flex-shrink-0">
                        <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="vibe-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#0F5E3D" />
                                    <stop offset="100%" stop-color="#0C4E5B" />
                                </linearGradient>
                            </defs>
                            <!-- Outer rounded border with Golok Lubuk inspired style -->
                            <rect x="2" y="2" width="36" height="36" rx="9" class="fill-[#FAF9F5] dark:fill-gray-800 stroke-[#0F5E3D]/20 dark:stroke-emerald-500/20" stroke-width="1.5"/>
                            <!-- Interlocking V and Wave shape -->
                            <path d="M12 13C14 17 16 27 20 27C24 27 26 17 28 13" stroke="url(#vibe-grad)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 18C18 20 20 22.5 20 22.5C20 22.5 22 20 24 18" stroke="#D97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <!-- Dynamic vibe dot -->
                            <circle cx="20" cy="31" r="2" fill="#D97706" />
                        </svg>
                    </div>
                    <div>
                        <Link :href="route('public.home')" class="text-xl font-bold tracking-tight text-gray-900 dark:text-white hover:text-[#0F5E3D] transition">
                            Vibe <span class="text-[#0F5E3D] dark:text-emerald-400">Karawang</span>
                        </Link>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-[#D97706]">Pariwisata & Kebudayaan</p>
                    </div>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden md:flex items-center space-x-6">
                    <Link 
                        :href="route('public.home')" 
                        class="text-sm font-semibold transition duration-150 py-1 border-b-2"
                        :class="route().current('public.home') ? 'text-[#0F5E3D] dark:text-emerald-400 border-[#0F5E3D]' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-[#0F5E3D] dark:hover:text-emerald-400'"
                    >
                        Beranda
                    </Link>
                    <Link 
                        :href="route('public.profile')" 
                        class="text-sm font-semibold transition duration-150 py-1 border-b-2"
                        :class="route().current('public.profile') ? 'text-[#0F5E3D] dark:text-emerald-400 border-[#0F5E3D]' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-[#0F5E3D] dark:hover:text-emerald-400'"
                    >
                        Profil Lembaga
                    </Link>
                    <Link 
                        :href="route('public.destinasi')" 
                        class="text-sm font-semibold transition duration-150 py-1 border-b-2"
                        :class="route().current('public.destinasi') || route().current('public.tourism.index') ? 'text-[#0F5E3D] dark:text-emerald-400 border-[#0F5E3D]' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-[#0F5E3D] dark:hover:text-emerald-400'"
                    >
                        Direktori Wisata
                    </Link>
                    <Link 
                        :href="route('public.news.index')" 
                        class="text-sm font-semibold transition duration-150 py-1 border-b-2"
                        :class="route().current('public.news.index') ? 'text-[#0F5E3D] dark:text-emerald-400 border-[#0F5E3D]' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-[#0F5E3D] dark:hover:text-emerald-400'"
                    >
                        Berita
                    </Link>
                    <Link 
                        :href="route('public.gallery.index')" 
                        class="text-sm font-semibold transition duration-150 py-1 border-b-2"
                        :class="route().current('public.gallery.index') ? 'text-[#0F5E3D] dark:text-emerald-400 border-[#0F5E3D]' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-[#0F5E3D] dark:hover:text-emerald-400'"
                    >
                        Galeri
                    </Link>

                    <!-- Dropdown Layanan -->
                    <div 
                        class="relative"
                        @mouseenter="isLayananOpen = true"
                        @mouseleave="isLayananOpen = false"
                    >
                        <button 
                            class="text-sm font-semibold transition duration-150 py-1 border-b-2 inline-flex items-center gap-1 focus:outline-none"
                            :class="route().current('layanan-masyarakat.*') ? 'text-[#0F5E3D] dark:text-emerald-400 border-[#0F5E3D]' : 'text-gray-600 dark:text-gray-300 border-transparent hover:text-[#0F5E3D] dark:hover:text-emerald-400'"
                        >
                            Layanan
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': isLayananOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <transition
                            enter-active-class="transition ease-out duration-200"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <div 
                                v-show="isLayananOpen"
                                class="absolute left-0 mt-2 w-64 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50 border border-gray-100 dark:border-gray-700 overflow-hidden"
                            >
                                <div class="py-1">
                                    <Link 
                                        :href="route('layanan-masyarakat.complaints.create')" 
                                        class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 hover:text-[#0F5E3D] dark:hover:text-emerald-400 font-medium transition"
                                        @click="isLayananOpen = false"
                                    >
                                        <svg class="w-5 h-5 text-[#0F5E3D] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                        <span>Laporan Pengaduan</span>
                                    </Link>
                                    <Link 
                                        :href="route('layanan-masyarakat.tourism-submissions.create')" 
                                        class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 hover:text-[#0F5E3D] dark:hover:text-emerald-400 font-medium transition"
                                        @click="isLayananOpen = false"
                                    >
                                        <svg class="w-5 h-5 text-[#0F5E3D] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                        </svg>
                                        <span>Usul Wisata Baru</span>
                                    </Link>
                                    <Link 
                                        :href="route('layanan-masyarakat.event-broadcasts.create')" 
                                        class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 hover:text-[#0F5E3D] dark:hover:text-emerald-400 font-medium transition"
                                        @click="isLayananOpen = false"
                                    >
                                        <svg class="w-5 h-5 text-[#0F5E3D] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>Pengajuan Event</span>
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>
                </div>

                <!-- Right Action Buttons (Desktop) -->
                <div class="hidden md:flex items-center space-x-3">
                    <template v-if="page.props.auth?.user">
                        <Link 
                            :href="route('dashboard')" 
                            class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-700 rounded-asymmetric-sm hover:bg-emerald-800 shadow-sm transition-all duration-200"
                        >
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link 
                            :href="route('login')" 
                            class="px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-emerald-600 dark:hover:text-emerald-400 transition"
                        >
                            Masuk
                        </Link>
                        <Link 
                            :href="route('register')" 
                            class="px-5 py-2.5 text-sm font-bold text-white bg-emerald-700 rounded-asymmetric-sm hover:bg-emerald-800 shadow-sm transition-all duration-200"
                        >
                            Daftar
                        </Link>
                    </template>
                </div>

                <!-- Hamburger Button (Mobile / Tablet) -->
                <div class="flex md:hidden items-center">
                    <button 
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="p-2.5 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition"
                        aria-label="Toggle Mobile Menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path 
                                v-if="!isMobileMenuOpen" 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                stroke-width="2" 
                                d="M4 6h16M4 12h16M4 18h16" 
                            />
                            <path 
                                v-else 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                stroke-width="2" 
                                d="M6 18L18 6M6 6l12 12" 
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Drawer -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div 
                v-show="isMobileMenuOpen" 
                class="md:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 px-4 pt-4 pb-6 space-y-4 shadow-inner"
            >
                <div class="flex flex-col space-y-2">
                    <Link 
                        :href="route('public.home')" 
                        class="block px-3 py-2.5 text-base font-semibold rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                        :class="route().current('public.home') ? 'bg-emerald-50 dark:bg-emerald-950/30 text-[#0F5E3D] dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'"
                        @click="isMobileMenuOpen = false"
                    >
                        Beranda
                    </Link>
                    <Link 
                        :href="route('public.profile')" 
                        class="block px-3 py-2.5 text-base font-semibold rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                        :class="route().current('public.profile') ? 'bg-emerald-50 dark:bg-emerald-950/30 text-[#0F5E3D] dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'"
                        @click="isMobileMenuOpen = false"
                    >
                        Profil Lembaga
                    </Link>
                    <Link 
                        :href="route('public.destinasi')" 
                        class="block px-3 py-2.5 text-base font-semibold rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                        :class="route().current('public.destinasi') || route().current('public.tourism.index') ? 'bg-emerald-50 dark:bg-emerald-950/30 text-[#0F5E3D] dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'"
                        @click="isMobileMenuOpen = false"
                    >
                        Direktori Wisata
                    </Link>
                    <Link 
                        :href="route('public.news.index')" 
                        class="block px-3 py-2.5 text-base font-semibold rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                        :class="route().current('public.news.index') ? 'bg-emerald-50 dark:bg-emerald-950/30 text-[#0F5E3D] dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'"
                        @click="isMobileMenuOpen = false"
                    >
                        Berita
                    </Link>
                    <Link 
                        :href="route('public.gallery.index')" 
                        class="block px-3 py-2.5 text-base font-semibold rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                        :class="route().current('public.gallery.index') ? 'bg-emerald-50 dark:bg-emerald-950/30 text-[#0F5E3D] dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'"
                        @click="isMobileMenuOpen = false"
                    >
                        Galeri
                    </Link>

                    <!-- Collapsible Layanan (Mobile) -->
                    <div class="space-y-1">
                        <button 
                            @click="isMobileLayananOpen = !isMobileLayananOpen"
                            class="w-full flex items-center justify-between px-3 py-2.5 text-base font-semibold rounded-lg text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/20 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                        >
                            <span>Layanan</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': isMobileLayananOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div v-show="isMobileLayananOpen" class="pl-4 space-y-1 border-l-2 border-emerald-900/10 dark:border-emerald-500/20 ml-3">
                            <Link 
                                :href="route('layanan-masyarakat.complaints.create')" 
                                class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-md hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                                @click="isMobileMenuOpen = false"
                            >
                                <svg class="w-4.5 h-4.5 text-[#0F5E3D] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                <span>Laporan Pengaduan</span>
                            </Link>
                            <Link 
                                :href="route('layanan-masyarakat.tourism-submissions.create')" 
                                class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-md hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                                @click="isMobileMenuOpen = false"
                            >
                                <svg class="w-4.5 h-4.5 text-[#0F5E3D] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                <span>Usul Wisata Baru</span>
                            </Link>
                            <Link 
                                :href="route('layanan-masyarakat.event-broadcasts.create')" 
                                class="flex items-center space-x-3 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 rounded-md hover:bg-emerald-50 dark:hover:bg-emerald-950/10 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                                @click="isMobileMenuOpen = false"
                            >
                                <svg class="w-4.5 h-4.5 text-[#0F5E3D] dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Pengajuan Event</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- CTAs (Mobile) -->
                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 space-y-2">
                    <template v-if="page.props.auth?.user">
                        <Link 
                            :href="route('dashboard')" 
                            class="block w-full text-center px-4 py-2.5 text-base font-bold text-white bg-emerald-700 rounded-asymmetric-sm hover:bg-emerald-800 transition"
                            @click="isMobileMenuOpen = false"
                        >
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link 
                            :href="route('login')" 
                            class="block w-full text-center px-4 py-2.5 text-base font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition rounded-lg"
                            @click="isMobileMenuOpen = false"
                        >
                            Masuk
                        </Link>
                        <Link 
                            :href="route('register')" 
                            class="block w-full text-center px-4 py-2.5 text-base font-bold text-white bg-emerald-700 rounded-asymmetric-sm hover:bg-emerald-800 transition"
                            @click="isMobileMenuOpen = false"
                        >
                            Daftar
                        </Link>
                    </template>
                </div>
            </div>
        </transition>
    </nav>
</template>
