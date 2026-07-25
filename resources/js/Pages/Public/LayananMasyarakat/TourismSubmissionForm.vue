<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    category: '',
    location: '',
    description: '',
    contact: '',
    operating_hours: '',
    ticket_price: '',
    photos: []
});

const fileError = ref('');

function sanitizeContact(e) {
    form.contact = e.target.value.replace(/\D/g, '').slice(0, 15);
}

function sanitizeTicketPrice(e) {
    form.ticket_price = e.target.value.replace(/\D/g, '');
}

function handlePhotosChange(e) {
    fileError.value = '';
    const files = Array.from(e.target.files);
    if (files.length === 0) return;

    const maxBytes = 2 * 1024 * 1024; // 2MB
    const oversized = files.find(f => f.size > maxBytes);

    if (oversized) {
        fileError.value = `File "${oversized.name}" (${(oversized.size / (1024 * 1024)).toFixed(2)} MB) melebihi batas maksimal 2MB!`;
        e.target.value = '';
        form.photos = [];
        return;
    }

    form.photos = files;
}

const submit = () => {
    if (fileError.value) return;

    form.post(route('layanan-masyarakat.tourism-submissions.store'), {
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
    <Head title="Form Usulan Wisata Baru - Dashboard User" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                    Pengajuan Usulan Wisata Baru
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
                <div class="overflow-hidden bg-white shadow-xl rounded-3xl dark:bg-slate-900 p-8 border-t-4 border-amber-500">
                    <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Usulkan Destinasi Wisata Baru</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan objek wisata lokal yang berpotensi agar dapat diverifikasi oleh tim Dinas.</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Nama Destinasi & Kategori -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="name" value="Nama Destinasi Wisata" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full focus:ring-amber-500 focus:border-amber-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.name }"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    placeholder="Contoh: Curug Cigentis Baru"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="category" value="Kategori Wisata" />
                                <select
                                    id="category"
                                    v-model="form.category"
                                    class="mt-1 block w-full border-slate-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm text-xs font-medium py-2.5"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.category }"
                                    required
                                >
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Wisata Alam">Wisata Alam</option>
                                    <option value="Wisata Buatan / Wahana">Wisata Buatan / Wahana</option>
                                    <option value="Wisata Budaya & Sejarah">Wisata Budaya & Sejarah</option>
                                    <option value="Wisata Kuliner">Wisata Kuliner</option>
                                    <option value="Wisata Bahari / Pantai">Wisata Bahari / Pantai</option>
                                    <option value="Desa Wisata">Desa Wisata</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category" />
                            </div>
                        </div>

                        <!-- Alamat / Lokasi -->
                        <div>
                            <InputLabel for="location" value="Alamat / Lokasi Wisata" />
                            <TextInput
                                id="location"
                                type="text"
                                class="mt-1 block w-full focus:ring-amber-500 focus:border-amber-500"
                                :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.location }"
                                v-model="form.location"
                                required
                                placeholder="Contoh: Desa Mekarbuana, Kec. Tegalwaru, Karawang"
                            />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>

                        <!-- Deskripsi Lengkap -->
                        <div>
                            <div class="flex justify-between items-center">
                                <InputLabel for="description" value="Deskripsi Lengkap Tempat Wisata" />
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                    {{ form.description ? form.description.length : 0 }} / 1000 karakter
                                </span>
                            </div>
                            <textarea
                                id="description"
                                class="mt-1 block w-full border-slate-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm text-xs p-3"
                                :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.description }"
                                rows="5"
                                v-model="form.description"
                                maxlength="1000"
                                required
                                placeholder="Ceritakan keindahan, keunikan, daya tarik utama, serta aksesibilitas..."
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <!-- Kontak, Jam Operasional & Tiket -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <InputLabel for="contact" value="Kontak Pengelola / WA (Angka Only)" />
                                <TextInput
                                    id="contact"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="15"
                                    class="mt-1 block w-full text-xs focus:ring-amber-500 focus:border-amber-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.contact }"
                                    v-model="form.contact"
                                    @input="sanitizeContact"
                                    placeholder="Contoh: 081234567890"
                                />
                                <InputError class="mt-2" :message="form.errors.contact" />
                            </div>

                            <div>
                                <InputLabel for="operating_hours" value="Jam Operasional (Opsional)" />
                                <TextInput
                                    id="operating_hours"
                                    type="text"
                                    class="mt-1 block w-full text-xs focus:ring-amber-500 focus:border-amber-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.operating_hours }"
                                    v-model="form.operating_hours"
                                    placeholder="Contoh: 08.00 - 17.00 WIB"
                                />
                                <InputError class="mt-2" :message="form.errors.operating_hours" />
                            </div>

                            <div>
                                <InputLabel for="ticket_price" value="Harga Tiket (Rp - Angka Only)" />
                                <TextInput
                                    id="ticket_price"
                                    type="text"
                                    inputmode="numeric"
                                    class="mt-1 block w-full text-xs focus:ring-amber-500 focus:border-amber-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.ticket_price }"
                                    v-model="form.ticket_price"
                                    @input="sanitizeTicketPrice"
                                    placeholder="Contoh: 15000"
                                />
                                <InputError class="mt-2" :message="form.errors.ticket_price" />
                            </div>
                        </div>

                        <!-- Upload Foto Lampiran -->
                        <div>
                            <InputLabel for="photos" value="Foto Lampiran (.jpg, .png, .webp - Max 2MB per foto)" />
                            <input
                                id="photos"
                                type="file"
                                multiple
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="mt-1 block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 dark:file:bg-amber-950 dark:file:text-amber-300"
                                @change="handlePhotosChange"
                            />
                            <p v-if="fileError" class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                ⚠️ {{ fileError }}
                            </p>
                            <InputError class="mt-2" :message="form.errors.photos" />
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
                                class="bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs py-3 px-8 rounded-xl transition duration-200 disabled:opacity-50 shadow-md"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing || !!fileError"
                            >
                                {{ form.processing ? 'Mengirim...' : 'Kirim Usulan Wisata' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
