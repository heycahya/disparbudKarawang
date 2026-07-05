<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    destination: {
        type: Object,
        required: true,
    },
    aspectRatio: {
        type: String,
        default: 'aspect-[4/5]',
    },
});
</script>

<template>
    <Link
        :href="route().has('public.destinasi.show') ? route('public.destinasi.show', destination.slug) : '/informasi/' + destination.slug"
        class="block group text-left cursor-pointer focus:outline-none"
    >
        <!-- Photo Container: Golok Lubuk Asymmetric Radius, No Outer Box/Shadow -->
        <div :class="[aspectRatio, 'w-full overflow-hidden rounded-tr-3xl rounded-bl-3xl bg-sanggabuana-soft']">
            <img
                :src="destination.cover_image ?? '/images/placeholder-wisata.jpg'"
                :alt="destination.name"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy"
            />
        </div>

        <!-- Meta Text: Natural Flowing Text directly beneath the photo -->
        <div class="mt-2.5 px-0">
            <h3 class="text-sm font-semibold text-sanggabuana leading-snug line-clamp-2 group-hover:text-karawang-emerald transition-colors">
                {{ destination.name }}
            </h3>
            <p class="mt-0.5 text-xs text-sanggabuana-muted">
                {{ (destination.views ?? 0).toLocaleString('id-ID') }} penayangan
            </p>
        </div>
    </Link>
</template>
