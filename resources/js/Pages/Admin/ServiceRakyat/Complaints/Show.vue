<script setup>
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  complaint: Object
})

const form = useForm({
  status: props.complaint.status,
  admin_note: props.complaint.admin_note || ''
})

const submitReview = () => {
  form.patch(route('admin.service-rakyat.complaints.status', props.complaint.id), {
    preserveScroll: true,
    onSuccess: () => {
      alert('Review pengaduan berhasil diperbarui!')
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Detail Review Pengaduan</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <!-- Detail Content -->
          <div class="md:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100 p-6">
              <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                <div>
                  <span class="text-xs font-semibold text-slate-400 uppercase">Subjek</span>
                  <h3 class="text-lg font-bold text-slate-800">{{ complaint.subject }}</h3>
                </div>
                <span 
                  :class="{
                    'bg-blue-100 text-blue-800': complaint.status === 'masuk',
                    'bg-amber-100 text-amber-800': complaint.status === 'ditinjau',
                    'bg-emerald-100 text-emerald-800': complaint.status === 'disetujui',
                    'bg-rose-100 text-rose-800': complaint.status === 'ditolak'
                  }"
                  class="px-3 py-1 text-xs font-semibold rounded-full uppercase"
                >
                  {{ complaint.status }}
                </span>
              </div>

              <div class="space-y-4">
                <div>
                  <span class="text-xs font-semibold text-slate-400 uppercase">Isi Laporan / Deskripsi</span>
                  <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line mt-1">{{ complaint.description }}</p>
                </div>

                <div v-if="complaint.attachment">
                  <span class="text-xs font-semibold text-slate-400 uppercase">Lampiran</span>
                  <div class="mt-2">
                    <a 
                      :href="complaint.attachment" 
                      target="_blank"
                      class="inline-flex items-center gap-2 text-xs font-medium text-emerald-700 hover:text-emerald-900 bg-emerald-50 px-3 py-2 rounded-md border border-emerald-100 transition"
                    >
                      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                      Lihat / Unduh Lampiran Berkas
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Review History -->
            <div v-if="complaint.reviewed_by" class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100 p-6">
              <h4 class="text-sm font-semibold text-slate-800 mb-3 border-b border-slate-100 pb-2">Informasi Peninjauan</h4>
              <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                  <span class="text-slate-400 block">Ditinjau Oleh</span>
                  <span class="font-medium text-slate-700">{{ complaint.reviewed_by?.name || 'Staf Admin' }}</span>
                </div>
                <div>
                  <span class="text-slate-400 block">Tanggal Peninjauan</span>
                  <span class="font-medium text-slate-700">{{ new Date(complaint.reviewed_at).toLocaleString('id-ID') }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Review Form Panel -->
          <div class="bg-white shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100 p-6 h-fit">
            <h4 class="font-bold text-slate-800 text-sm mb-4 border-b border-slate-100 pb-2">Panel Review Admin</h4>
            
            <form @submit.prevent="submitReview" class="space-y-4">
              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Pilih Status</label>
                <select 
                  v-model="form.status"
                  class="w-full text-sm border-slate-300 rounded-md focus:border-emerald-600 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition"
                >
                  <option value="masuk">Masuk</option>
                  <option value="ditinjau">Ditinjau</option>
                  <option value="disetujui">Setujui</option>
                  <option value="ditolak">Tolak</option>
                </select>
                <div v-if="form.errors.status" class="text-xs text-rose-600 mt-1">{{ form.errors.status }}</div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Catatan Admin</label>
                <textarea 
                  v-model="form.admin_note"
                  rows="4"
                  placeholder="Tambahkan catatan hasil peninjauan pengaduan..."
                  class="w-full text-sm border-slate-300 rounded-md focus:border-emerald-600 focus:ring focus:ring-emerald-200 focus:ring-opacity-50 transition"
                ></textarea>
                <div v-if="form.errors.admin_note" class="text-xs text-rose-600 mt-1">{{ form.errors.admin_note }}</div>
              </div>

              <button 
                type="submit"
                :disabled="form.processing"
                class="w-full py-2 bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-medium text-sm rounded-tr-xl rounded-bl-xl shadow transition duration-150"
              >
                {{ form.processing ? 'Menyimpan...' : 'Simpan Keputusan' }}
              </button>
            </form>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
