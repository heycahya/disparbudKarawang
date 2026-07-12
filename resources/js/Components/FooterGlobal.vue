<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-[#0C4E5B] text-white py-12 mt-auto border-t border-teal-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="text-lg font-bold mb-3 text-emerald-300">Dinas Pariwisata dan Kebudayaan</h4>
                    <p class="text-xs text-teal-100/80 leading-relaxed">
                        Jl. Alun-Alun Selatan No. 1, Karawang Kulon, Kec. Karawang Barat, Kabupaten Karawang, Jawa Barat 41311
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-3 text-emerald-300">Tautan Pintas</h4>
                    <ul class="space-y-1.5 text-xs text-teal-100/80">
                        <li><Link :href="route('public.profile')" class="hover:text-amber-300 transition-colors">Profil Lembaga</Link></li>
                        <li><Link :href="route('public.destinasi')" class="hover:text-amber-300 transition-colors">Direktori Pariwisata</Link></li>
                        <li><Link :href="route('public.gallery.index')" class="hover:text-amber-300 transition-colors">Galeri Foto</Link></li>
                        <li><Link :href="route('public.news.index')" class="hover:text-amber-300 transition-colors">Warta & Berita</Link></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-3 text-emerald-300">Kontak Resmi</h4>
                    <p class="text-xs text-teal-100/80">Telepon: (0267) 429800</p>
                    <p class="text-xs text-teal-100/80">Email: disparbud@karawangkab.go.id</p>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-teal-800/80 text-center text-xs text-teal-200/60">
                &copy; 2026 Pemkab Karawang - Dinas Pariwisata dan Kebudayaan.
            </div>
        </div>
    </footer>
</template>
