<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    attachment: null
});

const submit = () => {
    form.post(route('service-rakyat.complaints.store'), {
        onSuccess: () => form.reset()
    });
};
</script>

<template>
    <Head title="Form Laporan Pengaduan" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Laporan Pengaduan Masyarakat
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 p-8 border-t-4 border-[#0F5E3D]">
                    <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white">Buat Laporan Pengaduan Baru</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="title" value="Judul Laporan" />
                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Isi / Detail Pengaduan" />
                            <textarea
                                id="description"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="5"
                                v-model="form.description"
                                required
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div>
                            <InputLabel for="attachment" value="Lampiran (Optional - Dokumen max 5MB, Gambar max 2MB)" />
                            <input
                                id="attachment"
                                type="file"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-[#0F5E3D] hover:file:bg-emerald-100"
                                @change="form.attachment = $event.target.files[0]"
                            />
                            <InputError class="mt-2" :message="form.errors.attachment" />
                        </div>

                        <div class="flex items-center justify-end">
                            <PrimaryButton class="bg-[#0F5E3D] hover:bg-emerald-700 active:bg-emerald-800" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Kirim Pengaduan
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
