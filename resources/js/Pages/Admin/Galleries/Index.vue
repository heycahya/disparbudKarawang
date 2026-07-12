<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    galleries: Object
});
</script>

<template>
    <Head title="Manajemen Galeri" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Galeri</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-semibold">Daftar Galeri</h3>
                        <Link :href="route('admin.galleries.create')" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah Galeri</Link>
                    </div>

                    <div v-if="$page.props.flash.success" class="mb-4 p-4 text-green-700 bg-green-100 rounded">
                        {{ $page.props.flash.success }}
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="item in galleries.data" :key="item.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ item.title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ item.category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <Link :href="route('admin.galleries.edit', item.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                    <Link :href="route('admin.galleries.destroy', item.id)" method="delete" as="button" class="text-red-600 hover:text-red-900">Hapus</Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
