<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <nav class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-md sticky top-0 z-50 border-b border-emerald-900/10 dark:border-emerald-100/10 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Branding -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-tr-xl rounded-bl-xl bg-gradient-to-br from-[#0F5E3D] to-[#0C4E5B] flex items-center justify-center text-white font-extrabold shadow-md">
                        DK
                    </div>
                    <div>
                        <Link :href="route('public.home')" class="text-xl font-bold tracking-tight text-gray-900 dark:text-white hover:text-[#0F5E3D] transition">
                            Disparbud <span class="text-[#0F5E3D] dark:text-emerald-400">Karawang</span>
                        </Link>
                        <p class="text-[10px] uppercase font-bold tracking-wider text-[#D97706]">Pariwisata & Kebudayaan</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
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
                </div>

                <!-- Right Action Buttons -->
                <div class="flex items-center space-x-3">
                    <template v-if="page.props.auth?.user">
                        <Link 
                            :href="route('dashboard')" 
                            class="px-4 py-2 text-sm font-bold text-white bg-[#0F5E3D] rounded-tr-xl rounded-bl-xl hover:bg-[#0C4E5B] shadow-md hover:shadow-lg transition duration-200"
                        >
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link 
                            :href="route('login')" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition"
                        >
                            Masuk
                        </Link>
                        <Link 
                            :href="route('register')" 
                            class="px-4 py-2 text-sm font-bold text-white bg-[#0F5E3D] rounded-tr-xl rounded-bl-xl hover:bg-[#0C4E5B] shadow-md hover:shadow-lg transition duration-200"
                        >
                            Daftar
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>
