<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    user: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role,
});

const submit = () => {
    form.put(route('admin.users.update', props.user.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
                            <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" v-model="form.email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required />
                            <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select v-model="form.role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="admin">Admin</option>
                            </select>
                            <div v-if="form.errors.role" class="text-red-500 text-sm mt-1">{{ form.errors.role }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password Baru (Biarkan kosong jika tidak ingin mengubah)</label>
                            <input type="password" v-model="form.password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                            <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" v-model="form.password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50">
                                {{ form.processing ? 'Menyimpan...' : 'Update' }}
                            </button>
                            <Link :href="route('admin.users.index')" class="text-gray-600 hover:text-gray-900">Batal</Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
