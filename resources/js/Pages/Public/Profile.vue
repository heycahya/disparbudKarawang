<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/PublicNavbar.vue';
import FooterGlobal from '@/Components/FooterGlobal.vue';

defineProps({
    organization: {
        type: Object,
        default: () => ({
            profile: '',
            vision_mission: { vision: '', mission: '' },
            tupoksi: [],
            boards: [],
            details: {}
        })
    }
});
</script>

<template>
    <Head title="Profil Lembaga - Disparbud Karawang" />

    <div class="min-h-screen bg-rice-husk dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex flex-col font-sans">
        <PublicNavbar />

        <!-- Profile Header Banner -->
        <header class="bg-gradient-to-r from-[#0F5E3D] via-[#0C4E5B] to-emerald-950 py-16 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-10"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#D97706] bg-white/10 backdrop-blur-md px-3.5 py-1 rounded-full border border-white/20">
                    Kelembagaan & Organisasi
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold mt-3 tracking-tight">Profil Dinas Pariwisata & Kebudayaan</h1>
                <p class="text-emerald-100/90 max-w-2xl mt-2 text-sm sm:text-base">
                    Landasan hukum, struktur kepemimpinan, visi misi, serta fungsi strategis pembangunan pariwisata Kabupaten Karawang.
                </p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16 w-full">
            
            <!-- Vision & Mission Section -->
            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Vision Card -->
                <div class="bg-white dark:bg-gray-900 rounded-tr-[2.5rem] rounded-bl-[2.5rem] rounded-tl-lg rounded-br-lg p-8 shadow-md border border-gray-100 dark:border-gray-800 space-y-4">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950 text-[#0F5E3D] rounded-full flex items-center justify-center font-bold text-xl">
                        🎯
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Visi Pembangunan</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-sm sm:text-base italic bg-emerald-50/50 dark:bg-emerald-950/20 p-4 rounded-xl border-l-4 border-[#0F5E3D]">
                        "{{ organization.vision_mission?.vision || 'Terwujudnya Ekonomi Kerakyatan yang Kreatif, Produktif dan Berdaya Saing serta Berbasis pada Potensi Lokal' }}"
                    </p>
                </div>

                <!-- Mission Card -->
                <div class="bg-white dark:bg-gray-900 rounded-tr-[2.5rem] rounded-bl-[2.5rem] rounded-tl-lg rounded-br-lg p-8 shadow-md border border-gray-100 dark:border-gray-800 space-y-4">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-950 text-[#D97706] rounded-full flex items-center justify-center font-bold text-xl">
                        🚀
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Misi Utama</h2>
                    <div class="text-gray-700 dark:text-gray-300 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                        {{ organization.vision_mission?.mission || '1. Mendorong pertumbuhan ekonomi kerakyatan berbasis pariwisata dan ekonomi kreatif.\n2. Melestarikan dan mengembangkan potensi kebudayaan lokal Karawang.\n3. Meningkatkan tata kelola kelembagaan dan kualitas pelayanan publik sektor pariwisata.' }}
                    </div>
                </div>
            </section>

            <!-- Board Members Section (Pengurus Riil) -->
            <section class="space-y-8">
                <div class="border-b border-gray-200 dark:border-gray-800 pb-4">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#0F5E3D]">Struktur Organisasi</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Jajaran Pimpinan & Pengurus Dinas</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="board in organization.boards" 
                        :key="board.id || board.name" 
                        class="bg-white dark:bg-gray-900 rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-lg rounded-br-lg p-6 shadow-md border border-gray-100 dark:border-gray-800 text-center space-y-4 hover:shadow-lg transition duration-200 transform hover:-translate-y-1"
                    >
                        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-[#0F5E3D] to-[#0C4E5B] p-1 shadow-md">
                            <img 
                                v-if="board.photo" 
                                :src="board.photo" 
                                :alt="board.name" 
                                class="w-full h-full object-cover rounded-full"
                            >
                            <div v-else class="w-full h-full rounded-full bg-slate-100 dark:bg-gray-800 flex items-center justify-center text-2xl">
                                👤
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ board.name }}</h3>
                            <p class="text-xs font-semibold text-[#0F5E3D] dark:text-emerald-400 mt-1 uppercase tracking-wide">
                                {{ board.position }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Profile & Sejarah Brief -->
            <section class="bg-white dark:bg-gray-900 rounded-tr-[3rem] rounded-bl-[3rem] rounded-tl-lg rounded-br-lg p-8 sm:p-12 shadow-md border border-gray-100 dark:border-gray-800 space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-8 bg-[#0F5E3D] rounded-full"></div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Sejarah & Landasan Operasional</h2>
                </div>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-sm sm:text-base">
                    {{ organization.profile || 'Institusi ini dikukuhkan pertama kali lewat Perda No. 10/2008, mengalami revisi regulasi lewat Perda No. 14/2016, dirombak lewat Perda No. 11/2021, dengan landasan operasional Perbup No. 70/2021 (diamandemen Perbup No. 430/2023). Berfokus penuh pada pengembangan destinasi pariwisata, seni budaya, serta ekosistem ekonomi kreatif di Kabupaten Karawang.' }}
                </p>
            </section>

            <!-- Tupoksi Section -->
            <section class="space-y-8">
                <div class="border-b border-gray-200 dark:border-gray-800 pb-4">
                    <span class="text-xs font-extrabold uppercase tracking-wider text-[#0C4E5B]">Tugas Pokok & Fungsi</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Tupoksi Strategis Dinas</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div 
                        v-for="(fnItem, idx) in organization.tupoksi" 
                        :key="fnItem.id || idx" 
                        class="bg-white dark:bg-gray-900 rounded-tr-[2rem] rounded-bl-[2rem] rounded-tl-lg rounded-br-lg p-6 shadow-sm border border-gray-100 dark:border-gray-800 flex gap-4 items-start"
                    >
                        <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950 text-[#0C4E5B] dark:text-teal-400 font-extrabold flex items-center justify-center shrink-0">
                            {{ idx + 1 }}
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ fnItem.title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ fnItem.description }}</p>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <FooterGlobal />
    </div>
</template>
