<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import LeafletMap from '@/Components/LeafletMap.vue';

const props = defineProps({
    type: { type: String, default: 'tourism' },
    item: { type: Object, required: true },
    destination: { type: Object, default: null },
    photos: { type: Array, default: () => [] },
    relatedItems: { type: Array, default: () => [] },
    seo: { type: Object, default: () => ({}) },
});

const data = computed(() => props.item ?? props.destination ?? {});
const activeType = computed(() => props.type ?? 'tourism');

const entityName = computed(() => data.value?.name ?? data.value?.title ?? '');
const entityDescription = computed(() => data.value?.description ?? '');
const entityAddress = computed(() => data.value?.address ?? '');
const coverImage = computed(() => data.value?.cover_image ?? null);
const isLocatable = computed(() => ['tourism', 'accommodation', 'culinary'].includes(activeType.value));

// Image error fallback handling
const FALLBACK_IMG = 'https://res.cloudinary.com/mabhpcw6/image/upload/c_fill,g_auto,w_800,h_500,f_auto,q_auto/gallery_placeholder.jpg';
const brokenImages = ref({});
const handleImageError = (index, event) => {
    if (event && event.target) {
        event.target.src = FALLBACK_IMG;
    }
    brokenImages.value[index] = true;
};

// Build photo slides: use props.photos if provided, else fallback to single cover
const slides = computed(() => {
    if (props.photos && props.photos.length > 0) return props.photos;
    if (coverImage.value) return [{ url: coverImage.value, caption: entityName.value }];
    return [{ url: FALLBACK_IMG, caption: entityName.value }];
});

// Slider state
const currentSlide = ref(0);
const goTo = (i) => { currentSlide.value = i; };
const prev = () => { currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length; };
const next = () => { currentSlide.value = (currentSlide.value + 1) % slides.value.length; };

// Accordion collapsible state
const openAccordions = ref({
    facilities: true,
    guidelines: false,
    access: false,
});
const toggleAccordion = (key) => {
    openAccordions.value[key] = !openAccordions.value[key];
};

const typeConfig = computed(() => {
    const map = {
        tourism: {
            label: 'Destinasi Wisata', materialIcon: 'landscape',
            badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005F4A] dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
            sectionTitle: 'Rekomendasi Destinasi Wisata Lainnya',
            homeSectionHash: '#section-wisata',
        },
        culture: {
            label: 'Seni & Budaya', materialIcon: 'theater_comedy',
            badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005F4A] dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
            sectionTitle: 'Karya Seni & Kebudayaan Lainnya',
            homeSectionHash: '#section-budaya',
        },
        ekraf: {
            label: 'Ekonomi Kreatif', materialIcon: 'palette',
            badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005F4A] dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
            sectionTitle: 'Usaha Ekonomi Kreatif Lainnya',
            homeSectionHash: '#section-ekraf',
        },
        accommodation: {
            label: 'Akomodasi', materialIcon: 'hotel',
            badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005F4A] dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
            sectionTitle: 'Pilihan Akomodasi & Penginapan Lainnya',
            homeSectionHash: '#section-akomodasi-kuliner',
        },
        culinary: {
            label: 'Kuliner Lokal', materialIcon: 'restaurant',
            badgeClass: 'bg-emerald-50 dark:bg-emerald-950/60 text-[#005F4A] dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60',
            sectionTitle: 'Rekomendasi Kuliner Khas Lainnya',
            homeSectionHash: '#section-kuliner',
        },
    };
    return map[activeType.value] ?? map.tourism;
});

const getCultureCategoryLabel = (cat) => {
    const map = { kesenian: 'Kesenian', tradisi: 'Tradisi', warisan_budaya: 'Warisan Budaya' };
    return map[cat] ?? cat ?? '-';
};
const getTypeLabel = (type) => {
    const map = {
        hotel: 'Hotel', villa: 'Villa', homestay: 'Homestay', penginapan: 'Penginapan',
        restoran: 'Restoran', cafe: 'Cafe', warung: 'Warung', rumah_makan: 'Rumah Makan',
    };
    return map[type] ?? type ?? '-';
};

// Dynamic Route Generator for Related Items
const getDetailRoute = (itemSlug) => {
    const map = {
        tourism: 'public.tourism.show',
        culture: 'public.culture.show',
        ekraf: 'public.ekraf.show',
        accommodation: 'public.accommodation.show',
        culinary: 'public.culinary.show',
    };
    const routeName = map[activeType.value] || 'public.tourism.show';
    return route(routeName, itemSlug);
};

// Single unified category name
const displayCategoryName = computed(() => {
    if (activeType.value === 'tourism' && data.value.category?.name) {
        return data.value.category.name;
    }
    if (activeType.value === 'culture' && data.value.category) {
        return getCultureCategoryLabel(data.value.category);
    }
    if ((activeType.value === 'accommodation' || activeType.value === 'culinary') && data.value.type) {
        return getTypeLabel(data.value.type);
    }
    return typeConfig.value.label;
});

const mapDestinations = computed(() => {
    if (isLocatable.value && data.value?.latitude && data.value?.longitude) {
        return [{
            id: data.value.id,
            name: entityName.value,
            slug: data.value.slug,
            latitude: parseFloat(data.value.latitude),
            longitude: parseFloat(data.value.longitude),
            address: entityAddress.value,
            image_url: coverImage.value,
            category: displayCategoryName.value,
        }];
    }
    return [];
});

const hasMap = computed(() => isLocatable.value && mapDestinations.value.length > 0);

const googleMapsUrl = computed(() => {
    if (data.value?.latitude && data.value?.longitude) {
        return `https://www.google.com/maps/search/?api=1&query=${data.value.latitude},${data.value.longitude}`;
    }
    if (entityAddress.value) {
        return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(entityName.value + ' ' + entityAddress.value)}`;
    }
    return null;
});

// Contact link for direct CTA (Ekraf, Culinary, Accommodation)
const contactUrl = computed(() => {
    const rawContact = data.value?.contact || data.value?.phone;
    if (!rawContact) return null;
    const cleanNum = rawContact.replace(/[^0-9]/g, '');
    if (cleanNum.startsWith('0')) {
        return `https://wa.me/62${cleanNum.substring(1)}`;
    }
    if (cleanNum.startsWith('62')) {
        return `https://wa.me/${cleanNum}`;
    }
    return `tel:${cleanNum}`;
});

// Highlights checklist feature items
const defaultHighlights = computed(() => {
    if (activeType.value === 'ekraf') {
        return [
            'Produk Kreatif Original Karawang', 'Bisa Pesan Custom / Pre-Order',
            'Hasil Karya UMKM & Pengrajin Lokal', 'Jaminan Kualitas Produk & Bahan',
            'Tersedia Kanal Pemesanan Langsung', 'Siap Kirim Luar Kota / Ekspedisi'
        ];
    }
    if (activeType.value === 'culinary') {
        return [
            'Cita Rasa Asli Khas Karawang', 'Bahan Terjamin Higienis & Halal',
            'Tempat Makan Nyaman & Bersih', 'Fasilitas Area Parkir Kendaraan',
            'Pelayanan Cepat & Ramah', 'Cocok untuk Keluarga & Rombongan'
        ];
    }
    if (activeType.value === 'accommodation') {
        return [
            'Area Parkir Luas & Aman', 'Fasilitas Kamar Nyaman & Bersih',
            'Layanan Resepsionis & Keamanan 24 Jam', 'Akses Wi-Fi Gratis di Area Umum',
            'Lokasi Strategis & Dekat Wisata', 'Ruang Santai & Restoran'
        ];
    }
    return [
        'Area Parkir Kendaraan Luas', 'Fasilitas Musala & Toilet Umum',
        'Spot Foto Menarik & Instagenic', 'Pemandu & Informasi Wisata Lokal',
        'Akses Jalan Terjangkau Kendaraan', 'Kios Makanan & Souvenir Lokal'
    ];
});
</script>

<template>
    <Head>
        <title>{{ seo.title || entityName }} | Disparbud Karawang</title>
        <meta name="description" :content="seo.description || ''" />
        <meta property="og:title" :content="seo.title || entityName" />
        <meta property="og:description" :content="seo.description || ''" />
        <meta v-if="seo.image" property="og:image" :content="seo.image" />
        <meta property="og:type" :content="seo.type || 'website'" />
    </Head>

    <PublicLayout>
        <div class="bg-gray-50/60 dark:bg-gray-950 min-h-screen pb-20">

            <!-- ── 1. STICKY BACK BAR (TOPMOST) ── -->
            <div class="bg-white dark:bg-gray-900 border-b border-gray-200/80 dark:border-gray-800 sticky top-16 z-30 shadow-2xs">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between">
                    <a
                        :href="route('public.home')"
                        rel="external"
                        class="inline-flex items-center gap-2 text-xs font-bold text-gray-700 dark:text-gray-300 hover:text-[#005F4A] dark:hover:text-emerald-400 transition-colors group"
                    >
                        <span class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-emerald-50 dark:group-hover:bg-emerald-950/60 transition-colors">
                            <span class="material-symbols-outlined text-base text-gray-600 dark:text-gray-300 group-hover:text-[#005F4A] dark:group-hover:text-emerald-400">arrow_back</span>
                        </span>
                        Kembali ke Beranda
                    </a>

                    <!-- Breadcrumb Text -->
                    <div class="hidden sm:flex items-center gap-1.5 text-xs text-gray-400">
                        <span>Beranda</span>
                        <span>/</span>
                        <span>{{ typeConfig.label }}</span>
                        <span>/</span>
                        <span class="text-gray-700 dark:text-gray-300 font-bold truncate max-w-xs">{{ entityName }}</span>
                    </div>
                </div>
            </div>

            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 space-y-12">

                <!-- ── 2. FULL-WIDTH PHOTO SLIDER HERO (SPANS FULL 2 COLUMNS AT THE TOP) ── -->
                <div class="space-y-3">
                    <div v-if="slides.length > 0" class="relative overflow-hidden rounded-3xl bg-gray-900 select-none h-72 sm:h-[440px] lg:h-[480px] shadow-sm">
                        <!-- Slides -->
                        <div
                            class="flex h-full transition-transform duration-500 ease-in-out"
                            :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
                        >
                            <div
                                v-for="(slide, i) in slides"
                                :key="i"
                                class="flex-shrink-0 w-full h-full relative"
                            >
                                <img
                                    :src="slide.url"
                                    :alt="slide.caption || entityName"
                                    @error="handleImageError(i, $event)"
                                    class="w-full h-full object-cover object-center"
                                />

                                <div v-if="slide.caption && i > 0" class="absolute bottom-4 left-5 text-white text-xs sm:text-sm font-medium drop-shadow bg-black/50 backdrop-blur-xs px-3 py-1 rounded-md">
                                    {{ slide.caption }}
                                </div>
                            </div>
                        </div>

                        <!-- Slider Controls -->
                        <template v-if="slides.length > 1">
                            <button
                                @click="prev"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 dark:bg-gray-900/90 backdrop-blur flex items-center justify-center shadow-lg hover:bg-white transition text-gray-900 dark:text-white z-20"
                                aria-label="Previous Slide"
                            >
                                <span class="material-symbols-outlined text-2xl">chevron_left</span>
                            </button>
                            <button
                                @click="next"
                                class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/90 dark:bg-gray-900/90 backdrop-blur flex items-center justify-center shadow-lg hover:bg-white transition text-gray-900 dark:text-white z-20"
                                aria-label="Next Slide"
                            >
                                <span class="material-symbols-outlined text-2xl">chevron_right</span>
                            </button>

                            <!-- Counter Badge -->
                            <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-md z-20">
                                {{ currentSlide + 1 }} / {{ slides.length }}
                            </div>
                        </template>
                    </div>

                    <!-- No photo fallback -->
                    <div v-else class="rounded-3xl overflow-hidden bg-gradient-to-br from-emerald-900 to-teal-950 text-white flex items-center justify-center h-64 sm:h-80 shadow-sm">
                        <div class="text-center space-y-2 p-6">
                            <span class="material-symbols-outlined text-5xl text-amber-400">{{ typeConfig.materialIcon }}</span>
                            <h2 class="text-xl sm:text-2xl font-extrabold">{{ entityName }}</h2>
                            <p class="text-xs text-emerald-100">Foto dokumentasi belum tersedia</p>
                        </div>
                    </div>

                    <!-- Thumbnail strip -->
                    <div v-if="slides.length > 1" class="flex gap-2 pt-1 overflow-x-auto scrollbar-hide px-1">
                        <button
                            v-for="(slide, i) in slides"
                            :key="i"
                            @click="goTo(i)"
                            :class="[
                                'flex-shrink-0 w-24 h-16 rounded-xl overflow-hidden border-2 transition-all duration-200',
                                i === currentSlide ? 'border-[#005F4A] opacity-100 shadow-md scale-105' : 'border-transparent opacity-60 hover:opacity-90'
                            ]"
                        >
                            <img 
                                :src="slide.url" 
                                :alt="`Foto ${i + 1}`" 
                                @error="handleImageError(i, $event)"
                                class="w-full h-full object-cover" 
                            />
                        </button>
                    </div>
                </div>

                <!-- ── 3. MAIN 2-COLUMN SPLIT LAYOUT ── -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pt-2">

                    <!-- ── LEFT MAIN COLUMN (8 Cols) ── -->
                    <div class="lg:col-span-8 space-y-8">

                        <!-- ① HEADER TITLE & META (Single Category Badge, Big Title, Address/Price) -->
                        <div class="space-y-3 border-b border-gray-200 dark:border-gray-800 pb-6">
                            <div>
                                <span :class="`inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-md ${typeConfig.badgeClass}`">
                                    <span class="material-symbols-outlined text-base">{{ typeConfig.materialIcon }}</span>
                                    {{ displayCategoryName }}
                                </span>
                            </div>

                            <h1 class="text-3xl sm:text-4xl lg:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight">
                                {{ entityName }}
                            </h1>

                            <div class="flex flex-wrap gap-4 text-xs text-gray-600 dark:text-gray-400">
                                <p v-if="data.price_range" class="flex items-center gap-1.5 font-extrabold text-[#005F4A] dark:text-emerald-400">
                                    <span class="material-symbols-outlined text-base">payments</span>
                                    <span>{{ data.price_range }}</span>
                                </p>
                                <p v-if="entityAddress" class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-base text-[#005F4A] dark:text-emerald-400 shrink-0">location_on</span>
                                    <span>{{ entityAddress }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- ② ABOUT & OVERVIEW -->
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-800 pb-3">
                                <span class="material-symbols-outlined text-xl text-[#005F4A] dark:text-emerald-400">menu_book</span>
                                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                                    <span v-if="activeType === 'culture'">Sejarah &amp; Deskripsi Kebudayaan</span>
                                    <span v-else-if="activeType === 'ekraf'">Profil &amp; Uraian Usaha Kreatif</span>
                                    <span v-else-if="activeType === 'culinary'">Tentang &amp; Menu Spesialisasi Kuliner</span>
                                    <span v-else-if="activeType === 'accommodation'">Tentang Akomodasi</span>
                                    <span v-else>Deskripsi &amp; Informasi Wisata</span>
                                </h2>
                            </div>

                            <div v-if="entityDescription" class="prose prose-base dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed font-sans" v-html="entityDescription">
                            </div>
                            <div v-else class="text-sm text-gray-400 italic py-2">
                                Informasi lengkap untuk item ini sedang ditinjau dan diperbarui oleh pengelola dinas.
                            </div>
                        </div>

                        <!-- ③ HIGHLIGHTS / KEUNGGULAN (Only for Non-Culture pages) -->
                        <div v-if="activeType !== 'culture'" class="space-y-3 pt-2">
                            <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-800 pb-3">
                                <span class="material-symbols-outlined text-xl text-[#005F4A] dark:text-emerald-400">stars</span>
                                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                                    <span v-if="activeType === 'ekraf'">Keunggulan Produk &amp; Usaha</span>
                                    <span v-else-if="activeType === 'culinary'">Keunggulan &amp; Fasilitas Kuliner</span>
                                    <span v-else>Keunggulan &amp; Fasilitas Utama</span>
                                </h2>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                <div 
                                    v-for="(highlight, idx) in defaultHighlights" 
                                    :key="idx"
                                    class="flex items-center gap-3 p-3.5 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800 shadow-2xs"
                                >
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg shrink-0">check_circle</span>
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ highlight }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ④ ACCORDIONS / RINCIAN LAINNYA -->
                        <div v-if="activeType !== 'culture'" class="space-y-3 pt-2">
                            <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-800 pb-3">
                                <span class="material-symbols-outlined text-xl text-[#005F4A] dark:text-emerald-400">quiz</span>
                                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">
                                    <span v-if="activeType === 'ekraf'">Informasi Pemesanan &amp; Layanan</span>
                                    <span v-else-if="activeType === 'culinary'">Informasi Menu &amp; Layanan Tempat</span>
                                    <span v-else>Rincian Informasi Tambahan</span>
                                </h2>
                            </div>

                            <div class="space-y-3 pt-1">
                                <!-- Accordion 1 -->
                                <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden bg-white dark:bg-gray-900">
                                    <button 
                                        @click="toggleAccordion('facilities')"
                                        class="w-full flex items-center justify-between p-4 font-bold text-xs sm:text-sm text-gray-900 dark:text-white text-left transition hover:bg-emerald-50/50"
                                    >
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-base text-[#005F4A]">{{ activeType === 'culinary' ? 'restaurant_menu' : activeType === 'ekraf' ? 'shopping_bag' : 'widgets' }}</span>
                                            <span v-if="activeType === 'culinary'">Spesialisasi Menu &amp; Hidangan Khas</span>
                                            <span v-else-if="activeType === 'ekraf'">Layanan Pemesanan &amp; Custom Design</span>
                                            <span v-else>Layanan &amp; Kelengkapan Area</span>
                                        </span>
                                        <span class="material-symbols-outlined transition-transform duration-200" :class="{ 'rotate-180': openAccordions.facilities }">expand_more</span>
                                    </button>
                                    <div v-show="openAccordions.facilities" class="p-4 text-xs text-gray-600 dark:text-gray-400 space-y-2 border-t border-gray-100 dark:border-gray-800">
                                        <p v-if="activeType === 'culinary'">Menyajikan ragam olahan makanan dan minuman khas Karawang yang diolah menggunakan bahan berkualitas dengan resep otentik.</p>
                                        <p v-else-if="activeType === 'ekraf'">Melayani pembelian langsung di lokasi galeri/workshop, pemesanan khusus (custom order), souvenir kegiatan, hingga pemesanan jumlah besar (grosir).</p>
                                        <p v-else>Area ini dilengkapi sarana pendukung resmi dari pengelola. Harap perhatikan petunjuk lokasi untuk mengakses sarana utama seperti tempat ibadah dan toilet umum.</p>
                                    </div>
                                </div>

                                <!-- Accordion 2 -->
                                <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden bg-white dark:bg-gray-900">
                                    <button 
                                        @click="toggleAccordion('access')"
                                        class="w-full flex items-center justify-between p-4 font-bold text-xs sm:text-sm text-gray-900 dark:text-white text-left transition hover:bg-emerald-[#005F4A]/5"
                                    >
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-base text-[#005F4A]">{{ activeType === 'culinary' ? 'table_restaurant' : activeType === 'ekraf' ? 'local_shipping' : 'near_me' }}</span>
                                            <span v-if="activeType === 'culinary'">Layanan Reservasi &amp; Takeaway</span>
                                            <span v-else-if="activeType === 'ekraf'">Pengiriman &amp; Ekspedisi</span>
                                            <span v-else>Aksesibilitas &amp; Rute Perjalanan</span>
                                        </span>
                                        <span class="material-symbols-outlined transition-transform duration-200" :class="{ 'rotate-180': openAccordions.access }">expand_more</span>
                                    </button>
                                    <div v-show="openAccordions.access" class="p-4 text-xs text-gray-600 dark:text-gray-400 space-y-2 border-t border-gray-100 dark:border-gray-800">
                                        <p v-if="activeType === 'culinary'">Menerima makan di tempat (dine-in), dibungkus (takeaway), maupun pemesanan tempat untuk acara keluarga dan rombongan.</p>
                                        <p v-else-if="activeType === 'ekraf'">Pengiriman dapat dilakukan melalui kurir instan area Karawang maupun pengiriman luar kota/nasional menggunakan ekspedisi resmi.</p>
                                        <p v-else>Dapat diakses menggunakan kendaraan roda dua maupun roda empat. Gunakan peta lokasi pada sidebar kanan untuk melihat gambaran posisi geografis.</p>
                                    </div>
                                </div>

                                <!-- Accordion 3 -->
                                <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden bg-white dark:bg-gray-900">
                                    <button 
                                        @click="toggleAccordion('guidelines')"
                                        class="w-full flex items-center justify-between p-4 font-bold text-xs sm:text-sm text-gray-900 dark:text-white text-left transition hover:bg-emerald-[#005F4A]/5"
                                    >
                                        <span class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-base text-[#005F4A]">{{ activeType === 'culinary' ? 'chair' : activeType === 'ekraf' ? 'store' : 'policy' }}</span>
                                            <span v-if="activeType === 'culinary'">Fasilitas Tempat &amp; Kunjungan</span>
                                            <span v-else-if="activeType === 'ekraf'">Kunjungan ke Galeri / Workshop</span>
                                            <span v-else>Tata Tertib &amp; Etika Kunjungan</span>
                                        </span>
                                        <span class="material-symbols-outlined transition-transform duration-200" :class="{ 'rotate-180': openAccordions.guidelines }">expand_more</span>
                                    </button>
                                    <div v-show="openAccordions.guidelines" class="p-4 text-xs text-gray-600 dark:text-gray-400 space-y-2 border-t border-gray-100 dark:border-gray-800">
                                        <p v-if="activeType === 'culinary'">Dilengkapi tempat duduk yang luas, fasilitas area wudhu/musala, toilet, dan lahan parkir kendaraan untuk kenyamanan pengunjung.</p>
                                        <p v-else-if="activeType === 'ekraf'">Masyarakat dan wisatawan dapat mengunjungi langsung rumah produksi atau galeri display usaha pada jam buka operasional.</p>
                                        <p v-else>Dilarang merusak cagar budaya, membuang sampah sembarangan, atau melakukan aktivitas yang melanggar norma kesopanan adat Karawang.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ── RIGHT STICKY SIDEBAR COLUMN (4 Cols) ── -->
                    <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">

                        <!-- CARD 1: DIRECT CONTACT / RESERVATION BUTTON (For Ekraf / Culinary / Accommodation) -->
                        <div v-if="(activeType === 'culinary' || activeType === 'ekraf' || activeType === 'accommodation') && contactUrl" class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-emerald-200/80 dark:border-emerald-900/60 shadow-sm space-y-3">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-[#005F4A] dark:text-emerald-400 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-base">{{ activeType === 'culinary' ? 'restaurant' : activeType === 'ekraf' ? 'storefront' : 'hotel' }}</span>
                                <span v-if="activeType === 'culinary'">Hubungi / Reservasi Tempat</span>
                                <span v-else-if="activeType === 'ekraf'">Hubungi Pengrajin / Pemilik</span>
                                <span v-else>Hubungi Penginapan</span>
                            </h3>

                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                                <span v-if="activeType === 'culinary'">Ingin memesan tempat atau menanyakan ketersediaan menu? Hubungi langsung pihak pengelola di sini.</span>
                                <span v-else-if="activeType === 'ekraf'">Tertarik dengan produk ini? Hubungi langsung pemilik usaha untuk pesanan atau konsultasi design.</span>
                                <span v-else>Hubungi pihak penginapan untuk informasi pemesanan kamar dan reservasi.</span>
                            </p>

                            <a
                                :href="contactUrl"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-xs font-bold text-white bg-[#005F4A] hover:bg-[#004D3C] rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5"
                            >
                                <span class="material-symbols-outlined text-base">chat</span>
                                Hubungi via WhatsApp / Telepon
                            </a>
                        </div>

                        <!-- CARD 2: PETA LOKASI INTERAKTIF (If Locatable) -->
                        <div v-if="isLocatable" class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                            <h3 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 dark:text-white flex items-center gap-2 border-b border-gray-100 dark:border-gray-800 pb-3">
                                <span class="material-symbols-outlined text-[#005F4A] dark:text-emerald-400 text-lg">map</span>
                                Peta Lokasi
                            </h3>

                            <!-- Leaflet Map Container -->
                            <div v-if="hasMap" class="h-[350px] w-full rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800 shadow-inner">
                                <LeafletMap :destinations="mapDestinations" height="100%" />
                            </div>
                            <div v-else class="rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-dashed border-gray-200 dark:border-gray-700 py-8 text-center text-xs text-gray-400">
                                Titik koordinat lokasi belum dipetakan.
                            </div>

                            <!-- CTA Button Open Google Maps -->
                            <div v-if="googleMapsUrl" class="pt-1">
                                <a
                                    :href="googleMapsUrl"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-xs font-bold text-white bg-[#005F4A] hover:bg-[#004D3C] rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5"
                                >
                                    <span class="material-symbols-outlined text-base">directions</span>
                                    Lihat di Google Maps
                                </a>
                            </div>
                        </div>

                        <!-- CARD 3: QUICK SPECIFICATIONS & METADATA -->
                        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                            <h3 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg">info</span>
                                Informasi Rinci
                            </h3>

                            <div class="space-y-3.5 text-xs">
                                <!-- Category -->
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-base text-[#005F4A]">category</span>
                                        Kategori / Sektor
                                    </span>
                                    <span class="font-bold text-gray-900 dark:text-white">
                                        {{ displayCategoryName }}
                                    </span>
                                </div>

                                <!-- Ekraf Owner -->
                                <div v-if="activeType === 'ekraf' && data.owner_name" class="flex items-center justify-between text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-base text-[#005F4A]">person</span>
                                        Pemilik / Pengelola
                                    </span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ data.owner_name }}</span>
                                </div>

                                <!-- Price Range -->
                                <div v-if="data.price_range" class="flex items-center justify-between text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-base text-[#005F4A]">payments</span>
                                        Kisaran Harga
                                    </span>
                                    <span class="font-extrabold text-[#005F4A] dark:text-emerald-400 text-sm">{{ data.price_range }}</span>
                                </div>

                                <!-- Contact / Phone -->
                                <div v-if="data.contact || data.phone" class="flex items-center justify-between text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-base text-[#005F4A]">call</span>
                                        Kontak Telepon
                                    </span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ data.contact || data.phone }}</span>
                                </div>

                                <!-- Address -->
                                <div v-if="entityAddress" class="flex items-start gap-2 pt-1 border-t border-gray-100 dark:border-gray-800">
                                    <span class="material-symbols-outlined text-base text-[#005F4A] shrink-0 mt-0.5">place</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300 leading-relaxed block">{{ entityAddress }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 4: DISPARBUD SUPPORT CALLOUT CARD -->
                        <div class="bg-gradient-to-br from-emerald-900 via-[#005F4A] to-teal-900 text-white p-6 rounded-2xl shadow-lg space-y-4 relative overflow-hidden">
                            <div class="relative z-10 space-y-3">
                                <h3 class="text-base font-extrabold">Butuh Bantuan Informasi?</h3>
                                <p class="text-xs text-emerald-100/90 leading-relaxed">
                                    Hubungi Layanan Pengaduan Resmi Disparbud Karawang untuk bantuan informasi pariwisata atau instansi.
                                </p>
                                
                                <div class="pt-2 space-y-2 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base text-amber-300">call</span>
                                        <span class="font-bold">(0267) 429800</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-base text-amber-300">mail</span>
                                        <span class="font-bold">disparbud@karawangkab.go.id</span>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <a
                                        :href="route('layanan-masyarakat.complaints.create')"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-[#005F4A] bg-white hover:bg-emerald-50 rounded-xl transition shadow"
                                    >
                                        <span class="material-symbols-outlined text-base">campaign</span>
                                        Form Pengaduan Publik &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>

                    </aside>

                </div>

                <!-- ── 4. CONTINUOUS READING / RELATED ITEMS SECTION (BEFORE FOOTER) ── -->
                <section v-if="relatedItems && relatedItems.length > 0" class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <div class="space-y-1">
                        <span class="text-xs font-bold uppercase tracking-wider text-[#005F4A] dark:text-emerald-400">Rekomendasi Terkait</span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white">
                            {{ typeConfig.sectionTitle }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="relItem in relatedItems"
                            :key="relItem.id"
                            class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 flex flex-col justify-between group"
                        >
                            <div>
                                <a :href="getDetailRoute(relItem.slug)" rel="external" class="block relative overflow-hidden h-48 bg-slate-200 dark:bg-gray-800">
                                    <img 
                                        v-if="relItem.cover_image" 
                                        :src="relItem.cover_image" 
                                        :alt="relItem.name || relItem.title" 
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    />
                                    <div v-else class="w-full h-full flex flex-col items-center justify-center text-gray-400 text-xs">
                                        <span class="material-symbols-outlined text-3xl mb-1">{{ typeConfig.materialIcon }}</span>
                                        <span>{{ relItem.name || relItem.title }}</span>
                                    </div>
                                    
                                    <!-- Category badge overlay -->
                                    <span class="absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded-md bg-white/95 dark:bg-gray-900/95 text-[#005F4A] dark:text-emerald-300 border border-emerald-200/60 shadow-xs">
                                        {{ relItem.category?.name || getTypeLabel(relItem.type) || typeConfig.label }}
                                    </span>
                                </a>

                                <div class="p-5 space-y-2">
                                    <h3 class="text-sm font-extrabold text-gray-900 dark:text-white line-clamp-2 group-hover:text-[#005F4A] transition leading-snug">
                                        <a :href="getDetailRoute(relItem.slug)" rel="external">{{ relItem.name || relItem.title }}</a>
                                    </h3>
                                    
                                    <p v-if="relItem.address" class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm text-[#005F4A]">location_on</span>
                                        {{ relItem.address }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <a 
                                    :href="getDetailRoute(relItem.slug)" 
                                    rel="external"
                                    class="text-xs font-bold text-[#005F4A] dark:text-emerald-400 hover:underline inline-flex items-center gap-1"
                                >
                                    Lihat Detail {{ typeConfig.label }} &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </PublicLayout>
</template>
