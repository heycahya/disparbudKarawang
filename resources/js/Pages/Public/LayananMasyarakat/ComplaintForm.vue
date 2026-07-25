<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    title: '',
    category: '',
    location: '',
    description: '',
    attachment: null
});

const fileError = ref('');

function handleFileChange(e) {
    fileError.value = '';
    const file = e.target.files[0];
    if (!file) return;

    const maxBytes = 2 * 1024 * 1024; // 2MB
    if (file.size > maxBytes) {
        fileError.value = `File "${file.name}" (${(file.size / (1024 * 1024)).toFixed(2)} MB) melebihi batas maksimal 2MB!`;
        e.target.value = '';
        form.attachment = null;
        return;
    }

    form.attachment = file;
}

const submit = () => {
    if (fileError.value) return;

    form.post(route('layanan-masyarakat.complaints.store'), {
        onSuccess: () => form.reset(),
        onError: () => {
            setTimeout(() => {
                const firstError = document.querySelector('.border-rose-500, .text-rose-600, input:invalid, textarea:invalid, select:invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (typeof firstError.focus === 'function') firstError.focus();
                }
            }, 100);
        }
    });
};
</script>

<template>
    <Head title="Form Laporan Pengaduan - Dashboard User" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                    Form Laporan Pengaduan Masyarakat
                </h2>
                <Link
                    :href="route('dashboard')"
                    class="px-4 py-2 text-xs font-bold text-[#004b23] bg-emerald-50 dark:bg-emerald-950/60 hover:bg-emerald-100 dark:hover:bg-emerald-900 rounded-lg transition"
                >
                    &larr; Kembali ke Dashboard
                </Link>
            </div>
        </template>

        <div class="py-12 bg-slate-50 min-h-[calc(100vh-8rem)] dark:bg-slate-950 font-sans">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl rounded-xl dark:bg-slate-900 p-8 border-t-4 border-[#004b23]">
                    <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Buat Laporan Pengaduan Baru</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sampaikan kendala fasilitas pariwisata atau cagar budaya Karawang secara resmi.</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Judul Laporan & Kategori -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="title" value="Judul Laporan Pengaduan" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full focus:ring-[#0F5E3D] focus:border-[#0F5E3D]"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.title }"
                                    v-model="form.title"
                                    required
                                    autofocus
                                    placeholder="Contoh: Kerusakan Fasilitas di Candi Jiwa"
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="category" value="Kategori Layanan / Fasilitas" />
                                <select
                                    id="category"
                                    v-model="form.category"
                                    class="mt-1 block w-full border-slate-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 focus:border-[#0F5E3D] focus:ring-[#0F5E3D] rounded-xl shadow-sm text-xs font-medium py-2.5"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.category }"
                                    required
                                >
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Fasilitas Destinasi Wisata">Fasilitas Destinasi Wisata</option>
                                    <option value="Pelestarian Cagar Budaya">Pelestarian Cagar Budaya</option>
                                    <option value="Pelayanan Publik & Perizinan">Pelayanan Publik & Perizinan</option>
                                    <option value="Kebersihan & Keamanan Area">Kebersihan & Keamanan Area</option>
                                    <option value="Kebudayaan & Ekraf">Kebudayaan & Ekraf</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category" />
                            </div>
                        </div>

                        <!-- Lokasi Kejadian -->
                        <div>
                            <InputLabel for="location" value="Lokasi Kejadian / Objek Pengaduan (Opsional)" />
                            <TextInput
                                id="location"
                                type="text"
                                class="mt-1 block w-full text-xs focus:ring-[#0F5E3D] focus:border-[#0F5E3D]"
                                :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.location }"
                                v-model="form.location"
                                placeholder="Contoh: Kompleks Candi Jiwa Batujaya, Karawang"
                            />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>

                        <!-- Narasi Detail Pengaduan -->
                        <div>
                            <div class="flex justify-between items-center">
                                <InputLabel for="description" value="Narasi Pengaduan / Detail Masalah" />
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                    {{ form.description ? form.description.length : 0 }} / 1000 karakter
                                </span>
                            </div>
                            <textarea
                                id="description"
                                class="mt-1 block w-full border-slate-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 focus:border-[#0F5E3D] focus:ring-[#0F5E3D] rounded-xl shadow-sm text-xs p-3"
                                :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.description }"
                                rows="5"
                                v-model="form.description"
                                maxlength="1000"
                                required
                                placeholder="Jelaskan secara detail lokasi, kronologi kejadian, serta dampak masalah..."
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <!-- Lampiran Bukti Foto / Dokumen -->
                        <div>
                            <InputLabel for="attachment" value="Lampiran Bukti Foto / Dokumen (Gambar .jpg, .png, .webp - Max 2MB)" />
                            <input
                                id="attachment"
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="mt-1 block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-[#0F5E3D] hover:file:bg-emerald-100 dark:file:bg-emerald-950 dark:file:text-emerald-300"
                                @change="handleFileChange"
                            />
                            <p v-if="fileError" class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                ⚠️ {{ fileError }}
                            </p>
                            <InputError class="mt-2" :message="form.errors.attachment" />
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <Link
                                :href="route('dashboard')"
                                class="px-5 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-400 hover:text-slate-800 transition"
                            >
                                Batal
                            </Link>
                            <button
                                type="submit"
                                class="bg-[#004b23] hover:bg-[#003d1d] text-white font-bold text-xs py-3 px-8 rounded-lg transition duration-200 disabled:opacity-50 shadow-md"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing || !!fileError"
                            >
                                {{ form.processing ? 'Mengirim...' : 'Kirim Pengaduan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
