<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, onBeforeUnmount, ref } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    statistics: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Refs for Chart elements
const trendChartCanvas = ref(null);
const distributionChartCanvas = ref(null);

let trendChartInstance = null;
let distributionChartInstance = null;

onMounted(() => {
    // 1. Line Chart: Tren Pengajuan 6 Bulan Terakhir
    if (trendChartCanvas.value) {
        const labels = props.statistics.trends.map(t => t.label);
        const complaintsData = props.statistics.trends.map(t => t.complaints);
        const tourismData = props.statistics.trends.map(t => t.tourism_submissions);
        const eventData = props.statistics.trends.map(t => t.event_requests);

        trendChartInstance = new Chart(trendChartCanvas.value, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Aduan Masyarakat',
                        data: complaintsData,
                        borderColor: '#D97706', // Harvest Gold
                        backgroundColor: 'rgba(217, 119, 6, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Usulan Destinasi',
                        data: tourismData,
                        borderColor: '#0F5E3D', // Karawang Emerald
                        backgroundColor: 'rgba(15, 94, 61, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Permohonan Acara',
                        data: eventData,
                        borderColor: '#0C4E5B', // Taruma Deep Teal
                        backgroundColor: 'rgba(12, 78, 91, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#4B5563',
                            font: {
                                family: 'Outfit, Inter, sans-serif',
                                weight: '500',
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#4B5563',
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? 'rgba(75, 85, 99, 0.2)' : 'rgba(229, 231, 235, 0.8)',
                        },
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#4B5563',
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // 2. Doughnut Chart: Distribusi Status Aduan & Usulan Wisata
    if (distributionChartCanvas.value) {
        const stats = props.statistics;
        const totalMasuk = stats.complaints.masuk + stats.tourism_submissions.masuk + stats.event_requests.masuk;
        const totalDitinjau = stats.complaints.ditinjau + stats.tourism_submissions.ditinjau + stats.event_requests.ditinjau;
        const totalDisetujui = stats.complaints.disetujui + stats.tourism_submissions.disetujui + stats.event_requests.disetujui;
        const totalDitolak = stats.complaints.ditolak + stats.tourism_submissions.ditolak + stats.event_requests.ditolak;

        distributionChartInstance = new Chart(distributionChartCanvas.value, {
            type: 'doughnut',
            data: {
                labels: ['Masuk (Pending)', 'Ditinjau', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [totalMasuk, totalDitinjau, totalDisetujui, totalDitolak],
                    backgroundColor: [
                        '#6B7280', // Gray (Masuk)
                        '#D97706', // Harvest Gold (Ditinjau)
                        '#0F5E3D', // Karawang Emerald (Disetujui)
                        '#DC2626'  // Red (Ditolak)
                    ],
                    borderWidth: 2,
                    borderColor: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#4B5563',
                            font: {
                                family: 'Outfit, Inter, sans-serif',
                                weight: '500',
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
});

onBeforeUnmount(() => {
    if (trendChartInstance) trendChartInstance.destroy();
    if (distributionChartInstance) distributionChartInstance.destroy();
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Pusat Kendali & Analitik Admin
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Welcome Banner -->
                <div 
                    class="mb-8 overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 border-l-4 border-[#0F5E3D] transition duration-300"
                    style="border-radius: 16px 4px 16px 4px;"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold mb-1">
                                Selamat datang kembali, <span class="text-[#0F5E3D] dark:text-[#10B981] font-extrabold">{{ user.name }}</span>!
                            </h1>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Pantau dan kelola Layanan Masyarakat Disparbud Kabupaten Karawang secara real-time.
                            </p>
                        </div>
                        <div class="inline-flex self-start md:self-auto items-center px-4 py-1.5 rounded-full text-xs font-bold bg-[#0F5E3D]/10 text-[#0F5E3D] dark:bg-[#10B981]/10 dark:text-[#10B981]">
                            Otoritas: {{ user.role === 'super_admin' ? 'Super Administrator' : 'Administrator' }}
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                    <!-- Stat Card 1: Aduan Masuk (Harvest Gold) -->
                    <div 
                        class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-all duration-300 hover:shadow-md group"
                        style="border-radius: 20px 4px 20px 4px;"
                    >
                        <div class="absolute right-0 top-0 h-24 w-24 translate-x-6 -translate-y-6 rounded-full bg-[#D97706]/5 group-hover:scale-110 transition duration-300"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aduan Pending & Ditinjau</p>
                                <h3 class="mt-2 text-3xl font-extrabold text-[#D97706]">{{ statistics.complaints.masuk + statistics.complaints.ditinjau }}</h3>
                            </div>
                            <div class="p-3 bg-[#D97706]/10 rounded-lg text-[#D97706]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Selesai: <b>{{ statistics.complaints.disetujui }}</b></span>
                            <span>Total Aduan: <b>{{ statistics.complaints.total }}</b></span>
                        </div>
                    </div>

                    <!-- Stat Card 2: Destinasi Terpublikasi (Karawang Emerald) -->
                    <div 
                        class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-all duration-300 hover:shadow-md group"
                        style="border-radius: 4px 20px 4px 20px;"
                    >
                        <div class="absolute right-0 top-0 h-24 w-24 translate-x-6 -translate-y-6 rounded-full bg-[#0F5E3D]/5 group-hover:scale-110 transition duration-300"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Destinasi Aktif</p>
                                <h3 class="mt-2 text-3xl font-extrabold text-[#0F5E3D] dark:text-[#10B981]">{{ statistics.destinations.published }}</h3>
                            </div>
                            <div class="p-3 bg-[#0F5E3D]/10 rounded-lg text-[#0F5E3D] dark:text-[#10B981]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Draft: <b>{{ statistics.destinations.draft }}</b></span>
                            <span>Total Berita: <b>{{ statistics.news.total }}</b></span>
                        </div>
                    </div>

                    <!-- Stat Card 3: Usulan Wisata Disetujui (Karawang Emerald / Green) -->
                    <div 
                        class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-all duration-300 hover:shadow-md group"
                        style="border-radius: 20px 4px 20px 4px;"
                    >
                        <div class="absolute right-0 top-0 h-24 w-24 translate-x-6 -translate-y-6 rounded-full bg-[#0F5E3D]/5 group-hover:scale-110 transition duration-300"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Usulan Wisata Disetujui</p>
                                <h3 class="mt-2 text-3xl font-extrabold text-[#0F5E3D] dark:text-[#10B981]">{{ statistics.tourism_submissions.disetujui }}</h3>
                            </div>
                            <div class="p-3 bg-[#0F5E3D]/10 rounded-lg text-[#0F5E3D] dark:text-[#10B981]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Pending: <b>{{ statistics.tourism_submissions.masuk }}</b></span>
                            <span>Total Usulan: <b>{{ statistics.tourism_submissions.total }}</b></span>
                        </div>
                    </div>

                    <!-- Stat Card 4: Kunjungan Halaman Web (Taruma Deep Teal) -->
                    <div 
                        class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden transition-all duration-300 hover:shadow-md group"
                        style="border-radius: 4px 20px 4px 20px;"
                    >
                        <div class="absolute right-0 top-0 h-24 w-24 translate-x-6 -translate-y-6 rounded-full bg-[#0C4E5B]/5 group-hover:scale-110 transition duration-300"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Kunjungan Web</p>
                                <h3 class="mt-2 text-3xl font-extrabold text-[#0C4E5B] dark:text-[#38BDF8]">{{ statistics.web_visits.toLocaleString() }}</h3>
                            </div>
                            <div class="p-3 bg-[#0C4E5B]/10 rounded-lg text-[#0C4E5B] dark:text-[#38BDF8]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-700 flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span>Views Destinasi: <b>{{ statistics.destinations.views.toLocaleString() }}</b></span>
                            <span>Views Berita: <b>{{ statistics.news.views.toLocaleString() }}</b></span>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <!-- Left: Monthly Submissions Trend (Taruma Deep Teal styling) -->
                    <div 
                        class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition duration-300"
                        style="border-radius: 24px 6px 24px 6px;"
                    >
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <span class="h-4 w-1 bg-[#0C4E5B] rounded"></span>
                            Tren Pengajuan Layanan Masyarakat (6 Bulan Terakhir)
                        </h3>
                        <p class="text-xs text-gray-400 mb-6">Agregasi data bulanan dari Aduan, Usulan Destinasi, dan Permohonan Siaran Acara.</p>
                        <div class="relative h-80">
                            <canvas ref="trendChartCanvas"></canvas>
                        </div>
                    </div>

                    <!-- Right: Distribution of Status (Karawang Emerald styling) -->
                    <div 
                        class="bg-white dark:bg-gray-800 p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition duration-300"
                        style="border-radius: 6px 24px 6px 24px;"
                    >
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-2 flex items-center gap-2">
                            <span class="h-4 w-1 bg-[#0F5E3D] rounded"></span>
                            Distribusi Progres Status
                        </h3>
                        <p class="text-xs text-gray-400 mb-6">Persentase progress gabungan seluruh pengajuan Layanan Masyarakat.</p>
                        <div class="relative h-80 flex items-center justify-center">
                            <canvas ref="distributionChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');

h2, h3, h1, p, span, button {
    font-family: 'Outfit', 'Inter', sans-serif;
}
</style>
