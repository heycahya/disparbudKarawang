<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps({
    service_requests: {
        type: Array,
        default: () => []
    }
});

// Calculate statistics based on props.service_requests
const stats = computed(() => {
    const requests = props.service_requests || [];
    return {
        complaints: requests.filter(r => r.type === 'complaint').length,
        submissions: requests.filter(r => r.type === 'tourism_submission').length,
        events: requests.filter(r => r.type === 'event_broadcast').length,
    };
});

const getStatusBadge = (status) => {
    switch (status) {
        case 'masuk':
        case 'pending':
            return 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50';
        case 'ditinjau':
        case 'diproses':
        case 'processed':
            return 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-300 border border-blue-200 dark:border-blue-900/50';
        case 'disetujui':
        case 'approved':
            return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50';
        case 'ditolak':
        case 'rejected':
            return 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50';
        default:
            return 'bg-slate-50 text-slate-700 dark:bg-slate-900/30 dark:text-slate-300 border border-slate-200 dark:border-slate-800';
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'masuk':
        case 'pending': return 'Masuk';
        case 'ditinjau':
        case 'diproses':
        case 'processed': return 'Ditinjau / Diproses';
        case 'disetujui':
        case 'approved': return 'Disetujui';
        case 'ditolak':
        case 'rejected': return 'Ditolak';
        default: return status || 'Terkirim';
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Dashboard Publik - Vibe Karawang" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                Dashboard Publik
            </h2>
        </template>

        <div class="py-12 bg-slate-50 min-h-[calc(100vh-8rem)] dark:bg-slate-950 font-sans">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">
                
                <!-- 1. Banner Welcome Card -->
                <div class="relative overflow-hidden bg-gradient-to-r from-[#0F5E3D] via-[#0C4E5B] to-emerald-950 rounded-3xl shadow-xl text-white p-6 sm:p-10">
                    <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] opacity-10"></div>
                    <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-amber-500 rounded-full filter blur-3xl opacity-20"></div>
                    <div class="absolute -left-16 -top-16 w-80 h-80 bg-emerald-400 rounded-full filter blur-3xl opacity-20"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl font-black border border-white/20 shadow-inner shrink-0">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                                    Selamat Datang Kembali, <span class="text-amber-300">{{ user.name }}</span>!
                                </h1>
                                <p class="text-emerald-100/90 text-sm mt-1 max-w-xl leading-relaxed">
                                    Portal mandiri masyarakat Kabupaten Karawang. Kelola pengaduan, ajukan destinasi wisata lokal, dan publikasikan event kebudayaan Anda di sini.
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wider bg-white/10 border border-white/20 shadow-sm text-amber-300">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block animate-pulse"></span>
                            Masyarakat Umum
                        </span>
                    </div>
                </div>

                <!-- 2. Statistics Overview -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Complaints Count -->
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Laporan Pengaduan</span>
                            <span class="block text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ stats.complaints }}</span>
                        </div>
                    </div>

                    <!-- Tourism Submissions Count -->
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center text-amber-700 dark:text-amber-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Wisata Diajukan</span>
                            <span class="block text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ stats.submissions }}</span>
                        </div>
                    </div>

                    <!-- Events Count -->
                    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-950/40 flex items-center justify-center text-teal-700 dark:text-teal-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 3V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Event Diajukan</span>
                            <span class="block text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ stats.events }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Quick Action CTA Widgets -->
                <div class="space-y-4">
                    <h3 class="text-lg font-extrabold text-slate-800 dark:text-white tracking-tight">Layanan Publik Mandiri</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- CTA 1 -->
                        <Link :href="route('layanan-masyarakat.complaints.create')" class="flex flex-col justify-between p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl hover:border-[#0F5E3D] hover:shadow-lg dark:hover:border-emerald-500/50 hover:-translate-y-1 transition-all duration-300 group">
                            <div>
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform text-[#0F5E3D] dark:text-emerald-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h4 class="font-extrabold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Laporkan Pengaduan</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Laporkan kendala fasilitas jalan, sampah liar, atau keluhan di destinasi wisata Karawang.</p>
                            </div>
                            <span class="mt-6 text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                Mulai Lapor <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </span>
                        </Link>

                        <!-- CTA 2 -->
                        <Link :href="route('layanan-masyarakat.tourism-submissions.create')" class="flex flex-col justify-between p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl hover:border-amber-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                            <div>
                                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform text-amber-600 dark:text-amber-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <h4 class="font-extrabold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Usulkan Wisata Baru</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Punya rekomendasi air terjun tersembunyi, cagar budaya, atau spot foto alam? Ajukan ke dinas.</p>
                            </div>
                            <span class="mt-6 text-xs font-bold text-amber-600 dark:text-amber-400 inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                Ajukan Usulan <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </span>
                        </Link>

                        <!-- CTA 3 -->
                        <Link :href="route('layanan-masyarakat.event-broadcasts.create')" class="flex flex-col justify-between p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl hover:border-teal-500 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                            <div>
                                <div class="w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-950/40 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform text-teal-600 dark:text-teal-400 shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h4 class="font-extrabold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Pengajuan Siaran Acara</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Promosikan festival kebudayaan, pagelaran seni lokal, atau pentas musik komunitas Anda.</p>
                            </div>
                            <span class="mt-6 text-xs font-bold text-teal-600 dark:text-teal-400 inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                Ajukan Event <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </span>
                        </Link>
                    </div>
                </div>

                <!-- 4. Pelacakan Status Pengajuan Section -->
                <div class="space-y-4 pt-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800 dark:text-white tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#0F5E3D] dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>Pelacakan Status Pengajuan</span>
                            </h3>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Menampilkan 5 pengajuan layanan masyarakat terakhir Anda.</p>
                        </div>
                        <Link 
                            :href="route('public.history.index')" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                        >
                            <span>Lihat Seluruh Riwayat</span> &rarr;
                        </Link>
                    </div>

                    <!-- Summary Table -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm overflow-hidden">
                        <div v-if="service_requests?.length" class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600 dark:text-slate-400">
                                <thead class="bg-slate-50/50 dark:bg-slate-800/30 text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-800">
                                    <tr>
                                        <th class="px-6 py-4">Jenis Layanan</th>
                                        <th class="px-6 py-4">Judul Pengajuan</th>
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4">Catatan Admin</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                    <tr v-for="req in service_requests.slice(0, 5)" :key="req.type + '-' + req.id" class="hover:bg-slate-50/30 dark:hover:bg-slate-800/20 transition-colors">
                                        <td class="px-6 py-4.5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-[#0F5E3D] dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/40">
                                                {{ req.type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4.5 font-bold text-slate-800 dark:text-white max-w-xs truncate" :title="req.title">
                                            {{ req.title }}
                                        </td>
                                        <td class="px-6 py-4.5 whitespace-nowrap text-xs text-slate-400 dark:text-slate-500">
                                            {{ formatDate(req.created_at) }}
                                        </td>
                                        <td class="px-6 py-4.5 whitespace-nowrap">
                                            <span :class="getStatusBadge(req.status)" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                                {{ getStatusLabel(req.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4.5 text-xs text-slate-500 dark:text-slate-400 max-w-xs truncate" :title="req.admin_note">
                                            {{ req.admin_note || '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State Table -->
                        <div v-else class="py-12 px-6 text-center text-slate-400 dark:text-slate-600">
                            <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-4m-8 0v4m4-4v4" />
                            </svg>
                            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Belum ada pengajuan</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-500 max-w-xs mx-auto leading-relaxed">Daftar pengaduan atau usulan Anda akan ditampilkan di sini setelah Anda mengirimkan permohonan pertama.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
