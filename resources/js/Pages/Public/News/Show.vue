<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    news: { type: Object, required: true },
    relatedNews: { type: Array, default: () => [] },
    seo: { type: Object, default: () => ({}) }
});

const isCopied = ref(false);
const imageError = ref(false);

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Estimated reading time in minutes
const readingTime = computed(() => {
    if (!props.news?.content) return 1;
    const textOnly = props.news.content.replace(/<[^>]*>/g, '');
    const wordCount = textOnly.trim().split(/\s+/).length;
    return Math.max(1, Math.ceil(wordCount / 200));
});

const currentUrl = computed(() => typeof window !== 'undefined' ? window.location.href : '');

const copyShareLink = () => {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(window.location.href);
        isCopied.value = true;
        setTimeout(() => {
            isCopied.value = false;
        }, 2500);
    }
};

const shareWhatsApp = () => {
    const text = encodeURIComponent(`${props.news.title}\n${currentUrl.value}`);
    window.open(`https://api.whatsapp.com/send?text=${text}`, '_blank');
};

const shareFacebook = () => {
    const url = encodeURIComponent(currentUrl.value);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
};
</script>

<template>
    <Head>
        <title>{{ seo.title || news.title }} | Disparbud Karawang</title>
        <meta name="description" :content="seo.description || ''" />
        <meta property="og:title" :content="seo.title || news.title" />
        <meta property="og:description" :content="seo.description || ''" />
        <meta property="og:image" v-if="seo.image" :content="seo.image" />
        <meta property="og:type" :content="seo.type || 'article'" />
    </Head>

    <PublicLayout>
        <div class="bg-gray-50/60 dark:bg-gray-950 min-h-screen pb-20">
            
            <!-- ── SINGLE CLEAN STICKY BACK BAR ── -->
            <div class="bg-white dark:bg-gray-900 border-b border-gray-200/80 dark:border-gray-800 sticky top-16 z-30 shadow-2xs">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between">
                    <a
                        :href="route('public.home')"
                        rel="external"
                        class="inline-flex items-center gap-2 text-xs font-bold text-gray-700 dark:text-gray-300 hover:text-[#004b23] dark:hover:text-emerald-400 transition-colors group"
                    >
                        <span class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-emerald-50 dark:group-hover:bg-emerald-950/60 transition-colors">
                            <span class="material-symbols-outlined text-base text-gray-600 dark:text-gray-300 group-hover:text-[#004b23] dark:group-hover:text-emerald-400">arrow_back</span>
                        </span>
                        Kembali ke Beranda
                    </a>

                    <!-- Breadcrumbs -->
                    <div class="hidden sm:flex items-center gap-1.5 text-xs text-gray-400">
                        <span>Beranda</span>
                        <span>/</span>
                        <span>Berita</span>
                        <span>/</span>
                        <span class="text-gray-700 dark:text-gray-300 font-bold truncate max-w-xs">{{ news.title }}</span>
                    </div>
                </div>
            </div>

            <!-- ── MAIN 2-COLUMN MAGAZINE LAYOUT ── -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-12">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- LEFT COLUMN: Main Article (8 Cols) -->
                    <article class="lg:col-span-8 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden p-6 sm:p-10 space-y-8">
                        
                        <!-- Header ABOVE Image -->
                        <header class="space-y-4 border-b border-gray-100 dark:border-gray-800 pb-6">
                            <div class="flex flex-wrap items-center gap-2">
                                <span v-if="news.category" class="inline-block text-[11px] font-bold text-[#004b23] dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 dark:border-emerald-800/60 px-3 py-1 rounded-md">
                                    {{ news.category.name }}
                                </span>
                                <span class="text-xs font-semibold text-gray-400">Kabar Resmi Disparbud</span>
                                <span class="text-gray-300">·</span>
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm text-amber-500">schedule</span>
                                    Estimasi {{ readingTime }} menit baca
                                </span>
                            </div>

                            <!-- Big News Title -->
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-900 dark:text-white leading-tight tracking-tight">
                                {{ news.title }}
                            </h1>

                            <!-- Author & Meta Info Bar -->
                            <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400 pt-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-full bg-emerald-50 dark:bg-emerald-950 border border-emerald-200/60 dark:border-emerald-800/60 flex items-center justify-center text-[#004b23] dark:text-emerald-400 font-bold shrink-0">
                                        <span class="material-symbols-outlined text-lg">newspaper</span>
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 dark:text-gray-100 block text-xs">{{ news.user?.name || 'Tim Humas Disparbud' }}</span>
                                        <span class="text-[10px] text-gray-400 block">Redaksi Resmi Disparbud</span>
                                    </div>
                                </div>

                                <span class="text-gray-200 dark:text-gray-800">|</span>

                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-base text-gray-400">calendar_today</span>
                                    <span>{{ formatDate(news.created_at) }}</span>
                                </div>

                                <span class="text-gray-200 dark:text-gray-800">|</span>

                                <div class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-base text-gray-400">visibility</span>
                                    <span>{{ news.views || 0 }} kali dibaca</span>
                                </div>
                            </div>
                        </header>

                        <!-- Featured Cover Image with Error Fallback -->
                        <div v-if="news.thumbnail" class="overflow-hidden rounded-2xl bg-gray-100 dark:bg-gray-800 border border-gray-100 dark:border-gray-800 shadow-sm">
                            <img 
                                v-if="!imageError"
                                :src="news.thumbnail" 
                                :alt="news.title" 
                                @error="imageError = true"
                                class="w-full max-h-[480px] object-cover object-center"
                            />
                            <div v-else class="w-full h-64 flex flex-col items-center justify-center bg-gray-200 dark:bg-gray-800 text-gray-400 space-y-2">
                                <span class="material-symbols-outlined text-5xl">image_not_supported</span>
                                <p class="text-xs font-semibold">Sampul artikel tidak dapat dimuat</p>
                            </div>
                        </div>

                        <!-- Article Body (Blogspot Reader Typography) -->
                        <div 
                            class="prose prose-lg dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 leading-relaxed font-sans border-b border-gray-100 dark:border-gray-800 pb-8" 
                            v-html="news.content"
                        >
                        </div>

                        <!-- Article Tag Cloud & Share Actions -->
                        <div class="space-y-4 pt-2">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-[#005F4A] dark:text-emerald-400">label</span>
                                    <span>Kategori: <strong class="text-gray-900 dark:text-white font-bold">{{ news.category?.name || 'Berita Umum' }}</strong></span>
                                </div>

                                <!-- Multi Social Share Buttons -->
                                <div class="flex items-center gap-2">
                                    <button 
                                        @click="shareWhatsApp"
                                        title="Bagikan ke WhatsApp"
                                        class="px-3 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition flex items-center gap-1.5"
                                    >
                                        <span class="material-symbols-outlined text-base">chat</span>
                                        WhatsApp
                                    </button>
                                    <button 
                                        @click="shareFacebook"
                                        title="Bagikan ke Facebook"
                                        class="px-3 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition flex items-center gap-1.5"
                                    >
                                        <span class="material-symbols-outlined text-base">share</span>
                                        Facebook
                                    </button>
                                    <button 
                                        @click="copyShareLink"
                                        title="Salin Tautan"
                                        class="px-3.5 py-2 text-xs font-bold text-white bg-[#004b23] hover:bg-[#003d1d] rounded-lg transition flex items-center gap-1.5"
                                    >
                                        <span class="material-symbols-outlined text-base">link</span>
                                        {{ isCopied ? 'Tersalin!' : 'Salin Link' }}
                                    </button>
                                </div>
                            </div>
                        </div>

                    </article>

                    <!-- RIGHT COLUMN: Blog Sidebar (4 Cols) -->
                    <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
                        
                        <!-- Publisher Information Card -->
                        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                            <h3 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg">verified</span>
                                Redaksi Disparbud Karawang
                            </h3>

                            <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                                Portal berita &amp; pengumuman resmi Dinas Pariwisata dan Kebudayaan Kabupaten Karawang untuk masyarakat publik.
                            </p>

                            <div class="pt-2 border-t border-gray-100 dark:border-gray-800 space-y-2 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-[#004b23]">verified_user</span>
                                    <span>Informasi Resmi Terverifikasi</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-[#004b23]">update</span>
                                    <span>Diperbarui Secara Berkala</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Popular / Related Articles Widget -->
                        <div v-if="relatedNews && relatedNews.length > 0" class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm space-y-4">
                            <h3 class="text-sm font-extrabold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-amber-500 text-lg">trending_up</span>
                                Berita Terkait Lainnya
                            </h3>

                            <div class="space-y-4">
                                <div 
                                    v-for="item in relatedNews.slice(0, 3)" 
                                    :key="item.id"
                                    class="flex gap-3 items-start group"
                                >
                                    <a :href="route('public.news.show', item.slug)" rel="external" class="w-16 h-14 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 shrink-0 border border-gray-200 dark:border-gray-700">
                                        <img v-if="item.thumbnail" :src="item.thumbnail" :alt="item.title" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-[10px]">Foto</div>
                                    </a>
                                    <div class="space-y-1 text-xs">
                                        <span v-if="item.category" class="text-[10px] font-bold text-[#004b23] dark:text-emerald-400 block">
                                            {{ item.category.name }}
                                        </span>
                                        <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-[#004b23] transition line-clamp-2 leading-snug">
                                            <a :href="route('public.news.show', item.slug)" rel="external">{{ item.title }}</a>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Share / Callout Box -->
                        <div class="bg-gradient-to-br from-emerald-900 via-[#004b23] to-emerald-950 text-white p-6 rounded-xl shadow-lg space-y-3">
                            <h4 class="text-sm font-extrabold">Bagikan Informasi Ini</h4>
                            <p class="text-xs text-emerald-100/90 leading-relaxed">
                                Bantu publikasikan kegiatan pariwisata &amp; kebudayaan Karawang ke jejaring sosial Anda.
                            </p>
                            <button 
                                @click="copyShareLink"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-[#004b23] bg-white hover:bg-emerald-50 rounded-lg transition shadow"
                            >
                                <span class="material-symbols-outlined text-base">link</span>
                                {{ isCopied ? 'Link Berhasil Disalin!' : 'Salin Tautan Artikel' }}
                            </button>
                        </div>

                    </aside>

                </div>

                <!-- ── Related News Section (Continuous Reading Grid) ── -->
                <section v-if="relatedNews && relatedNews.length > 0" class="space-y-6 pt-4">
                    <div class="border-b border-gray-200 dark:border-gray-800 pb-3">
                        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white">Artikel &amp; Pengumuman Terbaru</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="item in relatedNews"
                            :key="item.id" 
                            class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300 flex flex-col justify-between group"
                        >
                            <div>
                                <a :href="route('public.news.show', item.slug)" rel="external" class="block relative overflow-hidden h-44 bg-slate-200 dark:bg-gray-800">
                                    <img 
                                        v-if="item.thumbnail" 
                                        :src="item.thumbnail" 
                                        :alt="item.title" 
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-xs">
                                        Sampul Berita
                                    </div>
                                </a>

                                <div class="p-5 space-y-2">
                                    <span v-if="item.category" class="inline-block text-[10px] font-bold text-[#005F4A] dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200/60 dark:border-emerald-800/60 px-2 py-0.5 rounded-md">
                                        {{ item.category.name }}
                                    </span>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2 group-hover:text-[#005F4A] transition leading-snug">
                                        <a :href="route('public.news.show', item.slug)" rel="external">{{ item.title }}</a>
                                    </h3>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <a 
                                    :href="route('public.news.show', item.slug)" 
                                    rel="external"
                                    class="text-xs font-bold text-[#005F4A] dark:text-emerald-400 hover:underline inline-flex items-center gap-1"
                                >
                                    Baca Artikel Lengkap &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </PublicLayout>
</template>
