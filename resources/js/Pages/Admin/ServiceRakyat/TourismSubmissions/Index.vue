<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  submissions: Object,
  filters: Object
})

const search = ref(props.filters.search || '')
const status = ref(props.filters.status || '')

const applyFilters = () => {
  router.get(route('admin.service-rakyat.tourism-submissions.index'), { 
    search: search.value, 
    status: status.value 
  }, {
    preserveState: true,
    replace: true
  })
}

watch([search, status], () => {
  applyFilters()
})
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Inbox Pengajuan Wisata Baru</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100">
          <div class="p-6 text-slate-900">
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
              <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-80">
                  <input 
                    v-model="search"
                    type="text" 
                    placeholder="Cari nama wisata..." 
                    class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-md focus:border-emerald-600 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 text-sm transition"
                  />
                  <span class="absolute left-3 top-2.5 text-slate-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                  </span>
                </div>

                <select 
                  v-model="status" 
                  class="border border-slate-300 rounded-md py-2 px-3 text-sm focus:border-emerald-600 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition w-full sm:w-48"
                >
                  <option value="">Semua Status</option>
                  <option value="masuk">Masuk</option>
                  <option value="ditinjau">Ditinjau</option>
                  <option value="disetujui">Disetujui</option>
                  <option value="ditolak">Ditolak</option>
                </select>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Wisata Diusulkan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengaju</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Masuk</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                  <tr v-for="item in submissions.data" :key="item.id" class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4">
                      <div class="text-sm font-semibold text-slate-800 line-clamp-1">{{ item.name }}</div>
                      <div class="text-xs text-slate-400 line-clamp-1">{{ item.address }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                      {{ item.user?.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span 
                        :class="{
                          'bg-blue-100 text-blue-800': item.status === 'masuk',
                          'bg-amber-100 text-amber-800': item.status === 'ditinjau',
                          'bg-emerald-100 text-emerald-800': item.status === 'disetujui',
                          'bg-rose-100 text-rose-800': item.status === 'ditolak'
                        }"
                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full uppercase"
                      >
                        {{ item.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                      {{ new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Link :href="route('admin.service-rakyat.tourism-submissions.show', item.id)" class="text-emerald-700 hover:text-emerald-900 transition">
                        Detail & Review
                      </Link>
                    </td>
                  </tr>
                  <tr v-if="submissions.data.length === 0">
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">
                      Belum ada data pengajuan wisata.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-6">
              <span class="text-xs text-slate-500">
                Menampilkan {{ submissions.from || 0 }} sampai {{ submissions.to || 0 }} dari {{ submissions.total }} data
              </span>
              <div class="flex gap-1">
                <Link 
                  v-for="link in submissions.links" 
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
