<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    isLoggedIn: {
        type: Boolean,
        default: false,
    },
});

const isOpen = ref(false);

const closeMenu = () => {
    isOpen.value = false;
};

const handleLogout = () => {
    closeMenu();
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <!-- Hamburger Icon Trigger Button -->
        <button
            id="hamburger-menu-toggle"
            @click="isOpen = true"
            class="p-2 rounded-full hover:bg-sanggabuana-hairline/30 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-karawang-emerald/50"
            aria-label="Buka Menu Navigasi"
            :aria-expanded="isOpen"
        >
            <svg
                class="w-6 h-6 text-sanggabuana"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                />
            </svg>
        </button>

        <!-- Slide-over / Drawer Backdrop & Overlay -->
        <Teleport to="body">
            <div
                v-if="isOpen"
                class="fixed inset-0 z-50 overflow-hidden"
                role="dialog"
                aria-modal="true"
            >
                <!-- Backdrop semi-transparan -->
                <div
                    class="fixed inset-0 bg-black/40 backdrop-blur-xs transition-opacity duration-300"
                    @click="closeMenu"
                ></div>

                <!-- Drawer Content -->
                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div
                        class="w-full sm:w-72 bg-white shadow-xl flex flex-col justify-between p-6 transform transition-transform duration-300 ease-in-out"
                    >
                        <!-- Top Header & Nav Items -->
                        <div>
                            <!-- Header Drawer -->
                            <div class="flex items-center justify-between pb-4">
                                <Link
                                    href="/"
                                    @click="closeMenu"
                                    class="text-base font-bold text-sanggabuana tracking-tight"
                                >
                                    Disparbud <span class="text-karawang-emerald">Karawang</span>
                                </Link>
                                <button
                                    @click="closeMenu"
                                    class="p-2 rounded-full hover:bg-sanggabuana-hairline/40 text-sanggabuana-muted hover:text-sanggabuana transition-colors"
                                    aria-label="Tutup Menu"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <hr class="border-sanggabuana-hairline my-3" />

                            <!-- Navigasi Utama -->
                            <nav class="flex flex-col space-y-3 py-2">
                                <Link
                                    href="/"
                                    @click="closeMenu"
                                    class="text-sm font-medium text-sanggabuana hover:text-karawang-emerald transition-colors py-1.5"
                                >
                                    Beranda
                                </Link>
                                <Link
                                    href="/profil"
                                    @click="closeMenu"
                                    class="text-sm font-medium text-sanggabuana hover:text-karawang-emerald transition-colors py-1.5"
                                >
                                    Profil Lembaga
                                </Link>
                                <Link
                                    href="/informasi"
                                    @click="closeMenu"
                                    class="text-sm font-medium text-sanggabuana hover:text-karawang-emerald transition-colors py-1.5"
                                >
                                    Destinasi Wisata
                                </Link>
                                <Link
                                    href="/berita"
                                    @click="closeMenu"
                                    class="text-sm font-medium text-sanggabuana hover:text-karawang-emerald transition-colors py-1.5"
                                >
                                    Berita & Artikel
                                </Link>
                                <Link
                                    href="/galeri"
                                    @click="closeMenu"
                                    class="text-sm font-medium text-sanggabuana hover:text-karawang-emerald transition-colors py-1.5"
                                >
                                    Galeri Dokumentasi
                                </Link>
                                <Link
                                    href="/service-rakyat"
                                    @click="closeMenu"
                                    class="text-sm font-medium text-sanggabuana hover:text-karawang-emerald transition-colors py-1.5"
                                >
                                    Service Rakyat
                                </Link>
                            </nav>
                        </div>

                        <!-- Bottom Section: Auth Actions -->
                        <div>
                            <hr class="border-sanggabuana-hairline my-4" />

                            <div v-if="isLoggedIn" class="space-y-2">
                                <Link
                                    :href="route('dashboard')"
                                    @click="closeMenu"
                                    class="block w-full text-center px-4 py-2.5 bg-karawang-emerald text-white text-sm font-semibold rounded-asymmetric-sm hover:bg-karawang-emerald-active transition-colors"
                                >
                                    Dashboard Saya
                                </Link>
                                <button
                                    @click="handleLogout"
                                    class="block w-full text-center px-4 py-2 text-sm font-medium text-sanggabuana-muted hover:text-red-600 transition-colors"
                                >
                                    Keluar (Logout)
                                </button>
                            </div>

                            <div v-else-if="canLogin" class="space-y-2.5">
                                <Link
                                    :href="route('login')"
                                    @click="closeMenu"
                                    class="block w-full text-center px-4 py-2.5 border border-sanggabuana-hairline text-sanggabuana text-sm font-semibold rounded-asymmetric-sm hover:border-karawang-emerald hover:text-karawang-emerald transition-colors"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    @click="closeMenu"
                                    class="block w-full text-center px-4 py-2.5 bg-karawang-emerald text-white text-sm font-semibold rounded-asymmetric-sm hover:bg-karawang-emerald-active transition-colors"
                                >
                                    Daftar Akun
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>
