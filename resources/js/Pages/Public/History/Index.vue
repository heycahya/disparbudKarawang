<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    submissions: Array
});

const activeTab = ref('all');

const filteredSubmissions = computed(() => {
    if (activeTab.value === 'all') {
        return props.submissions;
    }
    return props.submissions.filter(sub => sub.type === activeTab.value);
});

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'disetujui':
            return 'bg-emerald-100 text-[#0F5E3D] dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800';
        case 'ditolak':
            return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800';
        case 'ditinjau':
            return 'bg-amber-100 text-[#D97706] dark:bg-amber-950 dark:text-amber-300 border-amber-200 dark:border-amber-800';
        case 'masuk':
        default:
            return 'bg-slate-100 text-slate-800 dark:bg-slate-700/50 dark:text-slate-300 border-slate-200 dark:border-slate-600';
    }
};

const getStatusLabel = (status) => {
    switch (status) {
        case 'disetujui':
            return 'Disetujui';
        case 'ditolak':
            return 'Ditolak';
        case 'ditinjau':
            return 'Ditinjau';
        case 'masuk':
        default:
            return 'Masuk';
    }
};

// Check active step position for timeline
const getStepStatus = (currentStatus, stepName) => {
    const steps = ['masuk', 'ditinjau', 'completed']; // completed is either disetujui or ditolak
    
    let currentStepIndex = 0;
    if (currentStatus === 'ditinjau') {
        currentStepIndex = 1;
    } else if (currentStatus === 'disetujui' || currentStatus === 'ditolak') {
        currentStepIndex = 2;
    }

    if (stepName === 'masuk') {
        return currentStepIndex >= 0 ? 'active' : 'pending';
    }
    if (stepName === 'ditinjau') {
        return currentStepIndex >= 1 ? 'active' : 'pending';
    }
    if (stepName === 'completed') {
        if (currentStepIndex === 2) {
            return currentStatus === 'disetujui' ? 'success' : 'failed';
        }
        return 'pending';
    }
    return 'pending';
};
</script>

<template>
    <Head title="Riwayat Pengajuan & Tracking" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Lacak Status Pengajuan
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Filter Tabs -->
                <div class="mb-6 flex border-b border-gray-200 dark:border-gray-700">
                    <button
                        @click="activeTab = 'all'"
                        :class="[
                            'px-4 py-2 text-sm font-semibold transition-all border-b-2 outline-none',
                            activeTab === 'all'
                                ? 'border-[#0F5E3D] text-[#0F5E3D] dark:text-emerald-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'
                        ]"
                    >
                        Semua
                    </button>
                    <button
                        @click="activeTab = 'complaint'"
                        :class="[
                            'px-4 py-2 text-sm font-semibold transition-all border-b-2 outline-none',
                            activeTab === 'complaint'
                                ? 'border-[#0F5E3D] text-[#0F5E3D] dark:text-emerald-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'
                        ]"
                    >
                        Pengaduan
                    </button>
                    <button
                        @click="activeTab = 'tourism_submission'"
                        :class="[
                            'px-4 py-2 text-sm font-semibold transition-all border-b-2 outline-none',
                            activeTab === 'tourism_submission'
                                ? 'border-[#0F5E3D] text-[#0F5E3D] dark:text-emerald-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'
                        ]"
                    >
                        Usulan Wisata
                    </button>
                    <button
                        @click="activeTab = 'event_broadcast'"
                        :class="[
                            'px-4 py-2 text-sm font-semibold transition-all border-b-2 outline-none',
                            activeTab === 'event_broadcast'
                                ? 'border-[#0F5E3D] text-[#0F5E3D] dark:text-emerald-400'
                                : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400'
                        ]"
                    >
                        Siaran Acara
                    </button>
                </div>

                <!-- Submissions List -->
                <div v-if="filteredSubmissions.length > 0" class="space-y-6">
                    <div
                        v-for="sub in filteredSubmissions"
                        :key="`${sub.type}-${sub.id}`"
                        class="overflow-hidden bg-white shadow-xl rounded-asymmetric dark:bg-gray-800 border-l-8 border-[#0F5E3D] p-6 hover:shadow-2xl transition-shadow"
                    >
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-150 dark:border-gray-700 pb-4 mb-4">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border" :class="getStatusBadgeClass(sub.status)">
                                    {{ getStatusLabel(sub.status) }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                                    {{ sub.title }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-450 mt-1">
                                    Jenis: <span class="font-semibold text-gray-750 dark:text-gray-300">{{ sub.type_label }}</span> &bull; Diajukan pada: {{ formatDate(sub.created_at) }}
                                </p>
                            </div>
                        </div>

                        <!-- Stepper / Timeline Visualisation -->
                        <div class="my-6">
                            <div class="flex items-center justify-between w-full max-w-2xl mx-auto relative px-4">
                                <!-- Connecting Lines -->
                                <div class="absolute left-6 right-6 top-1/2 -translate-y-1/2 h-1 bg-gray-200 dark:bg-gray-700 -z-0">
                                    <div 
                                        class="h-full bg-[#0F5E3D] transition-all duration-500"
                                        :class="{
                                            'w-1/2': sub.status === 'ditinjau',
                                            'w-full': sub.status === 'disetujui' || sub.status === 'ditolak',
                                            'w-0': sub.status === 'masuk'
                                        }"
                                    ></div>
                                </div>

                                <!-- Step 1: Masuk -->
                                <div class="z-10 flex flex-col items-center">
                                    <div 
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors border-2"
                                        :class="[
                                            getStepStatus(sub.status, 'masuk') === 'active'
                                                ? 'bg-[#0F5E3D] text-white border-[#0F5E3D]'
                                                : 'bg-white text-gray-405 dark:bg-gray-800 border-gray-300'
                                        ]"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold mt-2 text-gray-800 dark:text-gray-200">Masuk</span>
                                </div>

                                <!-- Step 2: Ditinjau -->
                                <div class="z-10 flex flex-col items-center">
                                    <div 
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors border-2"
                                        :class="[
                                            getStepStatus(sub.status, 'ditinjau') === 'active'
                                                ? 'bg-yellow-500 text-white border-yellow-500'
                                                : 'bg-white text-gray-405 dark:bg-gray-800 border-gray-300'
                                        ]"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold mt-2 text-gray-800 dark:text-gray-200">Ditinjau</span>
                                </div>

                                <!-- Step 3: Selesai (Disetujui/Ditolak) -->
                                <div class="z-10 flex flex-col items-center">
                                    <div 
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-colors border-2"
                                        :class="[
                                            getStepStatus(sub.status, 'completed') === 'success'
                                                ? 'bg-green-500 text-white border-green-500'
                                                : getStepStatus(sub.status, 'completed') === 'failed'
                                                ? 'bg-red-500 text-white border-red-500'
                                                : 'bg-white text-gray-405 dark:bg-gray-800 border-gray-300'
                                        ]"
                                    >
                                        <svg v-if="getStepStatus(sub.status, 'completed') === 'failed'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-semibold mt-2 text-gray-800 dark:text-gray-200">
                                        {{ sub.status === 'ditolak' ? 'Ditolak' : 'Selesai' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Description & Admin Notes -->
                        <div class="mt-4 pt-4 border-t border-gray-150 dark:border-gray-700 space-y-3">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Deskripsi / Detail:</p>
                                <p class="text-sm text-gray-750 dark:text-gray-300 mt-1 whitespace-pre-line">{{ sub.description }}</p>
                            </div>
                            
                            <div v-if="sub.admin_note" class="p-4 rounded-lg bg-orange-50 border border-orange-200 dark:bg-orange-950/20 dark:border-orange-900/50">
                                <p class="text-xs font-bold text-orange-850 dark:text-orange-400">Catatan Admin:</p>
                                <p class="text-sm text-orange-900 dark:text-orange-300 mt-1">{{ sub.admin_note }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-150 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Belum ada pengajuan</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum membuat pengajuan layanan apapun saat ini.</p>
                    <div class="mt-6 flex justify-center gap-4">
                        <Link :href="route('service-rakyat.complaints.create')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#0F5E3D] hover:bg-emerald-700">
                            Buat Pengaduan
                        </Link>
                        <Link :href="route('service-rakyat.tourism-submissions.create')" class="inline-flex items-center px-4 py-2 border border-[#0F5E3D] text-sm font-medium rounded-md text-[#0F5E3D] bg-white hover:bg-emerald-50 dark:bg-gray-900 dark:text-emerald-400 dark:border-emerald-600 dark:hover:bg-gray-850">
                            Usul Wisata Baru
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
