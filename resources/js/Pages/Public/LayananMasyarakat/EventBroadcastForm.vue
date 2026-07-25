<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    organization: '',
    event_name: '',
    event_location: '',
    start_date: '',
    end_date: '',
    description: '',
    target_audience: '',
    proposal: null
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
        form.proposal = null;
        return;
    }

    form.proposal = file;
}

const submit = () => {
    if (fileError.value) return;

    form.post(route('layanan-masyarakat.event-broadcasts.store'), {
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
    <Head title="Form Permohonan Siaran Acara - Dashboard User" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold leading-tight text-slate-800 dark:text-slate-200">
                    Pengajuan Permohonan Siaran Acara / Event
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
                <div class="overflow-hidden bg-white shadow-xl rounded-3xl dark:bg-slate-900 p-8 border-t-4 border-teal-500">
                    <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Pengajuan Siaran Acara / Event Kebudayaan</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Publikasikan agenda event kebudayaan, festival kriya, atau pameran komunitas Anda secara resmi.</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Penyelenggara & Nama Event -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="organization" value="Nama Instansi / Penyelenggara" />
                                <TextInput
                                    id="organization"
                                    type="text"
                                    class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.organization }"
                                    v-model="form.organization"
                                    required
                                    autofocus
                                    placeholder="Contoh: Karang Taruna / Komunitas Seni"
                                />
                                <InputError class="mt-2" :message="form.errors.organization" />
                            </div>

                            <div>
                                <InputLabel for="event_name" value="Nama Acara / Event Kebudayaan" />
                                <TextInput
                                    id="event_name"
                                    type="text"
                                    class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.event_name }"
                                    v-model="form.event_name"
                                    required
                                    placeholder="Contoh: Festival Kopi & Seni Karawang 2026"
                                />
                                <InputError class="mt-2" :message="form.errors.event_name" />
                            </div>
                        </div>

                        <!-- Rentang Tanggal Mulai & Selesai -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="start_date" value="Tanggal & Waktu Mulai" />
                                <TextInput
                                    id="start_date"
                                    type="date"
                                    class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.start_date }"
                                    v-model="form.start_date"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.start_date" />
                            </div>

                            <div>
                                <InputLabel for="end_date" value="Tanggal & Waktu Selesai" />
                                <TextInput
                                    id="end_date"
                                    type="date"
                                    class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.end_date }"
                                    v-model="form.end_date"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.end_date" />
                            </div>
                        </div>

                        <!-- Lokasi Event & Target Pengunjung -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="event_location" value="Lokasi Pelaksanaan Event" />
                                <TextInput
                                    id="event_location"
                                    type="text"
                                    class="mt-1 block w-full focus:ring-teal-500 focus:border-teal-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.event_location }"
                                    v-model="form.event_location"
                                    required
                                    placeholder="Contoh: Lapangan Karangpawitan / Gedung Budaya"
                                />
                                <InputError class="mt-2" :message="form.errors.event_location" />
                            </div>

                            <div>
                                <InputLabel for="target_audience" value="Target Pengunjung (Opsional)" />
                                <TextInput
                                    id="target_audience"
                                    type="text"
                                    class="mt-1 block w-full text-xs focus:ring-teal-500 focus:border-teal-500"
                                    :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.target_audience }"
                                    v-model="form.target_audience"
                                    placeholder="Contoh: Pelajar, Umum, Pegiat Seni (Estimasi 500 orang)"
                                />
                                <InputError class="mt-2" :message="form.errors.target_audience" />
                            </div>
                        </div>

                        <!-- Deskripsi Detail Event -->
                        <div>
                            <div class="flex justify-between items-center">
                                <InputLabel for="description" value="Deskripsi Detail Event / Susunan Acara" />
                                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                    {{ form.description ? form.description.length : 0 }} / 1000 karakter
                                </span>
                            </div>
                            <textarea
                                id="description"
                                class="mt-1 block w-full border-slate-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-sm text-xs p-3"
                                :class="{ 'border-rose-500 ring-2 ring-rose-500/20': form.errors.description }"
                                rows="5"
                                v-model="form.description"
                                maxlength="1000"
                                required
                                placeholder="Jelaskan gambaran umum acara, pertunjukan utama, dan bintang tamu..."
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <!-- Upload Banner / Poster / Proposal -->
                        <div>
                            <InputLabel for="proposal" value="Upload Banner / Poster / Proposal Acara (Gambar .jpg, .png, .webp - Max 2MB)" />
                            <input
                                id="proposal"
                                type="file"
                                accept="image/jpeg,image/png,image/jpg,image/webp,application/pdf"
                                class="mt-1 block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 dark:file:bg-teal-950 dark:file:text-teal-300"
                                @change="handleFileChange"
                                required
                            />
                            <p v-if="fileError" class="text-xs text-rose-600 font-bold mt-1.5 flex items-center gap-1">
                                ⚠️ {{ fileError }}
                            </p>
                            <InputError class="mt-2" :message="form.errors.proposal" />
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
                                class="bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs py-3 px-8 rounded-xl transition duration-200 disabled:opacity-50 shadow-md"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing || !!fileError"
                            >
                                {{ form.processing ? 'Mengirim...' : 'Kirim Permohonan Event' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
