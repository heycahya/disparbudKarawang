<script setup>
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    stats: {
        type: Object,
        default: () => ({ total: 0, admin: 0, public: 0 })
    },
    filters: {
        type: Object,
        default: () => ({ role: 'all', search: '' })
    }
});

const page = usePage();
const activeRole = ref(props.filters.role || 'all');
const searchQuery = ref(props.filters.search || '');

// Reset Password Modal State
const selectedUser = ref(null);
const showResetModal = ref(false);

const resetForm = useForm({
    password: '',
    password_confirmation: ''
});

function applyFilters() {
    router.get(route('admin.users.index'), {
        role: activeRole.value,
        search: searchQuery.value
    }, {
        preserveState: true,
        replace: true
    });
}

function openResetModal(user) {
    selectedUser.value = user;
    resetForm.reset();
    resetForm.clearErrors();
    showResetModal.value = true;
}

function submitResetPassword() {
    if (!selectedUser.value) return;

    resetForm.post(route('admin.users.reset-password', selectedUser.value.id), {
        onSuccess: () => {
            showResetModal.value = false;
            selectedUser.value = null;
            resetForm.reset();
        }
    });
}

const getRoleBadgeClass = (role) => {
    switch (role) {
        case 'admin':
            return 'bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-900';
        case 'public':
            return 'bg-emerald-100 text-emerald-900 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900';
        default:
            return 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300';
    }
};
</script>

<template>
    <Head title="Manajemen Akun & Pengguna - Admin Disparbud" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                    Manajemen Akun Pengguna
                </h2>
                <Link
                    :href="route('admin.users.create')"
                    class="px-4 py-2 text-xs font-bold text-white bg-[#0F5E3D] hover:bg-emerald-800 rounded-xl transition shadow-md flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah User Baru
                </Link>
            </div>
        </template>

        <div class="py-8 bg-slate-50 min-h-[calc(100vh-8rem)] dark:bg-slate-950 font-sans">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- 1. Banner Header -->
                <div class="bg-gradient-to-r from-[#0F5E3D] via-emerald-800 to-teal-900 rounded-3xl shadow-xl text-white p-6 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold text-amber-300 mb-2 border border-white/10 uppercase tracking-wider">
                            Sistem Pengelolaan Akun
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kelola Akun Admin & Masyarakat</h1>
                        <p class="text-xs sm:text-sm text-emerald-100/90 mt-1 max-w-2xl leading-relaxed">
                            Atur peranan akun pengelola sistem, lakukan reset password instan, dan atur akun masyarakat terdaftar.
                        </p>
                    </div>
                </div>

                <!-- 2. Statistics Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <button
                        @click="activeRole = 'all'; applyFilters()"
                        class="p-5 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-[#0F5E3D]"
                        :class="activeRole === 'all' ? 'border-[#0F5E3D] shadow-md ring-2 ring-emerald-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Seluruh Akun</span>
                        <span class="block text-2xl font-black text-slate-800 dark:text-white mt-1">{{ stats.total }}</span>
                    </button>

                    <button
                        @click="activeRole = 'admin'; applyFilters()"
                        class="p-5 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-amber-500"
                        :class="activeRole === 'admin' ? 'border-amber-500 shadow-md ring-2 ring-amber-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-amber-600 uppercase tracking-wider">Akun Administrator</span>
                        <span class="block text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ stats.admin }}</span>
                    </button>

                    <button
                        @click="activeRole = 'public'; applyFilters()"
                        class="p-5 bg-white dark:bg-slate-900 border rounded-2xl text-left transition-all hover:border-emerald-500"
                        :class="activeRole === 'public' ? 'border-emerald-500 shadow-md ring-2 ring-emerald-500/20' : 'border-slate-200/80 dark:border-slate-800'"
                    >
                        <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Akun Masyarakat (Public)</span>
                        <span class="block text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ stats.public }}</span>
                    </button>
                </div>

                <!-- 3. Filter Bar & Search -->
                <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <!-- Role Filter Pills -->
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <button
                            @click="activeRole = 'all'; applyFilters()"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
                            :class="activeRole === 'all' ? 'bg-[#0F5E3D] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                        >
                            Semua Role
                        </button>
                        <button
                            @click="activeRole = 'admin'; applyFilters()"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
                            :class="activeRole === 'admin' ? 'bg-amber-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                        >
                            Admin Only
                        </button>
                        <button
                            @click="activeRole = 'public'; applyFilters()"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
                            :class="activeRole === 'public' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200'"
                        >
                            Public Only
                        </button>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full md:w-80 relative">
                        <input
                            v-model="searchQuery"
                            @keyup.enter="applyFilters"
                            type="text"
                            placeholder="Cari nama atau email user..."
                            class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500"
                        />
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div v-if="$page.props.flash?.success" class="p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 rounded-2xl border border-emerald-200 dark:border-emerald-800/50 text-xs font-bold flex items-center justify-between">
                    <span>✅ {{ $page.props.flash.success }}</span>
                </div>
                <div v-if="$page.props.errors?.error" class="p-4 bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 rounded-2xl border border-rose-200 dark:border-rose-800/50 text-xs font-bold flex items-center justify-between">
                    <span>⚠️ {{ $page.props.errors.error }}</span>
                </div>

                <!-- 4. Data Table -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-950 text-slate-400 font-bold uppercase border-b border-slate-200/80 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-4">Nama Lengkap</th>
                                    <th class="px-6 py-4">Alamat Email</th>
                                    <th class="px-6 py-4">Role Akses</th>
                                    <th class="px-6 py-4">Terdaftar</th>
                                    <th class="px-6 py-4 text-right">Aksi Kelola</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr v-for="u in users.data" :key="u.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                    
                                    <!-- Nama -->
                                    <td class="px-6 py-4 whitespace-nowrap font-extrabold text-slate-900 dark:text-white flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-700 dark:text-slate-300 text-xs shrink-0">
                                            {{ u.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <span>{{ u.name }}</span>
                                    </td>

                                    <!-- Email -->
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-600 dark:text-slate-300 font-medium">
                                        {{ u.email }}
                                    </td>

                                    <!-- Role Badge -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider" :class="getRoleBadgeClass(u.role)">
                                            {{ u.role === 'admin' ? 'Administrator' : 'Masyarakat (Public)' }}
                                        </span>
                                    </td>

                                    <!-- Tanggal Terdaftar -->
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                        {{ u.created_at ? new Date(u.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-' }}
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                        <button
                                            @click="openResetModal(u)"
                                            class="px-3 py-1.5 text-[10px] font-bold text-amber-700 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/60 dark:text-amber-300 rounded-lg transition border border-amber-200 dark:border-amber-900"
                                            title="Reset Password"
                                        >
                                            Reset Password
                                        </button>

                                        <Link
                                            :href="route('admin.users.edit', u.id)"
                                            class="px-3 py-1.5 text-[10px] font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg transition"
                                        >
                                            Edit
                                        </Link>

                                        <Link
                                            v-if="$page.props.auth.user.id !== u.id"
                                            :href="route('admin.users.destroy', u.id)"
                                            method="delete"
                                            as="button"
                                            class="px-3 py-1.5 text-[10px] font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 rounded-lg transition border border-rose-200 dark:border-rose-900"
                                            onbeforeunload=""
                                        >
                                            Hapus
                                        </Link>
                                    </td>
                                </tr>

                                <tr v-if="users.data.length === 0">
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        Tidak ada akun pengguna yang ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div v-if="users.links && users.links.length > 3" class="p-4 border-t border-slate-100 dark:border-slate-800 flex justify-center gap-1">
                        <Component
                            v-for="link in users.links"
                            :key="link.label"
                            :is="link.url ? Link : 'span'"
                            :href="link.url"
                            v-html="link.label"
                            class="px-3 py-1 text-xs rounded-lg font-bold transition"
                            :class="link.active ? 'bg-[#0F5E3D] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                        />
                    </div>
                </div>

            </div>
        </div>

        <!-- Reset Password Modal -->
        <div v-if="showResetModal && selectedUser" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl border border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between border-b pb-3 border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Reset Password Akun</h3>
                    <button @click="showResetModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                </div>

                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Setel ulang password baru secara langsung untuk user <strong>{{ selectedUser.name }}</strong> ({{ selectedUser.email }}).
                </p>

                <form @submit.prevent="submitResetPassword" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Password Baru</label>
                        <input
                            v-model="resetForm.password"
                            type="password"
                            required
                            class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Minimal 8 karakter"
                        />
                        <span v-if="resetForm.errors.password" class="text-[10px] text-rose-500 font-bold mt-1 block">{{ resetForm.errors.password }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                        <input
                            v-model="resetForm.password_confirmation"
                            type="password"
                            required
                            class="w-full text-xs p-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-emerald-500 focus:border-emerald-500"
                            placeholder="Ulangi password baru"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showResetModal = false" class="px-4 py-2 text-xs font-bold text-slate-500">Batal</button>
                        <button
                            type="submit"
                            :disabled="resetForm.processing"
                            class="px-5 py-2 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition shadow-md"
                        >
                            Reset Password Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
