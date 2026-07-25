<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
// FooterGlobal: All navigation links point to section anchors on the Home page.
// External links (gov sites) open in a new tab.
</script>

<template>
    <footer class="bg-[#004D3C] text-white pt-14 pb-8 mt-auto border-t border-black/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-10 border-b border-white/10">

                <!-- Branding & About -->
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center">
                        <ApplicationLogo class="h-12 w-auto text-white" />
                    </div>
                    <p class="text-xs text-teal-100/80 leading-relaxed max-w-sm">
                        Portal Resmi Dinas Pariwisata dan Kebudayaan Kabupaten Karawang. Melayani informasi pariwisata, cagar budaya, ekonomi kreatif, dan layanan mandiri masyarakat.
                    </p>
                    <address class="text-xs text-teal-100/70 not-italic space-y-1">
                        <p>📍 Jl. Alun-Alun Selatan No. 1, Karawang Kulon, Kec. Karawang Barat, Kabupaten Karawang 41311</p>
                        <p>📞 (0267) 429800</p>
                        <p>✉️ disparbud@karawangkab.go.id</p>
                    </address>
                </div>

                <!-- Quick Navigation — all anchors to Home sections -->
                <div class="space-y-4">
                    <h4 class="text-sm font-bold text-emerald-300 uppercase tracking-widest">Navigasi Portal</h4>
                    <ul class="space-y-2 text-xs text-teal-100/80">
                        <li>
                            <a href="/#section-hero" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Beranda
                            </a>
                        </li>
                        <li>
                            <a href="/#section-profil" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Profil Lembaga
                            </a>
                        </li>
                        <li>
                            <a href="/#section-wisata" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Destinasi Wisata
                            </a>
                        </li>
                        <li>
                            <a href="/#section-budaya" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Seni &amp; Kebudayaan
                            </a>
                        </li>
                        <li>
                            <a href="/#section-ekraf" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Ekonomi Kreatif
                            </a>
                        </li>
                        <li>
                            <a href="/#section-akomodasi-kuliner" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Akomodasi &amp; Kuliner
                            </a>
                        </li>
                        <li>
                            <a href="/#section-berita" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Berita &amp; Pengumuman
                            </a>
                        </li>
                        <li>
                            <a href="/#section-galeri-peta" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Galeri &amp; Peta
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Layanan & Instansi -->
                <div class="space-y-4">
                    <h4 class="text-sm font-bold text-emerald-300 uppercase tracking-widest">Layanan Masyarakat</h4>
                    <ul class="space-y-2 text-xs text-teal-100/80">
                        <li>
                            <a href="/#section-layanan" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Form Pengaduan
                            </a>
                        </li>
                        <li>
                            <a href="/#section-layanan" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Usul Wisata Baru
                            </a>
                        </li>
                        <li>
                            <a href="/#section-layanan" class="hover:text-amber-300 transition-colors flex items-center gap-1.5">
                                <span class="text-[10px]">▸</span> Pengajuan Event
                            </a>
                        </li>
                    </ul>

                    <div class="pt-2 border-t border-teal-800/40">
                        <h4 class="text-xs font-bold text-emerald-300 uppercase tracking-widest mb-2">Instansi Terkait</h4>
                        <ul class="space-y-1.5 text-xs text-teal-100/70">
                            <li>
                                <a href="https://www.karawangkab.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-amber-300 transition-colors">
                                    ↗ Pemkab Karawang
                                </a>
                            </li>
                            <li>
                                <a href="https://jabarprov.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-amber-300 transition-colors">
                                    ↗ Disparbud Jawa Barat
                                </a>
                            </li>
                            <li>
                                <a href="https://kemenparekraf.go.id" target="_blank" rel="noopener noreferrer" class="hover:text-amber-300 transition-colors">
                                    ↗ Kemenparekraf RI
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Bottom Bar -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-teal-200/50">
                <p>&copy; 2026 Pemerintah Kabupaten Karawang — Dinas Pariwisata dan Kebudayaan.</p>
                <p class="text-[10px]">Dikembangkan oleh Kelompok 1 Mata Kuliah Web Programming Universitas Bina Sarana Informatika Cikampek</p>
            </div>
        </div>
    </footer>
</template>
