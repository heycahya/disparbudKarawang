<script setup>
import { ref, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const page = usePage();
const successMessage = ref(null);
const errorMessage = ref(null);

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            successMessage.value = flash.success;
            setTimeout(() => {
                successMessage.value = null;
            }, 5000);
        }
        if (flash?.error) {
            errorMessage.value = flash.error;
            setTimeout(() => {
                errorMessage.value = null;
            }, 5000);
        }
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav
                class="border-b border-karawang-emerald/15 bg-white dark:border-teal-800/40 dark:bg-gray-800 shadow-sm"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                                <template v-if="$page.props.auth.user?.role === 'public'">
                                    <NavLink
                                        :href="route('public.history.index')"
                                        :active="route().current('public.history.index')"
                                    >
                                        Riwayat Pengajuan
                                    </NavLink>
                                </template>
                                <template v-if="$page.props.auth.user?.role === 'admin' || $page.props.auth.user?.role === 'super_admin'">
                                    <NavLink
                                        :href="route('admin.news.index')"
                                        :active="route().current('admin.news.*')"
                                    >
                                        Berita
                                    </NavLink>
                                    <NavLink
                                        :href="route('admin.tourism-destinations.index')"
                                        :active="route().current('admin.tourism-destinations.*')"
                                    >
                                        Destinasi
                                    </NavLink>
                                    <NavLink
                                        :href="route('admin.cultures.index')"
                                        :active="route().current('admin.cultures.*')"
                                    >
                                        Kebudayaan
                                    </NavLink>
                                    <NavLink
                                        :href="route('admin.creative-economies.index')"
                                        :active="route().current('admin.creative-economies.*')"
                                    >
                                        Ekraf
                                    </NavLink>
                                    <NavLink
                                        :href="route('admin.accommodations.index')"
                                        :active="route().current('admin.accommodations.*')"
                                    >
                                        Akomodasi
                                    </NavLink>
                                    <NavLink
                                        :href="route('admin.culinary-places.index')"
                                        :active="route().current('admin.culinary-places.*')"
                                    >
                                        Kuliner
                                    </NavLink>
                                    <NavLink
                                        v-if="$page.props.auth.user?.role === 'super_admin'"
                                        :href="route('admin.manajemen-akun')"
                                        :active="route().current('admin.manajemen-akun')"
                                    >
                                        Akun
                                    </NavLink>
                                </template>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown / Auth Actions -->
                            <div class="relative ms-3" v-if="$page.props.auth.user">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                            <div class="relative ms-3 flex items-center space-x-3" v-else>
                                <Link
                                    :href="route('login')"
                                    class="text-sm font-semibold text-gray-600 hover:text-[#0F5E3D] dark:text-gray-300 dark:hover:text-emerald-400 transition"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-700 rounded-md hover:bg-emerald-800 shadow-sm transition-colors duration-200"
                                >
                                    Daftar
                                </Link>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <template v-if="$page.props.auth.user?.role === 'public'">
                            <ResponsiveNavLink
                                :href="route('public.history.index')"
                                :active="route().current('public.history.index')"
                            >
                                Riwayat Pengajuan
                            </ResponsiveNavLink>
                        </template>
                        <template v-if="$page.props.auth.user?.role === 'admin' || $page.props.auth.user?.role === 'super_admin'">
                            <ResponsiveNavLink
                                :href="route('admin.news.index')"
                                :active="route().current('admin.news.*')"
                            >
                                Berita
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('admin.tourism-destinations.index')"
                                :active="route().current('admin.tourism-destinations.*')"
                            >
                                Destinasi
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('admin.cultures.index')"
                                :active="route().current('admin.cultures.*')"
                            >
                                Kebudayaan
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('admin.creative-economies.index')"
                                :active="route().current('admin.creative-economies.*')"
                            >
                                Ekraf
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('admin.accommodations.index')"
                                :active="route().current('admin.accommodations.*')"
                            >
                                Akomodasi
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('admin.culinary-places.index')"
                                :active="route().current('admin.culinary-places.*')"
                            >
                                Kuliner
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="$page.props.auth.user?.role === 'super_admin'"
                                :href="route('admin.manajemen-akun')"
                                :active="route().current('admin.manajemen-akun')"
                            >
                                Akun
                            </ResponsiveNavLink>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        v-if="$page.props.auth.user"
                        class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-gray-800 dark:text-gray-200"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    <div v-else class="border-t border-gray-200 py-4 px-4 dark:border-gray-600 space-y-2">
                        <Link :href="route('login')" class="block text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Masuk</Link>
                        <Link :href="route('register')" class="block text-sm font-bold text-[#0F5E3D] hover:text-[#0C4E5B]">Daftar</Link>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow dark:bg-gray-800"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>

        <!-- Global Floating Toasts -->
        <div class="fixed bottom-5 right-5 z-50 flex flex-col gap-2">
            <!-- Success Toast -->
            <div v-if="successMessage" class="flex items-center p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 border-l-4 border-emerald-500 transition-all duration-300" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg dark:bg-emerald-800 dark:text-emerald-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Success icon</span>
                </div>
                <div class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-100 pr-2">{{ successMessage }}</div>
                <button @click="successMessage = null" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>

            <!-- Error Toast -->
            <div v-if="errorMessage" class="flex items-center p-4 text-gray-500 bg-white rounded-lg shadow-lg dark:text-gray-400 dark:bg-gray-800 border-l-4 border-red-500 transition-all duration-300" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm1.25 11.5a1.25 1.25 0 1 1-2.5 0v-5a1.25 1.25 0 1 1 2.5 0v5Zm0-8a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Z"/>
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
                <div class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-100 pr-2">{{ errorMessage }}</div>
                <button @click="errorMessage = null" type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
