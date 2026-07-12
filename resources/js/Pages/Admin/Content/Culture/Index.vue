<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  cultures: Object,
  filters: Object
})

const search = ref(props.filters.search || '')

watch(search, (value) => {
  router.get(route('admin.cultures.index'), { search: value }, {
    preserveState: true,
    replace: true
  })
})

const deleteCulture = (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus data kebudayaan ini?')) {
    router.delete(route('admin.cultures.destroy', id))
  }
}

const getCategoryLabel = (cat) => {
  switch (cat) {
    case 'kesenian': return 'Kesenian';
    case 'tradisi': return 'Tradisi';
    case 'warisan_budaya': return 'Warisan Budaya';
    default: return cat;
  }
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Manajemen Warisan Budaya & Seni</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
          <div class="p-6 text-slate-900">
            <!-- Header Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
              <div class="relative w-full sm:w-80">
                <input 
                  v-model="search"
                  type="text" 
                  placeholder="Cari kebudayaan..." 
                  class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-md focus:border-emerald-600 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-sm transition"
                />
                <span class="absolute left-3 top-2.5 text-slate-400">
                  <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
              </div>
              
              <Link 
                :href="route('admin.cultures.create')" 
                class="w-full sm:w-auto px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md font-medium text-sm text-center shadow transition duration-150 ease-in-out"
              >
                + Tambah Kebudayaan
              </Link>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 rounded-md">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Gambar Cover</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama Kebudayaan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                  <tr v-for="item in cultures.data" :key="item.id" class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <img :src="item.cover_image" class="h-10 w-16 object-cover rounded-md border border-slate-200 shadow-sm" alt="Cover Image" />
                    </td>
                    <td class="px-6 py-4">
                      <div class="text-sm font-semibold text-slate-800">{{ item.name }}</div>
                      <div class="text-xs text-slate-400">Dilihat: {{ item.views }} kali</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                      {{ getCategoryLabel(item.category) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        :class="item.status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full uppercase"
                      >
                        {{ item.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <div class="flex justify-end gap-2">
                        <Link :href="route('admin.cultures.edit', item.id)" class="text-emerald-700 hover:text-emerald-900 transition">
                          Edit
                        </Link>
                        <button @click="deleteCulture(item.id)" class="text-rose-600 hover:text-rose-900 transition">
                          Hapus
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="cultures.data.length === 0">
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">
                      Belum ada data kebudayaan.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-6">
              <span class="text-xs text-slate-500">
                Menampilkan {{ cultures.from || 0 }} sampai {{ cultures.to || 0 }} dari {{ cultures.total }} data
              </span>
              <div class="flex gap-1">
                <Link 
                  v-for="link in cultures.links" 
                  :key="link.label"
                  :href="link.url || '#'" 
                  v-html="link.label"
                  :class="[
                    link.active ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-white text-slate-700 hover:bg-slate-50 border-slate-300',
                    !link.url ? 'opacity-50 cursor-not-allowed' : ''
                  ]"
                  class="px-3 py-1 text-xs border rounded-md font-medium transition"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
