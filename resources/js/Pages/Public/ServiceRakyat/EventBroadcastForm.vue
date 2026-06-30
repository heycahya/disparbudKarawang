<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    organization: '',
    event_name: '',
    event_location: '',
    start_date: '',
    end_date: '',
    description: '',
    proposal: null
});

const submit = () => {
    form.post(route('service-rakyat.event-broadcasts.store'), {
        onSuccess: () => form.reset()
    });
};
</script>

<template>
    <Head title="Form Permohonan Siaran Acara" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Permohonan Siaran Acara
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800 p-8 border-t-4 border-[#0F5E3D]">
                    <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white">Pengajuan Siaran Acara / Event</h3>

                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="organization" value="Nama Instansi / Organisasi" />
                            <TextInput
                                id="organization"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.organization"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.organization" />
                        </div>

                        <div>
                            <InputLabel for="event_name" value="Nama Acara / Event" />
                            <TextInput
                                id="event_name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.event_name"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.event_name" />
                        </div>

                        <div>
                            <InputLabel for="event_location" value="Lokasi Acara" />
                            <TextInput
                                id="event_location"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.event_location"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.event_location" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="start_date" value="Tanggal Mulai" />
                                <TextInput
                                    id="start_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.start_date"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.start_date" />
                            </div>

                            <div>
                                <InputLabel for="end_date" value="Tanggal Selesai" />
                                <TextInput
                                    id="end_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.end_date"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.end_date" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="description" value="Deskripsi Detail Acara" />
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
                            <InputLabel for="proposal" value="Proposal Acara (Wajib Dokumen PDF/DOC/DOCX, max 5MB)" />
                            <input
                                id="proposal"
                                type="file"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-[#0F5E3D] hover:file:bg-emerald-100"
                                @change="form.proposal = $event.target.files[0]"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.proposal" />
                        </div>

                        <div class="flex items-center justify-end">
                            <PrimaryButton class="bg-[#0F5E3D] hover:bg-emerald-700 active:bg-emerald-800" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Kirim Permohonan
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
