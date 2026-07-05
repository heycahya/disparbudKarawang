<script setup>
import { Head, Link } from '@inertiajs/vue3';
import HamburgerMenu from '@/Components/HamburgerMenu.vue';
import FooterGlobal from '@/Components/FooterGlobal.vue';
import DestinationCard from '@/Components/DestinationCard.vue';
import NewsCard from '@/Components/NewsCard.vue';
import StatBadge from '@/Components/StatBadge.vue';

const props = defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    stats: {
        type: Object,
        default: () => ({
            total_destinations: 0,
            total_news: 0,
            total_cultures: 0,
        }),
    },
    featured_destinations: {
        type: Array,
        default: () => [],
    },
    latest_news: {
        type: Array,
        default: () => [],
    },
});

const aspectRatioMap = [
    'aspect-[4/5]',   // index 0
    'aspect-[3/4]',   // index 1
    'aspect-square',  // index 2
    'aspect-[4/5]',   // index 3
    'aspect-square',  // index 4
    'aspect-[4/5]',   // index 5
];

const getAspectRatio = (index) => aspectRatioMap[index] ?? 'aspect-[4/5]';
</script>

<template>
    <Head title="Disparbud Kabupaten Karawang — Pesona Budaya & Wisata" />

    <div class="min-h-screen flex flex-col bg-white text-sanggabuana font-sans antialiased selection:bg-karawang-emerald/10 selection:text-karawang-emerald">
        <!-- 1. Header & Global Navbar Minimalis -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-xs border-b border-sanggabuana-hairline h-16 px-6 lg:px-20 transition-all">
            <div class="max-w-screen-xl mx-auto h-full flex items-center justify-between">
                <!-- Logo Branding -->
                <Link href="/" class="text-base font-bold text-sanggabuana tracking-tight hover:opacity-90 transition-opacity">
                    Disparbud <span class="text-karawang-emerald">Karawang</span>
                </Link>

                <!-- Navigation Control: Single Interactive Hamburger Menu -->
                <HamburgerMenu
                    :canLogin="canLogin"
                    :canRegister="canRegister"
                    :isLoggedIn="!!$page.props.auth.user"
                />
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-grow">
            <!-- 2. Hero Section Lapang & Minimalis -->
            <section class="py-16 lg:py-24 bg-white border-b border-sanggabuana-hairline/50">
                <div class="max-w-screen-xl mx-auto px-6 lg:px-20">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <!-- Left Hero Content (7 cols desktop) -->
                        <div class="lg:col-span-7">
                            <p class="text-xs font-semibold uppercase tracking-widest text-karawang-emerald mb-3">
                                Portal Resmi Disparbud Karawang
                            </p>

                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-sanggabuana leading-tight tracking-tight max-w-lg">
                                Jelajahi Budaya & Pariwisata Karawang
                            </h1>

                            <p class="mt-4 text-sm sm:text-base text-sanggabuana-muted leading-relaxed max-w-md">
                                Temukan keindahan destinasi wisata alam, warisan sejarah kekayaan budaya, dan berita terkini dalam satu pintu portal resmi.
                            </p>

                            <!-- StatBadge Minimalis Row -->
                            <div class="flex flex-wrap gap-3 sm:gap-4 mt-8">
                                <StatBadge
                                    label="Destinasi Wisata"
                                    :value="stats.total_destinations ?? 0"
                                />
                                <StatBadge
                                    label="Artikel Berita"
                                    :value="stats.total_news ?? 0"
                                />
                                <StatBadge
                                    label="Kekayaan Budaya"
                                    :value="stats.total_cultures ?? 0"
                                />
                            </div>

                            <!-- Clean Inline CTA Link -->
                            <div class="mt-8">
                                <Link
                                    href="/informasi"
                                    class="inline-flex items-center gap-2 text-sm font-semibold text-karawang-emerald hover:text-karawang-emerald-active transition-colors group"
                                >
                                    <span>Jelajahi Semua Destinasi</span>
                                    <svg
                                        class="w-4 h-4 transform group-hover:translate-x-1 transition-transform"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </Link>
                            </div>
                        </div>

                        <!-- Right Hero Image Plate (5 cols desktop) -->
                        <div class="lg:col-span-5">
                            <div class="relative aspect-[4/5] rounded-tr-3xl rounded-bl-3xl overflow-hidden bg-sanggabuana-soft shadow-xs">
                                <img
                                    :src="featured_destinations[0]?.cover_image ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'"
                                    alt="Destinasi Unggulan Karawang"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Section Destinasi Unggulan (Pinterest Asymmetric Grid) -->
            <section class="py-16 lg:py-20 bg-white">
                <div class="max-w-screen-xl mx-auto px-6 lg:px-20">
                    <!-- Section Header -->
                    <div class="flex items-end justify-between mb-8">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-sanggabuana">
                                Destinasi Unggulan
                            </h2>
                            <p class="mt-1 text-xs sm:text-sm text-sanggabuana-muted">
                                Rekomendasi tempat wisata favorit pengunjung Karawang
                            </p>
                        </div>
                        <Link
                            href="/informasi"
                            class="text-xs sm:text-sm font-semibold text-sanggabuana-muted hover:text-karawang-emerald transition-colors"
                        >
                            Lihat semua →
                        </Link>
                    </div>

                    <!-- Grid Pinterest-style -->
                    <div v-if="featured_destinations && featured_destinations.length > 0" class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <DestinationCard
                            v-for="(destination, index) in featured_destinations"
                            :key="destination.id"
                            :destination="destination"
                            :aspectRatio="getAspectRatio(index)"
                        />
                    </div>

                    <!-- State Kosong / Placeholder (jika belum ada data) -->
                    <div v-else class="text-center py-12 border border-dashed border-sanggabuana-hairline rounded-xl">
                        <p class="text-sm text-sanggabuana-muted">Belum ada destinasi dipublikasikan.</p>
                    </div>
                </div>
            </section>

            <!-- 4. Section Berita Terbaru (Airbnb Clean Cards Grid) -->
            <section class="py-16 lg:py-20 bg-white border-t border-sanggabuana-hairline/40">
                <div class="max-w-screen-xl mx-auto px-6 lg:px-20">
                    <!-- Section Header -->
                    <div class="flex items-end justify-between mb-8">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-sanggabuana">
                                Berita & Informasi Terbaru
                            </h2>
                            <p class="mt-1 text-xs sm:text-sm text-sanggabuana-muted">
                                Kabar terkini seputar kegiatan dan perkembangan pariwisata
                            </p>
                        </div>
                        <Link
                            href="/berita"
                            class="text-xs sm:text-sm font-semibold text-sanggabuana-muted hover:text-karawang-emerald transition-colors"
                        >
                            Lihat semua →
                        </Link>
                    </div>

                    <!-- Grid Berita 4 Columns -->
                    <div v-if="latest_news && latest_news.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                        <NewsCard
                            v-for="article in latest_news"
                            :key="article.id"
                            :article="article"
                        />
                    </div>

                    <!-- State Kosong / Placeholder -->
                    <div v-else class="text-center py-12 border border-dashed border-sanggabuana-hairline rounded-xl">
                        <p class="text-sm text-sanggabuana-muted">Belum ada berita dipublikasikan.</p>
                    </div>
                </div>
            </section>

            <!-- 5. Section CTA Service Rakyat Ultra Minimalis -->
            <section class="py-16 lg:py-20 bg-white">
                <div class="max-w-screen-xl mx-auto px-6 lg:px-20">
                    <div class="border border-sanggabuana-hairline rounded-2xl p-8 lg:p-12 text-center bg-white">
                        <p class="text-xs uppercase tracking-widest text-sanggabuana-muted font-semibold">
                            Layanan Masyarakat
                        </p>
                        <h2 class="mt-3 text-2xl sm:text-3xl font-bold text-sanggabuana max-w-md mx-auto">
                            Punya Aspirasi atau Temuan Destinasi Baru?
                        </h2>
                        <p class="mt-3 text-sm text-sanggabuana-muted max-w-sm mx-auto leading-relaxed">
                            Sampaikan pengaduan, usulkan destinasi wisata baru, atau ajukan permohonan penyiaran event Anda via Service Rakyat.
                        </p>
                        <div class="mt-8">
                            <Link
                                :href="canLogin ? route('login') : '#'"
                                class="inline-flex items-center justify-center px-6 py-3 bg-karawang-emerald text-white text-sm font-semibold rounded-asymmetric-sm hover:bg-karawang-emerald-active transition-colors shadow-xs"
                            >
                                Mulai Pengajuan Layanan
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- 6. Global Minimalist Footer -->
        <FooterGlobal />
    </div>
</template>
