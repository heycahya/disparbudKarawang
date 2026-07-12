<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';

const page = usePage();

const form = useForm({
    name: '',
    description: '',
    location: '',
    photos: []
});

const submit = () => {
    if (!page.props.auth.user) {
        router.visit(route('login'), {
            data: { intended: window.location.pathname }
        });
        return;
    }

    form.post(route('layanan-masyarakat.tourism-submissions.store'), {
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
                <div class="overflow-hidden bg-white shadow-xl rounded-asymmetric dark:bg-gray-800 p-8 border-l-8 border-[#0F5E3D]">
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
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-karawang-emerald dark:focus:border-emerald-500 focus:ring-karawang-emerald dark:focus:ring-emerald-500 rounded-asymmetric-sm shadow-sm"
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
                                <svg v-if="form.processing" class="animate-spin -ms-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Mengirim...' : 'Kirim Usulan' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
