<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="bg-white border-t border-sanggabuana-hairline mt-auto">
        <div class="max-w-screen-xl mx-auto px-6 lg:px-20 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Kolom 1: Profil & Branding -->
                <div>
                    <Link href="/" class="text-base font-bold text-sanggabuana tracking-tight">
                        Disparbud <span class="text-karawang-emerald">Karawang</span>
                    </Link>
                    <p class="mt-3 text-xs leading-relaxed text-sanggabuana-muted max-w-sm">
                        Portal informasi resmi Dinas Pariwisata dan Kebudayaan Kabupaten Karawang.
                        Pusat pendokumentasian destinasi wisata, event, dan warisan budaya Karawang.
                    </p>
                </div>

                <!-- Kolom 2: Navigasi Cepat -->
                <div>
                    <h3 class="text-sm font-semibold text-sanggabuana mb-3">Navigasi Cepat</h3>
                    <ul class="space-y-2 text-xs text-sanggabuana-muted">
                        <li>
                            <Link href="/" class="hover:text-karawang-emerald transition-colors">
                                Beranda
                            </Link>
                        </li>
                        <li>
                            <Link href="/informasi" class="hover:text-karawang-emerald transition-colors">
                                Destinasi Wisata
                            </Link>
                        </li>
                        <li>
                            <Link href="/berita" class="hover:text-karawang-emerald transition-colors">
                                Berita & Artikel
                            </Link>
                        </li>
                        <li>
                            <Link href="/galeri" class="hover:text-karawang-emerald transition-colors">
                                Galeri Dokumentasi
                            </Link>
                        </li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak Disparbud -->
                <div>
                    <h3 class="text-sm font-semibold text-sanggabuana mb-3">Kontak Resmi</h3>
                    <address class="not-italic space-y-2 text-xs text-sanggabuana-muted">
                        <p>Jl. Alun-Alun Barat No. 1, Karawang Barat</p>
                        <p>Kabupaten Karawang, Jawa Barat 41311</p>
                        <p class="pt-1">Email: <a href="mailto:disparbud@karawangkab.go.id" class="hover:text-karawang-emerald underline">disparbud@karawangkab.go.id</a></p>
                        <p>Telp: (0267) 402511</p>
                    </address>
                </div>
            </div>

            <!-- Legal Band Sub-footer -->
            <div class="border-t border-sanggabuana-hairline pt-6 mt-8 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center text-xs text-sanggabuana-muted gap-2">
                <p>© 2026 Pemerintah Kabupaten Karawang — Dinas Pariwisata dan Kebudayaan.</p>
                <p class="text-[11px]">Airbnb & Pinterest Minimalist Layout Revamp</p>
            </div>
        </div>
    </footer>
</template>
