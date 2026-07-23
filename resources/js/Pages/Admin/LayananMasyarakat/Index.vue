<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    items: {
        type: Array,
        default: () => []
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, masuk: 0, ditinjau: 0, disetujui: 0, ditolak: 0 })
    },
    filters: {
        type: Object,
        default: () => ({ type: 'all', status: 'all', search: '' })
    }
});

const activeType = ref(props.filters.type || 'all');
const activeStatus = ref(props.filters.status || 'all');
const searchQuery = ref(props.filters.search || '');

// Keep filters state reactively in sync when props change from server or navigation
watch(() => props.filters, (newFilters) => {
    if (newFilters) {
        activeType.value = newFilters.type || 'all';
        activeStatus.value = newFilters.status || 'all';
        searchQuery.value = newFilters.search || '';
    }
}, { deep: true });

// Modals state
const selectedItem = ref(null);
const showDetailModal = ref(false);
const showStatusModal = ref(false);

const statusForm = useForm({
    status: '',
    admin_note: ''
});

function applyFilters() {
    router.get(route('admin.verifikasi-layanan.index'), {
        type: activeType.value,
        status: activeStatus.value,
        search: searchQuery.value
    }, {
        preserveState: true,
        replace: true
    });
}

function openDetail(item) {
    selectedItem.value = item;
    showDetailModal.value = true;
}

function openStatusModal(item, targetStatus) {
    selectedItem.value = item;
    statusForm.status = targetStatus;
    statusForm.admin_note = item.admin_note || '';
    showStatusModal.value = true;
}

function submitStatus() {
    if (!selectedItem.value) return;

    statusForm.patch(route('admin.verifikasi-layanan.update-status', {
        type: selectedItem.value.type,
        id: selectedItem.value.id
    }), {
        onSuccess: () => {
            showStatusModal.value = false;
            showDetailModal.value = false;
            selectedItem.value = null;
        }
    });
}

function cloneToPublic(item) {
    if (!confirm(`Kloning ${item.type_label} "${item.title}" menjadi konten resmi publik?`)) return;

    router.post(route('admin.verifikasi-layanan.clone', {
        type: item.type,
        id: item.id
    }));
}

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'masuk':
        case 'pending':
            return 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200 dark:border-amber-900/50';
        case 'ditinjau':
        case 'diproses':
            return 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 border border-blue-200 dark:border-blue-900/50';
        case 'disetujui':
        case 'approved':
            return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50';
        case 'ditolak':
        case 'rejected':
            return 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300 border border-rose-200 dark:border-rose-900/50';
        default:
            return 'bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200';
    }
};

const getTypeBadgeClass = (type) => {
    switch (type) {
        case 'complaint':
            return 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300';
        case 'tourism_submission':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300';
        case 'event_broadcast':
            return 'bg-teal-100 text-teal-800 dark:bg-teal-950 dark:text-teal-300';
        default:
            return 'bg-slate-100 text-slate-800';
    }
};
</script>

<template>
    <Head title="Verifikasi Layanan Masyarakat - Admin Disparbud" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                Verifikasi Layanan Masyarakat
            </h2>
        </template>

        <div class="py-8 bg-slate-50 min-h-[calc(100vh-8rem)] dark:bg-slate-950 font-sans">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- 1. Header Banner -->
                <div class="bg-gradient-to-r from-[#0F5E3D] via-emerald-800 to-teal-900 rounded-3xl shadow-xl text-white p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-amber-300 mb-2 border border-white/10 uppercase tracking-wider">
                            Modul Moderasi Admin
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Verifikasi Layanan & Usulan Publik</h1>
                        <p class="text-xs sm:text-sm text-emerald-100/90 mt-1 max-w-2xl leading-relaxed">
                            Kelola pengaduan masyarakat, rekomendasi destinasi wisata baru, dan permohonan siaran acara.
                        </p>
                    </div>
                </div>

                <!-- 2. Summary Statistics (Interactive Cards) -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                    <button
                        @click="activeStatus = 'all'; applyFilters()"
                        class="p-4 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-[#0F5E3D]"
                        :class="activeStatus === 'all' ? 'border-[#0F5E3D] shadow-md ring-2 ring-emerald-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Masuk</span>
                        <span class="block text-xl font-black text-slate-800 dark:text-white mt-1">{{ stats.total }}</span>
                    </button>

                    <button
                        @click="activeStatus = 'masuk'; applyFilters()"
                        class="p-4 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-amber-500"
                        :class="['masuk', 'pending'].includes(activeStatus) ? 'border-amber-500 shadow-md ring-2 ring-amber-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-amber-600 uppercase tracking-wider">Perlu Ditinjau</span>
                        <span class="block text-xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ stats.masuk }}</span>
                    </button>

                    <button
                        @click="activeStatus = 'ditinjau'; applyFilters()"
                        class="p-4 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-blue-500"
                        :class="['ditinjau', 'diproses'].includes(activeStatus) ? 'border-blue-500 shadow-md ring-2 ring-blue-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-blue-600 uppercase tracking-wider">Ditinjau</span>
                        <span class="block text-xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ stats.ditinjau }}</span>
                    </button>

                    <button
                        @click="activeStatus = 'disetujui'; applyFilters()"
                        class="p-4 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-emerald-500"
                        :class="['disetujui', 'approved'].includes(activeStatus) ? 'border-emerald-500 shadow-md ring-2 ring-emerald-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Disetujui</span>
                        <span class="block text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ stats.disetujui }}</span>
                    </button>

                    <button
                        @click="activeStatus = 'ditolak'; applyFilters()"
                        class="p-4 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-rose-500"
                        :class="['ditolak', 'rejected'].includes(activeStatus) ? 'border-rose-500 shadow-md ring-2 ring-rose-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-rose-600 uppercase tracking-wider">Ditolak</span>
                        <span class="block text-xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ stats.ditolak }}</span>
                    </button>
                </div>

                <!-- 3. Filter Bar & Search -->
                <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Type Pills -->
                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
                        <button
                            @click="activeType = 'all'; applyFilters()"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                            :class="activeType === 'all' ? 'bg-[#0F5E3D] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                        >
                            Semua Layanan
                        </button>
                        <button
                            @click="activeType = 'complaint'; applyFilters()"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                            :class="activeType === 'complaint' ? 'bg-[#0F5E3D] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                        >
                            Pengaduan
                        </button>
                        <button
                            @click="activeType = 'tourism_submission'; applyFilters()"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                            :class="activeType === 'tourism_submission' ? 'bg-amber-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                        >
                            Usulan Wisata
                        </button>
                        <button
                            @click="activeType = 'event_broadcast'; applyFilters()"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                            :class="activeType === 'event_broadcast' ? 'bg-teal-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                        >
                            Pengajuan Event
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full md:w-72 relative">
                        <input
                            v-model="searchQuery"
                            @keyup.enter="applyFilters"
                            type="text"
                            placeholder="Cari judul, pengirim..."
                            class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500"
                        />
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- 4. Data Table -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-950 text-slate-400 font-bold uppercase border-b border-slate-200/80 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-4">Pengirim</th>
                                    <th class="px-6 py-4">Jenis Layanan</th>
                                    <th class="px-6 py-4">Judul / Permohonan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Tanggal Masuk</th>
                                    <th class="px-6 py-4 text-right">Aksi Moderasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr v-for="item in items" :key="item.type + '-' + item.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                    
                                    <!-- Pengirim -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ item.user.name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ item.user.email }}</div>
                                    </td>

                                    <!-- Jenis Layanan -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider" :class="getTypeBadgeClass(item.type)">
                                            {{ item.type_label }}
                                        </span>
                                    </td>

                                    <!-- Judul / Detail -->
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 dark:text-slate-200 max-w-xs truncate">{{ item.title }}</div>
                                        <div v-if="item.location" class="text-[10px] text-slate-400 truncate">📍 {{ item.location }}</div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider" :class="getStatusBadgeClass(item.status)">
                                            {{ item.status }}
                                        </span>
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                        {{ item.created_at || '-' }}
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-1.5">
                                        <button
                                            @click="openDetail(item)"
                                            class="px-2.5 py-1.5 text-[10px] font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg transition"
                                        >
                                            Detail
                                        </button>

                                        <button
                                            @click="openStatusModal(item, 'disetujui')"
                                            class="px-2.5 py-1.5 text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition"
                                        >
                                            Setujui
                                        </button>

                                        <button
                                            @click="openStatusModal(item, 'ditolak')"
                                            class="px-2.5 py-1.5 text-[10px] font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition"
                                        >
                                            Tolak
                                        </button>

                                        <button
                                            v-if="['tourism_submission', 'event_broadcast'].includes(item.type)"
                                            @click="cloneToPublic(item)"
                                            class="px-2.5 py-1.5 text-[10px] font-bold text-amber-900 bg-amber-400 hover:bg-amber-500 rounded-lg transition"
                                            title="Kloning menjadi konten publik (Draft)"
                                        >
                                            Kloning Publik
                                        </button>
                                    </td>
                                </tr>

                                <tr v-if="items.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        Tidak ada data pengajuan layanan masyarakat yang sesuai filter.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Detail Modal -->
        <div v-if="showDetailModal && selectedItem" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full p-6 space-y-6 shadow-2xl border border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between border-b pb-4 border-slate-100 dark:border-slate-800">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider mb-1" :class="getTypeBadgeClass(selectedItem.type)">
                            {{ selectedItem.type_label }}
                        </span>
                        <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">{{ selectedItem.title }}</h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-950 p-4 rounded-xl">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Pengirim</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ selectedItem.user.name }} ({{ selectedItem.user.email }})</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Tanggal Masuk</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ selectedItem.created_at }}</span>
                        </div>
                        <div v-if="selectedItem.location">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Lokasi / Alamat</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ selectedItem.location }}</span>
                        </div>
                        <div v-if="selectedItem.organization">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Instansi / Organisasi</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ selectedItem.organization }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Deskripsi Pengajuan</span>
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 rounded-xl text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                            {{ selectedItem.description }}
                        </div>
                    </div>

                    <div v-if="selectedItem.attachment">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Lampiran / Foto</span>
                        <a :href="selectedItem.attachment" target="_blank" class="inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-400 font-bold underline">
                            🔍 Lihat Lampiran File / Preview Photo
                        </a>
                    </div>

                    <div v-if="selectedItem.admin_note">
                        <span class="block text-[10px] font-bold text-rose-500 uppercase mb-1">Catatan Admin</span>
                        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 rounded-xl">
                            {{ selectedItem.admin_note }}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button @click="showDetailModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-400">Tutup</button>
                    <button @click="openStatusModal(selectedItem, 'disetujui')" class="px-4 py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl hover:bg-emerald-700">Setujui</button>
                    <button @click="openStatusModal(selectedItem, 'ditolak')" class="px-4 py-2 bg-rose-600 text-white font-bold text-xs rounded-xl hover:bg-rose-700">Tolak</button>
                </div>
            </div>
        </div>

        <!-- Status Change Modal -->
        <div v-if="showStatusModal && selectedItem" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200 dark:border-slate-800">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                    Ubah Status Pengajuan: <span class="capitalize">{{ statusForm.status }}</span>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Pengajuan: <strong>{{ selectedItem.title }}</strong> oleh {{ selectedItem.user.name }}.
                </p>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan Evaluasi Admin (Optional / Alasan Penolakan)</label>
                    <textarea
                        v-model="statusForm.admin_note"
                        rows="4"
                        class="w-full text-xs p-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Tuliskan alasan penolakan atau instruksi persetujuan bagi pengirim..."
                    ></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button @click="showStatusModal = false" class="px-4 py-2 text-xs font-bold text-slate-500">Batal</button>
                    <button
                        @click="submitStatus"
                        :disabled="statusForm.processing"
                        class="px-5 py-2 text-xs font-bold text-white rounded-xl transition"
                        :class="statusForm.status === 'disetujui' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
