<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    gallery: Object
});

const form = useForm({
    _method: 'PUT',
    title: props.gallery.title,
    category: props.gallery.category,
    media: null,
});

const submit = () => {
    form.post(route('admin.galleries.update', props.gallery.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Galeri" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Galeri</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Galeri</label>
                            <input type="text" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select v-model="form.category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="wisata">Wisata</option>
                                <option value="budaya">Budaya</option>
                                <option value="ekraf">Ekraf</option>
                                <option value="event">Event</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <div v-if="form.errors.category" class="text-red-500 text-sm mt-1">{{ form.errors.category }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ganti File Media (Opsional)</label>
                            <div class="mb-2">
                                <img v-if="props.gallery.photo" :src="props.gallery.photo" alt="Current Image" class="h-32 object-cover rounded" />
                            </div>
                            <input type="file" @input="form.media = $event.target.files[0]" class="mt-1 block w-full" accept="image/*" />
                            <div v-if="form.errors.media" class="text-red-500 text-sm mt-1">{{ form.errors.media }}</div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                                {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                            </button>
                            <Link :href="route('admin.galleries.index')" class="text-gray-600 hover:text-gray-900">Batal</Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
