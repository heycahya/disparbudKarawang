<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    title: '',
    category: '',
    media: null,
});

const previewUrl = ref('');

const handleMediaChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.media = file;
        previewUrl.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('admin.galleries.store'), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Tambah Galeri - Admin Disparbud" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-xl text-slate-800 dark:text-slate-200 leading-tight">
                Tambah Galeri Foto Baru
            </h2>
        </template>

        <div class="py-12 bg-slate-50 min-h-screen dark:bg-slate-950">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm rounded-2xl border border-slate-100 dark:border-slate-800">
                    <div class="p-6 sm:p-8 text-slate-900 dark:text-slate-100">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Judul Galeri -->
                            <div>
                                <label for="title" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Judul Foto / Dokumentasi</label>
                                <input 
                                    id="title"
                                    v-model="form.title"
                                    type="text" 
                                    class="mt-1 block w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 rounded-xl shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm text-slate-900 dark:text-slate-100"
                                    placeholder="Masukkan judul foto..."
                                    required 
                                />
                                <span v-if="form.errors.title" class="text-xs text-rose-600 mt-1 block">{{ form.errors.title }}</span>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="category" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Kategori</label>
                                <select 
                                    id="category"
                                    v-model="form.category"
                                    class="mt-1 block w-full border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 rounded-xl shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm text-slate-900 dark:text-slate-100"
                                    required
                                >
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option value="wisata">Wisata</option>
                                    <option value="budaya">Budaya</option>
                                    <option value="ekraf">Ekonomi Kreatif</option>
                                    <option value="event">Event & Kegiatan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                <span v-if="form.errors.category" class="text-xs text-rose-600 mt-1 block">{{ form.errors.category }}</span>
                            </div>

                            <!-- File Media -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">File Gambar (Foto/Dokumentasi)</label>
                                
                                <div v-if="previewUrl" class="mb-4">
                                    <img :src="previewUrl" class="h-48 w-full object-cover rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm" alt="Preview" />
                                </div>

                                <input 
                                    type="file" 
                                    accept="image/*"
                                    @change="handleMediaChange"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-950 dark:file:text-emerald-300 transition"
                                    required 
                                />
                                <p class="text-xs text-slate-400 mt-1">Format gambar: JPG, PNG, WEBP (Maksimal 2MB)</p>
                                <span v-if="form.errors.media" class="text-xs text-rose-600 mt-1 block">{{ form.errors.media }}</span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                                <Link 
                                    :href="route('admin.galleries.index')" 
                                    class="px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                                >
                                    Batal
                                </Link>
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="px-5 py-2.5 bg-[#0F5E3D] hover:bg-emerald-800 text-white rounded-xl text-xs font-bold shadow-md transition disabled:opacity-50 flex items-center gap-2"
                                >
                                    <span>{{ form.processing ? 'Mengunggah...' : 'Simpan Galeri' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
