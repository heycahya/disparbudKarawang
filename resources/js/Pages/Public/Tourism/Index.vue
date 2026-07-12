<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicNavbar from '@/Components/PublicNavbar.vue';
import FooterGlobal from '@/Components/FooterGlobal.vue';

defineProps({
    data: Object,
    activeTab: String,
    destinations: {
        type: Array,
        default: () => []
    }
});

const getCultureCategoryLabel = (cat) => {
    switch (cat) {
        case 'kesenian': return 'Kesenian';
        case 'tradisi': return 'Tradisi';
        case 'warisan_budaya': return 'Warisan Budaya';
        default: return cat;
    }
}

const getAccommodationTypeLabel = (type) => {
    switch (type) {
        case 'hotel': return 'Hotel';
        case 'villa': return 'Villa';
        case 'homestay': return 'Homestay';
        case 'penginapan': return 'Penginapan';
        default: return type;
    }
}

const getCulinaryTypeLabel = (type) => {
    switch (type) {
        case 'restoran': return 'Restoran';
        case 'cafe': return 'Cafe';
        case 'warung': return 'Warung';
        case 'rumah_makan': return 'Rumah Makan';
        default: return type;
    }
}
</script>

<template>
    <Head title="Katalog Direktori Wisata & Budaya" />

    <div class="min-h-screen bg-rice-husk dark:bg-gray-950 text-gray-900 dark:text-gray-100 flex flex-col font-sans">
        <!-- Navigation bar -->
        <PublicNavbar />

        <header class="bg-gradient-to-r from-[#0F5E3D] via-[#0C4E5B] to-emerald-950 py-16 text-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#D97706] bg-white/10 backdrop-blur-md px-3.5 py-1 rounded-full border border-white/20">
                    Katalog Publik
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold mt-3 tracking-tight">Direktori Pariwisata & Kebudayaan</h1>
                <p class="text-emerald-100/90 max-w-2xl mt-2 text-sm sm:text-base">
                    Temukan pesona destinasi alam, warisan kebudayaan luhur, kuliner lezat, serta ekosistem pelaku ekonomi kreatif Kabupaten Karawang.
                </p>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 w-full">

            <!-- Tabs Section -->
            <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-800 pb-4 mb-8">
                <Link 
                    :href="route('public.tourism.index', { tab: 'tourism' })"
                    class="px-5 py-2.5 text-sm font-semibold transition duration-150 ease-in-out border border-transparent"
                    :class="activeTab === 'tourism' 
                        ? 'bg-[#0F5E3D] text-white rounded-tr-xl rounded-bl-xl shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-tr-xl rounded-bl-xl'"
                >
                    Destinasi Wisata
                </Link>
                <Link 
                    :href="route('public.tourism.index', { tab: 'culture' })"
                    class="px-5 py-2.5 text-sm font-semibold transition duration-150 ease-in-out border border-transparent"
                    :class="activeTab === 'culture' 
                        ? 'bg-[#0F5E3D] text-white rounded-tr-xl rounded-bl-xl shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-tr-xl rounded-bl-xl'"
                >
                    Kebudayaan & Seni
                </Link>
                <Link 
                    :href="route('public.tourism.index', { tab: 'ekraf' })"
                    class="px-5 py-2.5 text-sm font-semibold transition duration-150 ease-in-out border border-transparent"
                    :class="activeTab === 'ekraf' 
                        ? 'bg-[#0F5E3D] text-white rounded-tr-xl rounded-bl-xl shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-tr-xl rounded-bl-xl'"
                >
                    Ekonomi Kreatif
                </Link>
                <Link 
                    :href="route('public.tourism.index', { tab: 'accommodation' })"
                    class="px-5 py-2.5 text-sm font-semibold transition duration-150 ease-in-out border border-transparent"
                    :class="activeTab === 'accommodation' 
                        ? 'bg-[#0F5E3D] text-white rounded-tr-xl rounded-bl-xl shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-tr-xl rounded-bl-xl'"
                >
                    Akomodasi & Hotel
                </Link>
                <Link 
                    :href="route('public.tourism.index', { tab: 'culinary' })"
                    class="px-5 py-2.5 text-sm font-semibold transition duration-150 ease-in-out border border-transparent"
                    :class="activeTab === 'culinary' 
                        ? 'bg-[#0F5E3D] text-white rounded-tr-xl rounded-bl-xl shadow-md' 
                        : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-tr-xl rounded-bl-xl'"
                >
                    Kuliner Lokal
                </Link>
            </div>

            <!-- Content Directory Grid -->
            <div v-if="data?.data?.length" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div 
                    v-for="item in data.data" 
                    :key="item.id" 
                    class="bg-white dark:bg-gray-900 rounded-asymmetric shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                >
                    <!-- Image -->
                    <img v-if="item.cover_image" :src="item.cover_image" alt="cover" class="w-full h-48 object-cover">
                    <div v-else class="w-full h-48 bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                        Tidak Ada Gambar
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <!-- Badges/Metadata -->
                        <div class="mb-3">
                            <span v-if="activeTab === 'tourism' && item.category" class="text-xs font-semibold text-emerald-700 bg-emerald-50 dark:bg-emerald-950 px-2.5 py-0.5 rounded-full">
                                {{ item.category.name }}
                            </span>
                            <span v-if="activeTab === 'culture'" class="text-xs font-semibold text-sky-700 bg-sky-50 dark:bg-sky-950 px-2.5 py-0.5 rounded-full">
                                {{ getCultureCategoryLabel(item.category) }}
                            </span>
                            <span v-if="activeTab === 'ekraf'" class="text-xs font-semibold text-purple-700 bg-purple-50 dark:bg-purple-950 px-2.5 py-0.5 rounded-full">
                                Ekonomi Kreatif
                            </span>
                            <span v-if="activeTab === 'accommodation'" class="text-xs font-semibold text-indigo-700 bg-indigo-50 dark:bg-indigo-950 px-2.5 py-0.5 rounded-full">
                                {{ getAccommodationTypeLabel(item.type) }}
                            </span>
                            <span v-if="activeTab === 'culinary'" class="text-xs font-semibold text-amber-700 bg-amber-50 dark:bg-amber-950 px-2.5 py-0.5 rounded-full">
                                {{ getCulinaryTypeLabel(item.type) }}
                            </span>
                        </div>

                        <!-- Name -->
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2 line-clamp-2">
                            <!-- Link to detail only for tourism, other show descriptively -->
                            <Link v-if="activeTab === 'tourism'" :href="route('public.tourism.show', item.slug)" class="hover:text-[#0F5E3D] transition">
                                {{ item.name }}
                            </Link>
                            <span v-else>{{ item.name }}</span>
                        </h3>

                        <!-- Custom Info for Ekraf / Accomodation / Culinary -->
                        <div class="space-y-1.5 mb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                            <!-- Owner & Contact for Ekraf -->
                            <div v-if="activeTab === 'ekraf'" class="flex flex-col gap-0.5">
                                <p v-if="item.owner_name"><span class="font-semibold">Pemilik:</span> {{ item.owner_name }}</p>
                                <p v-if="item.contact"><span class="font-semibold">Kontak:</span> {{ item.contact }}</p>
                                <p v-if="item.address" class="line-clamp-1"><span class="font-semibold">Alamat:</span> {{ item.address }}</p>
                            </div>

                            <!-- Phone, Price, Address for Accommodation & Culinary -->
                            <div v-if="activeTab === 'accommodation' || activeTab === 'culinary'" class="flex flex-col gap-0.5">
                                <p v-if="item.price_range"><span class="font-semibold text-emerald-700 dark:text-emerald-500">Harga:</span> {{ item.price_range }}</p>
                                <p v-if="item.phone"><span class="font-semibold">Reservasi:</span> {{ item.phone }}</p>
                                <p v-if="item.address" class="line-clamp-1"><span class="font-semibold">Alamat:</span> {{ item.address }}</p>
                            </div>

                            <!-- Standard Address for Tourism -->
                            <p v-if="activeTab === 'tourism' && item.address" class="line-clamp-1 font-medium text-gray-500 dark:text-gray-400">
                                {{ item.address }}
                            </p>
                        </div>

                        <!-- Description (HTML Stripped or clamped) -->
                        <div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 prose prose-sm max-w-none" v-html="item.description"></div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white dark:bg-gray-900 rounded-tr-2xl rounded-bl-2xl p-12 text-center border border-gray-100 dark:border-gray-800 shadow-sm">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada data</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data untuk kategori ini belum tersedia atau masih dalam status draft.</p>
            </div>

            <!-- Pagination links -->
            <div v-if="data?.links?.length > 3" class="flex justify-center space-x-2 mt-8">
                <div v-for="link in data.links" :key="link.label">
                    <Link 
                        v-if="link.url" 
                        :href="link.url" 
                        class="px-4 py-2 border rounded-lg text-sm transition font-medium"
                        :class="link.active 
                            ? 'bg-[#0F5E3D] text-white border-[#0F5E3D]' 
                            : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-800 hover:bg-gray-100'"
                        v-html="link.label"
                    />
                    <span 
                        v-else 
                        class="px-4 py-2 border rounded-lg text-sm text-gray-400 dark:text-gray-600 cursor-not-allowed bg-gray-100 dark:bg-gray-900" 
                        v-html="link.label"
                    />
                </div>
            </div>
        </main>

        <FooterGlobal />
    </div>
</template>
