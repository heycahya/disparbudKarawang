<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import LeafletMap from '@/Components/LeafletMap.vue';

const props = defineProps({
    type: { type: String, default: 'tourism' },
    item: { type: Object, required: true },
    destination: { type: Object, default: null },
    photos: { type: Array, default: () => [] }, // [{url, caption}]
    seo: { type: Object, default: () => ({}) },
});

const data = computed(() => props.item ?? props.destination ?? {});
const activeType = computed(() => props.type ?? 'tourism');

const entityName = computed(() => data.value?.name ?? data.value?.title ?? '');
const entityDescription = computed(() => data.value?.description ?? '');
const entityAddress = computed(() => data.value?.address ?? '');
const coverImage = computed(() => data.value?.cover_image ?? null);
const isLocatable = computed(() => ['tourism', 'accommodation', 'culinary'].includes(activeType.value));

// Build photo slides: use props.photos if provided (tourism), else fallback to single cover
const slides = computed(() => {
    if (props.photos && props.photos.length > 0) return props.photos;
    if (coverImage.value) return [{ url: coverImage.value, caption: entityName.value }];
    return [];
});

// Slider state
const currentSlide = ref(0);
const goTo = (i) => { currentSlide.value = i; };
const prev = () => { currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length; };
const next = () => { currentSlide.value = (currentSlide.value + 1) % slides.value.length; };

const typeConfig = computed(() => {
    const map = {
        tourism: {
            label: 'Destinasi Wisata', icon: '🏔️',
            badgeClass: 'bg-emerald-100 text-emerald-800',
            backRoute: route('public.tourism.index'),
            backLabel: 'Direktori Wisata',
        },
        culture: {
            label: 'Seni & Budaya', icon: '🎭',
            badgeClass: 'bg-sky-100 text-sky-800',
            backRoute: route('public.tourism.index', { tab: 'culture' }),
            backLabel: 'Direktori Budaya',
        },
        ekraf: {
            label: 'Ekonomi Kreatif', icon: '🎨',
            badgeClass: 'bg-purple-100 text-purple-800',
            backRoute: route('public.tourism.index', { tab: 'ekraf' }),
            backLabel: 'Direktori Ekraf',
        },
        accommodation: {
            label: 'Akomodasi', icon: '🏨',
            badgeClass: 'bg-indigo-100 text-indigo-800',
            backRoute: route('public.tourism.index', { tab: 'accommodation' }),
            backLabel: 'Direktori Akomodasi',
        },
        culinary: {
            label: 'Kuliner Lokal', icon: '🍽️',
            badgeClass: 'bg-amber-100 text-amber-800',
            backRoute: route('public.tourism.index', { tab: 'culinary' }),
            backLabel: 'Direktori Kuliner',
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
            category: activeType.value === 'tourism'
                ? data.value.category?.name
                : getTypeLabel(data.value.type),
        }];
    }
    return [];
});

const hasMap = computed(() => isLocatable.value && mapDestinations.value.length > 0);
</script>

<template>
    <Head>
        <title>{{ seo.title ?? entityName }} | Disparbud Karawang</title>
        <meta name="description" :content="seo.description ?? ''" />
        <meta property="og:title" :content="seo.title ?? entityName" />
        <meta property="og:description" :content="seo.description ?? ''" />
        <meta v-if="seo.image" property="og:image" :content="seo.image" />
        <meta property="og:type" :content="seo.type ?? 'website'" />
    </Head>

    <PublicLayout>
        <div class="bg-white dark:bg-gray-950 min-h-screen">

            <!-- ── Back Button Bar ── -->
            <div class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3">
                    <Link
                        :href="typeConfig.backRoute"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition-colors group"
                    >
                        <span class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-emerald-50 dark:group-hover:bg-emerald-900/40 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                        {{ typeConfig.backLabel }}
                    </Link>
                </div>
            </div>

            <!-- ── Photo Slider ─────────────────────────── -->
            <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-5">
                <!-- Has photos: render slider -->
                <div v-if="slides.length > 0" class="relative overflow-hidden rounded-2xl bg-gray-100 dark:bg-gray-800 select-none" style="height: 480px;">

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
                                class="w-full h-full object-cover object-center"
                            />
                            <!-- Gradient overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>

                            <!-- Caption -->
                            <div v-if="slide.caption && i > 0" class="absolute bottom-4 left-5 text-white text-sm font-medium drop-shadow">
                                {{ slide.caption }}
                            </div>
                        </div>
                    </div>

                    <!-- Category badge overlay -->
                    <span :class="`absolute top-4 left-4 inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full ${typeConfig.badgeClass} shadow-sm`">
                        {{ typeConfig.icon }} {{ typeConfig.label }}
                    </span>

                    <!-- Prev / Next buttons (only if multiple slides) -->
                    <template v-if="slides.length > 1">
                        <button
                            @click="prev"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 dark:bg-gray-900/80 backdrop-blur flex items-center justify-center shadow hover:bg-white dark:hover:bg-gray-900 transition-colors"
                            aria-label="Previous"
                        >
                            <svg class="w-4 h-4 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button
                            @click="next"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 dark:bg-gray-900/80 backdrop-blur flex items-center justify-center shadow hover:bg-white dark:hover:bg-gray-900 transition-colors"
                            aria-label="Next"
                        >
                            <svg class="w-4 h-4 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Dot indicators -->
                        <div class="absolute bottom-4 right-4 flex items-center gap-1.5">
                            <button
                                v-for="(_, i) in slides"
                                :key="i"
                                @click="goTo(i)"
                                :class="[
                                    'rounded-full transition-all duration-200',
                                    i === currentSlide
                                        ? 'w-5 h-2 bg-white'
                                        : 'w-2 h-2 bg-white/50 hover:bg-white/80'
                                ]"
                                :aria-label="`Slide ${i + 1}`"
                            />
                        </div>

                        <!-- Counter -->
                        <div class="absolute top-4 right-4 bg-black/40 backdrop-blur-sm text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                            {{ currentSlide + 1 }} / {{ slides.length }}
                        </div>
                    </template>
                </div>

                <!-- No image fallback -->
                <div v-else class="rounded-2xl overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center" style="height: 280px;">
                    <div class="text-center text-gray-400 space-y-2">
                        <div class="text-5xl">{{ typeConfig.icon }}</div>
                        <p class="text-sm font-medium">Foto belum tersedia</p>
                    </div>
                </div>

                <!-- Thumbnail strip (if > 1 slide) -->
                <div v-if="slides.length > 1" class="flex gap-2 mt-3 overflow-x-auto pb-1 scrollbar-hide">
                    <button
                        v-for="(slide, i) in slides"
                        :key="i"
                        @click="goTo(i)"
                        :class="[
                            'flex-shrink-0 w-20 h-14 rounded-lg overflow-hidden border-2 transition-all duration-200',
                            i === currentSlide ? 'border-[#0F5E3D] opacity-100' : 'border-transparent opacity-60 hover:opacity-90'
                        ]"
                    >
                        <img :src="slide.url" :alt="`Foto ${i + 1}`" class="w-full h-full object-cover" />
                    </button>
                </div>
            </div>

            <!-- ── Single Column Body ─────────────────────── -->
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-7 space-y-7">

                <!-- ① Title & Meta ── -->
                <div class="space-y-3 pb-6 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex flex-wrap gap-2">
                        <span :class="`inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full ${typeConfig.badgeClass}`">
                            {{ typeConfig.label }}
                        </span>
                        <span v-if="activeType === 'tourism' && data.category"
                            class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            {{ data.category.name }}
                        </span>
                        <span v-if="activeType === 'culture' && data.category"
                            class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            {{ getCultureCategoryLabel(data.category) }}
                        </span>
                        <span v-if="(activeType === 'accommodation' || activeType === 'culinary') && data.type"
                            class="inline-flex text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            {{ getTypeLabel(data.type) }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white leading-snug">{{ entityName }}</h1>

                    <div v-if="entityAddress" class="flex items-start gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ entityAddress }}</span>
                    </div>

                    <!-- Ekraf metadata -->
                    <div v-if="activeType === 'ekraf'" class="flex flex-wrap gap-5 pt-1">
                        <div v-if="data.owner_name" class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ data.owner_name }}</span>
                        </div>
                        <div v-if="data.contact" class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ data.contact }}</span>
                        </div>
                    </div>

                    <!-- Accommodation / Culinary metadata -->
                    <div v-if="activeType === 'accommodation' || activeType === 'culinary'" class="flex flex-wrap gap-5 pt-1">
                        <div v-if="data.price_range" class="flex items-center gap-1.5 text-sm">
                            <span class="font-bold text-emerald-700 dark:text-emerald-400">{{ data.price_range }}</span>
                            <span class="text-gray-400">·</span>
                            <span class="text-gray-500 dark:text-gray-400">Kisaran Harga</span>
                        </div>
                        <div v-if="data.phone" class="flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ data.phone }}</span>
                        </div>
                    </div>
                </div>

                <!-- ② Description ── -->
                <div v-if="entityDescription" class="space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        <span v-if="activeType === 'culture'">Sejarah &amp; Deskripsi</span>
                        <span v-else-if="activeType === 'ekraf'">Profil Usaha</span>
                        <span v-else>Deskripsi</span>
                    </p>
                    <div
                        class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed text-[15px] sm:text-base"
                        v-html="entityDescription"
                    ></div>
                </div>

                <!-- ③ Peta Lokasi ── -->
                <template v-if="isLocatable">
                    <div class="border-t border-gray-100 dark:border-gray-800 pt-7 space-y-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Peta Lokasi</p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Temukan {{ entityName }} di Peta</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Titik koordinat lokasi ditampilkan pada peta interaktif di bawah ini.</p>
                        </div>
                        <LeafletMap v-if="hasMap" :destinations="mapDestinations" height="420px" />
                        <div v-else class="rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-dashed border-gray-200 dark:border-gray-700 py-8 text-center text-sm text-gray-400">
                            Data koordinat lokasi belum tersedia.
                        </div>
                    </div>
                </template>

                <!-- ④ Footer nav ── -->
                <div class="pt-5 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <Link
                        :href="typeConfig.backRoute"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition-colors group"
                    >
                        <span class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-emerald-50 dark:group-hover:bg-emerald-900/40 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </span>
                        Kembali ke {{ typeConfig.backLabel }}
                    </Link>
                    <Link :href="route('public.home')" class="text-xs text-gray-400 hover:text-[#0F5E3D] dark:hover:text-emerald-400 transition-colors font-medium">
                        Portal Utama →
                    </Link>
                </div>

            </div>
        </div>
    </PublicLayout>
</template>
