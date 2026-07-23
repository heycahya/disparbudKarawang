<script setup>
import ToastNotification from '@/Components/ToastNotification.vue';
import { ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingSidebarMobile = ref(false);

const page = usePage();
const successMessage = ref(null);
const errorMessage = ref(null);

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            successMessage.value = flash.success;
            setTimeout(() => {
                successMessage.value = null;
            }, 5000);
        }
        if (flash?.error) {
            errorMessage.value = flash.error;
            setTimeout(() => {
                errorMessage.value = null;
            }, 5000);
        }
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <div class="h-screen max-h-screen overflow-hidden bg-slate-50 dark:bg-slate-950 font-sans flex text-slate-800 dark:text-slate-200">
        
        <!-- Sidebar: Desktop Left Navigation (w-64) -->
        <aside 
            class="hidden lg:flex flex-col w-64 h-screen shrink-0 bg-gradient-to-b from-[#0F5E3D] to-emerald-950 text-white border-r border-emerald-800/20 relative z-20"
        >
            <!-- Pattern -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-5 pointer-events-none"></div>

            <!-- Logo Branding Section -->
            <div class="p-6 border-b border-white/10 relative z-10">
                <Link :href="$page.props.auth.user?.role === 'admin' ? route('admin.dashboard') : route('dashboard')" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-md group-hover:scale-105 transition-transform duration-300">
                        <ApplicationLogo class="w-7 h-7" />
                    </div>
                    <div>
                        <span class="text-xs font-black tracking-wider block">VIBE <span class="text-amber-400">KARAWANG</span></span>
                        <span class="text-[9px] text-emerald-200/80 tracking-widest uppercase block -mt-0.5 font-bold">Portal Mandiri</span>
                    </div>
                </Link>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto relative z-10">
                
                <!-- General Link -->
                <Link 
                    :href="$page.props.auth.user?.role === 'public' ? route('dashboard') : route('admin.dashboard')"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                    :class="(route().current('dashboard') || route().current('admin.dashboard')) 
                        ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                        : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Dashboard</span>
                </Link>

                <!-- Public Roles Link -->
                <template v-if="$page.props.auth.user?.role === 'public'">
                    <Link 
                        :href="route('public.history.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('public.history.index') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Riwayat Pengajuan</span>
                    </Link>
                </template>

                <!-- Admin Links -->
                <template v-if="$page.props.auth.user?.role === 'admin'">
                    <div class="pt-4 pb-2 px-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300/60 block">Layanan Masyarakat</span>
                    </div>

                    <!-- Verifikasi Layanan -->
                    <Link 
                        :href="route('admin.verifikasi-layanan.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.verifikasi-layanan.*') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Verifikasi Layanan</span>
                    </Link>

                    <div class="pt-4 pb-2 px-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300/60 block">Manajemen Konten</span>
                    </div>

                    <!-- Berita -->
                    <Link 
                        :href="route('admin.news.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.news.*') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 4a2 2 0 00-2-2m2 2a2 2 0 11-4 0V7a2 2 0 012-2h2a2 2 0 012 2v3z" />
                        </svg>
                        <span>Katalog Berita</span>
                    </Link>

                    <!-- Destinasi -->
                    <Link 
                        :href="route('admin.tourism-destinations.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.tourism-destinations.*') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Destinasi Wisata</span>
                    </Link>

                    <!-- Kebudayaan -->
                    <Link 
                        :href="route('admin.cultures.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.cultures.*') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Seni & Budaya</span>
                    </Link>

                    <!-- Ekraf -->
                    <Link 
                        :href="route('admin.creative-economies.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.creative-economies.*') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Ekonomi Kreatif</span>
                    </Link>

                    <!-- Akomodasi -->
                    <Link 
                        :href="route('admin.accommodations.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.accommodations.*') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Akomodasi</span>
                    </Link>

                    <!-- Kuliner -->
                    <Link 
                        :href="route('admin.culinary-places.index')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.culinary-places.*') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                        </svg>
                        <span>Kuliner Lokal</span>
                    </Link>

                    <!-- Akun -->
                    <div class="pt-4 pb-2 px-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300/60 block">Sistem</span>
                    </div>
                    <Link 
                        :href="route('admin.manajemen-akun')"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.manajemen-akun') 
                            ? 'bg-amber-400 text-slate-900 shadow-md shadow-amber-400/20' 
                            : 'text-emerald-100 hover:bg-white/10 hover:text-white'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Manajemen Akun</span>
                    </Link>
                </template>
            </nav>

            <!-- Sidebar Footer User Profile -->
            <div class="p-4 border-t border-white/10 relative z-10 bg-emerald-950/40">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs font-bold truncate">{{ $page.props.auth.user.name }}</span>
                        <span class="block text-[10px] text-emerald-300/70 truncate capitalize">{{ $page.props.auth.user.role }}</span>
                    </div>
                </div>
                <Link 
                    :href="route('logout')" 
                    method="post" 
                    as="button" 
                    class="w-full flex items-center justify-center gap-2 py-2.5 px-3 bg-rose-600/80 hover:bg-rose-600 text-white font-bold text-xs rounded-xl transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar Akun
                </Link>
            </div>
        </aside>

        <!-- Mobile Sidebar Drawer Overlay -->
        <div 
            v-if="showingSidebarMobile" 
            @click="showingSidebarMobile = false"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden transition-opacity animate-fade-in"
        ></div>

        <!-- Mobile Sidebar Drawer -->
        <aside 
            class="fixed top-0 bottom-0 left-0 z-50 w-64 flex flex-col bg-gradient-to-b from-[#0F5E3D] to-emerald-950 text-white border-r border-emerald-800/20 transform transition-transform duration-300 ease-in-out lg:hidden"
            :class="showingSidebarMobile ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Pattern -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-5 pointer-events-none"></div>

            <!-- Logo Branding Section -->
            <div class="p-6 border-b border-white/10 relative z-10 flex justify-between items-center">
                <Link :href="$page.props.auth.user?.role === 'admin' ? route('admin.dashboard') : route('dashboard')" class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center border border-white/20 shadow-md">
                        <ApplicationLogo class="w-6 h-6" />
                    </div>
                    <div>
                        <span class="text-xs font-black tracking-wider block">VIBE <span class="text-amber-400">KARAWANG</span></span>
                        <span class="text-[8px] text-emerald-200/80 tracking-widest uppercase block -mt-0.5 font-bold">Portal Mandiri</span>
                    </div>
                </Link>
                <button 
                    @click="showingSidebarMobile = false" 
                    class="text-white hover:text-amber-400"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto relative z-10">
                <Link 
                    :href="$page.props.auth.user?.role === 'public' ? route('dashboard') : route('admin.dashboard')"
                    @click="showingSidebarMobile = false"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                    :class="(route().current('dashboard') || route().current('admin.dashboard')) 
                        ? 'bg-amber-400 text-slate-900' 
                        : 'text-emerald-100 hover:bg-white/10'"
                >
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span>Dashboard</span>
                </Link>

                <template v-if="$page.props.auth.user?.role === 'public'">
                    <Link 
                        :href="route('public.history.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('public.history.index') 
                            ? 'bg-amber-400 text-slate-900' 
                            : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Riwayat Pengajuan</span>
                    </Link>
                </template>

                <template v-if="$page.props.auth.user?.role === 'admin'">
                    <div class="pt-4 pb-2 px-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300/60 block">Layanan Masyarakat</span>
                    </div>

                    <Link 
                        :href="route('admin.verifikasi-layanan.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.verifikasi-layanan.*') 
                            ? 'bg-amber-400 text-slate-900' 
                            : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Verifikasi Layanan</span>
                    </Link>

                    <div class="pt-4 pb-2 px-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300/60 block">Manajemen Konten</span>
                    </div>

                    <!-- Berita -->
                    <Link 
                        :href="route('admin.news.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.news.*') ? 'bg-amber-400 text-slate-900' : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <span>Katalog Berita</span>
                    </Link>

                    <!-- Destinasi -->
                    <Link 
                        :href="route('admin.tourism-destinations.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.tourism-destinations.*') ? 'bg-amber-400 text-slate-900' : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <span>Destinasi Wisata</span>
                    </Link>

                    <!-- Kebudayaan -->
                    <Link 
                        :href="route('admin.cultures.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.cultures.*') ? 'bg-amber-400 text-slate-900' : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <span>Seni & Budaya</span>
                    </Link>

                    <!-- Ekraf -->
                    <Link 
                        :href="route('admin.creative-economies.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.creative-economies.*') ? 'bg-amber-400 text-slate-900' : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <span>Ekonomi Kreatif</span>
                    </Link>

                    <!-- Akomodasi -->
                    <Link 
                        :href="route('admin.accommodations.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.accommodations.*') ? 'bg-amber-400 text-slate-900' : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <span>Akomodasi</span>
                    </Link>

                    <!-- Kuliner -->
                    <Link 
                        :href="route('admin.culinary-places.index')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.culinary-places.*') ? 'bg-amber-400 text-slate-900' : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <span>Kuliner Lokal</span>
                    </Link>

                    <!-- Akun -->
                    <div class="pt-4 pb-2 px-4">
                        <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300/60 block">Sistem</span>
                    </div>
                    <Link 
                        :href="route('admin.manajemen-akun')"
                        @click="showingSidebarMobile = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all"
                        :class="route().current('admin.manajemen-akun') ? 'bg-amber-400 text-slate-900' : 'text-emerald-100 hover:bg-white/10'"
                    >
                        <span>Manajemen Akun</span>
                    </Link>
                </template>
            </nav>

            <!-- Sidebar Footer Profile -->
            <div class="p-4 border-t border-white/10 relative z-10 bg-emerald-950/40">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded bg-white/10 flex items-center justify-center font-bold text-xs">
                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs font-bold truncate">{{ $page.props.auth.user.name }}</span>
                    </div>
                </div>
                <Link 
                    :href="route('logout')" 
                    method="post" 
                    as="button" 
                    class="w-full flex items-center justify-center gap-2 py-2 px-3 bg-rose-600/80 hover:bg-rose-600 text-white font-bold text-xs rounded-xl transition"
                >
                    Keluar Akun
                </Link>
            </div>
        </aside>

        <!-- Right Side: Content Wrapper (Independent Scroll) -->
        <div class="flex-1 h-screen flex flex-col min-w-0 overflow-y-auto">
            
            <!-- Top Nav Bar -->
            <header class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800/80 h-16 flex items-center justify-between px-6 shrink-0 relative z-30 shadow-sm">
                
                <!-- Left: Hamburger or Breadcrumb -->
                <div class="flex items-center gap-4">
                    <button 
                        @click="showingSidebarMobile = true" 
                        class="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 lg:hidden"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Page Header Title Slot fallback -->
                    <div class="hidden lg:block">
                        <span class="text-xs text-slate-400 dark:text-slate-500 font-semibold tracking-wider uppercase">Vibe Karawang Portal</span>
                    </div>
                </div>

                <!-- Right: Profile & View Public Portal Button -->
                <div class="flex items-center gap-4">
                    <a 
                        :href="route('public.home')" 
                        target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-900/50 text-xs font-bold hover:bg-emerald-100 transition shadow-sm"
                    >
                        <span>🌐 Lihat Portal Utama</span>
                    </a>

                    <!-- Profile Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-xl bg-slate-50 dark:bg-slate-800 px-3 py-1.5 border border-slate-200/50 dark:border-slate-700/50 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100/70 dark:hover:bg-slate-700 transition"
                            >
                                <span class="w-6 h-6 rounded-lg bg-emerald-700 text-white flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                </span>
                                <span class="hidden sm:inline truncate max-w-[120px]">{{ $page.props.auth.user.name }}</span>
                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profil Saya</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Keluar Akun</DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Main Page Layout Content -->
            <main class="flex-1 relative overflow-y-auto">
                <slot />
            </main>
        </div>

        <!-- Global Floating Toast Notification -->
        <ToastNotification />
    </div>
</template>
