<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    article: {
        type: Object,
        required: true,
    },
    aspectRatio: {
        type: String,
        default: 'aspect-[16/10]',
    },
});

const formatDate = (dateStr) => {
    if (!dateStr) return 'Terbaru';
    return new Date(dateStr).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Link
        :href="route().has('public.news.show') ? route('public.news.show', article.slug) : '/berita/' + article.slug"
        class="block group text-left cursor-pointer focus:outline-none"
    >
        <!-- Photo Container: Golok Lubuk Asymmetric Radius, No Outer Box/Shadow -->
        <div :class="[aspectRatio, 'w-full overflow-hidden rounded-tr-3xl rounded-bl-3xl bg-sanggabuana-soft']">
            <img
                :src="article.thumbnail ?? '/images/placeholder-berita.jpg'"
                :alt="article.title"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            />
        </div>

        <!-- Meta Text: Natural Flowing Text directly beneath the photo -->
        <div class="mt-2.5 px-0">
            <h3 class="text-sm font-semibold text-sanggabuana leading-snug line-clamp-2 group-hover:text-karawang-emerald transition-colors">
                {{ article.title }}
            </h3>
            <p class="mt-0.5 text-xs text-sanggabuana-muted">
                {{ formatDate(article.published_at) }} · {{ (article.views ?? 0).toLocaleString('id-ID') }} tayangan
            </p>
        </div>
    </Link>
</template>
