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

const getStatusBadge = (status) => {
    switch (status) {
        case 'masuk':
        case 'pending':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800';
        case 'ditinjau':
        case 'diproses':
        case 'processed':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800';
        case 'disetujui':
        case 'approved':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800';
        case 'ditolak':
        case 'rejected':
            return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 border border-gray-200 dark:border-gray-800';
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
    <Head title="Dashboard Publik" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard Publik
            </h2>
        </template>

        <div class="py-12 bg-rice-husk min-h-[calc(100vh-8rem)] dark:bg-gray-900">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <div class="overflow-hidden bg-white shadow-xl rounded-asymmetric dark:bg-gray-800 border-l-8 border-[#0F5E3D]">
                    <div class="p-8 text-gray-900 dark:text-gray-100 space-y-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 dark:border-gray-700 pb-6">
                            <div>
                                <h1 class="text-3xl font-extrabold tracking-tight">Selamat datang, <span class="text-[#0F5E3D] dark:text-emerald-400">{{ user.name }}</span>!</h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Portal layanan mandiri masyarakat untuk pariwisata dan kebudayaan Karawang.</p>
                            </div>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-karawang-emerald/10 text-karawang-emerald border border-karawang-emerald/20 dark:bg-emerald-950 dark:text-emerald-300">
                                Status Akun: {{ user.role }}
                            </span>
                        </div>
                        
                        <!-- Quick Action CTAs -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <Link :href="route('service-rakyat.complaints.create')" class="p-6 border border-slate-200/80 rounded-asymmetric-sm bg-white dark:bg-gray-900 hover:border-karawang-emerald shadow-sm hover:shadow-md transition-all duration-300 group">
                                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-[#0F5E3D] dark:text-emerald-400 flex items-center justify-center font-bold text-lg mb-3 group-hover:scale-110 transition-transform">
                                    📢
                                </div>
                                <h4 class="font-bold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Laporan Pengaduan</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Laporkan keluhan atau masukan terkait destinasi wisata dan budaya Karawang.</p>
                            </Link>

                            <Link :href="route('service-rakyat.tourism-submissions.create')" class="p-6 border border-slate-200/80 rounded-asymmetric-sm bg-white dark:bg-gray-900 hover:border-karawang-emerald shadow-sm hover:shadow-md transition-all duration-300 group">
                                <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-950 text-[#D97706] dark:text-amber-400 flex items-center justify-center font-bold text-lg mb-3 group-hover:scale-110 transition-transform">
                                    🗺️
                                </div>
                                <h4 class="font-bold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Pengajuan Wisata Baru</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Ajukan objek wisata baru di Karawang untuk dipublikasikan di katalog.</p>
                            </Link>

                            <Link :href="route('service-rakyat.event-broadcasts.create')" class="p-6 border border-slate-200/80 rounded-asymmetric-sm bg-white dark:bg-gray-900 hover:border-karawang-emerald shadow-sm hover:shadow-md transition-all duration-300 group">
                                <div class="w-10 h-10 rounded-lg bg-teal-50 dark:bg-teal-950 text-[#0C4E5B] dark:text-teal-400 flex items-center justify-center font-bold text-lg mb-3 group-hover:scale-110 transition-transform">
                                    🎉
                                </div>
                                <h4 class="font-bold text-[#0F5E3D] dark:text-emerald-400 text-lg mb-1">Pengajuan Event Broadcasting</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">Ajukan peliputan atau publikasi event instansi/organisasi Anda.</p>
                            </Link>
                        </div>

                        <!-- Ringkasan Status Pengajuan Service Rakyat -->
                        <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        📋 <span>Pelacakan Status Pengajuan Service Rakyat</span>
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Ringkasan status terkini permohonan dan pengaduan Anda.</p>
                                </div>
                                <Link 
                                    :href="route('public.history.index')" 
                                    class="text-xs font-bold text-[#0F5E3D] dark:text-emerald-400 hover:underline inline-flex items-center gap-1"
                                >
                                    Lihat Seluruh Riwayat &rarr;
                                </Link>
                            </div>

                            <!-- Summary Table -->
                            <div v-if="service_requests?.length" class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                                <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                                    <thead class="bg-slate-50 dark:bg-gray-900 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <th class="px-6 py-3.5">Jenis Layanan</th>
                                            <th class="px-6 py-3.5">Judul Pengajuan</th>
                                            <th class="px-6 py-3.5">Tanggal</th>
                                            <th class="px-6 py-3.5">Status</th>
                                            <th class="px-6 py-3.5">Catatan Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                        <tr v-for="req in service_requests.slice(0, 5)" :key="req.type + '-' + req.id" class="hover:bg-slate-50/50 dark:hover:bg-gray-700/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-emerald-50 dark:bg-emerald-950 text-[#0F5E3D] dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                                    {{ req.type_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white max-w-xs truncate">
                                                {{ req.title }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                                {{ formatDate(req.created_at) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span :class="getStatusBadge(req.status)" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                                    {{ getStatusLabel(req.status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-xs text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                                {{ req.admin_note || '-' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty State Table -->
                            <div v-else class="bg-slate-50 dark:bg-gray-900 rounded-xl p-8 text-center border border-dashed border-gray-300 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengajuan layanan. Pilih salah satu jenis layanan di atas untuk mengajukan.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
