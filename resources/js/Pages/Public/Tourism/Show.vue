<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    destination: Object,
    seo: Object
});
</script>

<template>
    <Head>
        <title>{{ seo.title }} | Disparbud Karawang</title>
        <meta name="description" :content="seo.description" />
        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:image" v-if="seo.image" :content="seo.image" />
        <meta property="og:type" :content="seo.type || 'website'" />
    </Head>

    <PublicLayout>

        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
            <article class="bg-white dark:bg-gray-900 rounded-asymmetric shadow-xl overflow-hidden p-8 sm:p-12 border border-slate-200/80 dark:border-gray-800">
                <header class="mb-8">
                    <span v-if="destination.category" class="text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950 px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ destination.category.name }}
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold mt-4 mb-3 text-gray-900 dark:text-white leading-tight">
                        {{ destination.name }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1 border-b border-gray-100 dark:border-gray-800 pb-4">
                        📍 Alamat: {{ destination.address }}
                    </p>
                </header>

                <div v-if="destination.cover_image" class="mb-8 overflow-hidden rounded-asymmetric-sm shadow-md">
                    <img :src="destination.cover_image" :alt="destination.name" class="w-full max-h-[480px] object-cover">
                </div>

                <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 leading-relaxed text-base sm:text-lg">
                    {{ destination.description }}
                </div>
            </article>
        </main>

    </PublicLayout>
</template>
