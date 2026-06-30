<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    location: '',
    photos: []
});

const submit = () => {
    form.post(route('service-rakyat.tourism-submissions.store'), {
        onSuccess: () => form.reset()
    });
};
</script>

<template>
    <Head title="Form Usulan Wisata Baru" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Pengajuan Usulan Wisata Baru
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 p-8 border-t-4 border-[#0F5E3D]">
                    <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white">Usulkan Destinasi Wisata Baru</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Nama Destinasi Wisata" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="location" value="Alamat / Lokasi Wisata" />
                            <TextInput
                                id="location"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.location"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Deskripsi Tempat Wisata" />
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
                            <InputLabel for="photos" value="Foto Destinasi (Maksimal 5 Foto, @max 2MB)" />
                            <input
                                id="photos"
                                type="file"
                                multiple
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-[#0F5E3D] hover:file:bg-emerald-100"
                                @change="form.photos = Array.from($event.target.files)"
                            />
                            <InputError class="mt-2" :message="form.errors.photos" />
                        </div>

                        <div class="flex items-center justify-end">
                            <PrimaryButton class="bg-[#0F5E3D] hover:bg-emerald-700 active:bg-emerald-800" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Kirim Usulan
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
