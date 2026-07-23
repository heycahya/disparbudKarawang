<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';

const props = defineProps({
    cultures: Object,
    filters: Object
});

const search = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category || '');
const selectedStatus = ref(props.filters?.status || '');

// Delete modal state
const itemToDelete = ref(null);
const showDeleteModal = ref(false);
const isDeleting = ref(false);

function applyFilters() {
    router.get(route('admin.cultures.index'), {
        search: search.value,
        category: selectedCategory.value,
        status: selectedStatus.value
    }, {
        preserveState: true,
        replace: true
    });
}

function promptDelete(item) {
    itemToDelete.value = item;
    showDeleteModal.value = true;
}

function confirmDelete() {
    if (!itemToDelete.value) return;
    isDeleting.value = true;
    router.delete(route('admin.cultures.destroy', itemToDelete.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            itemToDelete.value = null;
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        }
    });
}
</script>

<template>
    <Head title="Manajemen Cagar Budaya & Seni - Admin Disparbud" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                    Manajemen Kebudayaan
                </h2>
                <Link
                    :href="route('admin.cultures.create')"
                    class="px-4 py-2 text-xs font-bold text-white bg-[#0F5E3D] hover:bg-emerald-800 rounded-xl transition shadow-md flex items-center gap-1.5"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kebudayaan
                </Link>
            </div>
        </template>

        <div class="py-8 bg-slate-50 min-h-[calc(100vh-8rem)] dark:bg-slate-950 font-sans">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Filter Bar -->
                <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <!-- Category Filter -->
                        <select
                            v-model="selectedCategory"
                            @change="applyFilters"
                            class="py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 focus:ring-emerald-500 focus:border-emerald-500"
                        >
                            <option value="">Semua Kategori</option>
                            <option value="Seni Tradisional">Seni Tradisional</option>
                            <option value="Situs Sejarah">Situs Sejarah</option>
                            <option value="Cagar Budaya">Cagar Budaya</option>
                            <option value="Upacara Adat">Upacara Adat</option>
                        </select>

                        <!-- Status Filter -->
                        <select
                            v-model="selectedStatus"
                            @change="applyFilters"
                            class="py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 focus:ring-emerald-500 focus:border-emerald-500"
                        >
                            <option value="">Semua Status</option>
                            <option value="published">Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="w-full md:w-72 relative">
                        <input
                            v-model="search"
                            @keyup.enter="applyFilters"
                            type="text"
                            placeholder="Cari kebudayaan..."
                            class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs focus:ring-emerald-500 focus:border-emerald-500"
                        />
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Datagrid Table -->
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-950 text-slate-400 font-bold uppercase border-b border-slate-200/80 dark:border-slate-800">
                                <tr>
                                    <th class="px-6 py-4">Cover</th>
                                    <th class="px-6 py-4">Nama Warisan / Seni</th>
                                    <th class="px-6 py-4">Kategori</th>
                                    <th class="px-6 py-4">Lokasi</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                <tr v-for="item in cultures.data" :key="item.id" class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img :src="item.cover_image" class="h-12 w-20 object-cover rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm" alt="Cover" />
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-extrabold text-slate-900 dark:text-white text-sm">{{ item.name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5">👁️ Views: {{ item.views || 0 }} kali</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-600 dark:text-slate-300">
                                        {{ item.category || '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                                        {{ item.location || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2.5 py-1 inline-flex text-[10px] font-extrabold rounded-full uppercase tracking-wider"
                                            :class="item.status === 'published' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300'"
                                        >
                                            {{ item.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                        <Link
                                            :href="route('admin.cultures.edit', item.id)"
                                            class="px-3 py-1.5 text-[10px] font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg transition"
                                        >
                                            Edit
                                        </Link>
                                        <button
                                            @click="promptDelete(item)"
                                            class="px-3 py-1.5 text-[10px] font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/60 dark:text-rose-300 rounded-lg transition border border-rose-200 dark:border-rose-900"
                                        >
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="cultures.data.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        Belum ada data kebudayaan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="cultures.links && cultures.links.length > 3" class="p-4 border-t border-slate-100 dark:border-slate-800 flex justify-center gap-1">
                        <Component
                            v-for="link in cultures.links"
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

        <!-- Confirm Delete Modal -->
        <ConfirmDeleteModal
            :show="showDeleteModal"
            :item-name="itemToDelete?.name"
            :processing="isDeleting"
            @close="showDeleteModal = false"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>
