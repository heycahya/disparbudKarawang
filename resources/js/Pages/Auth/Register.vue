<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Buat Akun - Vibe Karawang" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-slate-50 dark:bg-slate-950 font-sans select-none">
        
        <!-- Left Side: Vibrant Branding & Info (Hidden on mobile) -->
        <div class="hidden lg:flex lg:col-span-5 xl:col-span-4 bg-gradient-to-br from-[#004b23] via-[#002e15] to-emerald-950 relative overflow-hidden flex-col justify-between p-12 text-white">
            
            <!-- Graphic background patterns -->
            <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>
            <div class="absolute -bottom-16 -left-16 w-80 h-80 bg-amber-500 rounded-full filter blur-3xl opacity-20"></div>
            <div class="absolute -top-16 -right-16 w-80 h-80 bg-emerald-400 rounded-full filter blur-3xl opacity-25"></div>
            
            <!-- Top Logo/Branding -->
            <div class="relative z-10">
                <Link href="/" class="flex items-center group">
                    <ApplicationLogo class="h-12 w-auto text-white group-hover:scale-105 transition-transform duration-300" />
                </Link>
            </div>

            <!-- Central Content -->
            <div class="relative z-10 my-auto space-y-6">
                <h1 class="text-3xl xl:text-4xl font-extrabold leading-tight tracking-tight">
                    Gabung dengan Vibe Karawang! ✨
                </h1>
                <p class="text-emerald-100/90 text-sm leading-relaxed max-w-sm">
                    Mulai perjalanan Anda sekarang. Dapatkan akses penuh untuk mengajukan usulan destinasi pariwisata baru, memantau laporan keluhan secara langsung, dan mendapatkan informasi event secara realtime.
                </p>
                
                <!-- Info Badges -->
                <div class="pt-4 space-y-3">
                    <div class="flex items-center gap-3 text-xs bg-white/5 border border-white/10 rounded-lg p-3 backdrop-blur-sm max-w-xs">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>Ajukan Usul Lokasi Wisata Favorit Anda</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs bg-white/5 border border-white/10 rounded-lg p-3 backdrop-blur-sm max-w-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Dukung Kemajuan Pariwisata Daerah</span>
                    </div>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="relative z-10 text-xs text-emerald-200/70 border-t border-white/10 pt-6">
                <p>&copy; 2026 Disparbud Kabupaten Karawang.</p>
                <p class="mt-1">All rights reserved.</p>
            </div>
        </div>

        <!-- Right Side: Clean Form Section -->
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col justify-between p-6 sm:p-12 md:p-20 relative bg-white dark:bg-slate-900">
            
            <!-- Top Bar: Navigation link back -->
            <div class="flex justify-between items-center mb-8 lg:mb-0">
                <Link 
                    href="/" 
                    class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#004b23] dark:text-slate-400 dark:hover:text-emerald-400 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </Link>
                
                <!-- Mobile Branding -->
                <div class="lg:hidden flex items-center">
                    <Link href="/">
                        <ApplicationLogo class="h-8 w-auto text-[#004b23] dark:text-emerald-400" />
                    </Link>
                </div>
            </div>

            <!-- Central Form Container -->
            <div class="w-full max-w-md mx-auto my-auto py-8">
                
                <!-- Form Header -->
                <div class="mb-8">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        Buat Akun Baru
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">
                        Sudah memiliki akun? 
                        <Link :href="route('login')" class="text-[#004b23] dark:text-emerald-400 font-semibold hover:underline">
                            Masuk di sini
                        </Link>
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Lengkap
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input 
                                id="name"
                                type="text"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                                class="pl-10 block w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition"
                                placeholder="Nama Lengkap Anda"
                            />
                        </div>
                        <span v-if="form.errors.name" class="text-xs text-rose-600 dark:text-rose-400 mt-1.5 block font-medium">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </span>
                            <input 
                                id="email"
                                type="email"
                                v-model="form.email"
                                required
                                autocomplete="username"
                                class="pl-10 block w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition"
                                placeholder="nama@email.com"
                            />
                        </div>
                        <span v-if="form.errors.email" class="text-xs text-rose-600 dark:text-rose-400 mt-1.5 block font-medium">
                            {{ form.errors.email }}
                        </span>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input 
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                                class="pl-10 block w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition"
                                placeholder="••••••••"
                            />
                        </div>
                        <span v-if="form.errors.password" class="text-xs text-rose-600 dark:text-rose-400 mt-1.5 block font-medium">
                            {{ form.errors.password }}
                        </span>
                    </div>

                    <!-- Password Confirmation Input -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </span>
                            <input 
                                id="password_confirmation"
                                type="password"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                class="pl-10 block w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition"
                                placeholder="••••••••"
                            />
                        </div>
                        <span v-if="form.errors.password_confirmation" class="text-xs text-rose-600 dark:text-rose-400 mt-1.5 block font-medium">
                            {{ form.errors.password_confirmation }}
                        </span>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="w-full bg-[#004b23] hover:bg-[#003d1d] text-white py-3.5 px-4 rounded-lg font-bold text-sm shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 ease-in-out disabled:opacity-50 flex items-center justify-center gap-2"
                        >
                            <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            <span>{{ form.processing ? 'Mendaftar...' : 'Daftar Sekarang' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bottom Copyright info on mobile -->
            <div class="text-center lg:hidden text-xs text-slate-400 dark:text-slate-600 mt-8">
                <p>&copy; 2026 Disparbud Kabupaten Karawang.</p>
            </div>
            
            <!-- Safe Spacer for desktop flex align -->
            <div class="hidden lg:block"></div>
        </div>
    </div>
</template>
