<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  submission: Object
})

const form = useForm({
  status: props.submission.status,
  admin_note: props.submission.admin_note || ''
})

const submitReview = () => {
  form.patch(route('admin.service-rakyat.tourism-submissions.status', props.submission.id), {
    preserveScroll: true,
    onSuccess: () => {
      alert('Review pengajuan wisata berhasil diperbarui!')
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Detail Review Pengajuan Wisata</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <!-- Detail Content -->
          <div class="md:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100 p-6">
              
              <div v-if="submission.photo" class="mb-6 rounded-xl overflow-hidden max-h-80 border border-slate-200">
                <img :src="submission.photo" class="w-full h-full object-cover" alt="Foto Wisata" />
              </div>

              <div class="flex justify-between items-start border-b border-slate-100 pb-4 mb-4">
                <div>
                  <span class="text-xs font-semibold text-slate-400 uppercase">Nama Wisata Diusulkan</span>
                  <h3 class="text-xl font-bold text-slate-800">{{ submission.name }}</h3>
                </div>
                <span 
                  :class="{
                    'bg-blue-100 text-blue-800': submission.status === 'masuk',
                    'bg-amber-100 text-amber-800': submission.status === 'ditinjau',
                    'bg-emerald-100 text-emerald-800': submission.status === 'disetujui',
                    'bg-rose-100 text-rose-800': submission.status === 'ditolak'
                  }"
                  class="px-3 py-1 text-xs font-semibold rounded-full uppercase"
                >
                  {{ submission.status }}
                </span>
              </div>

              <div class="space-y-4">
                <div>
                  <span class="text-xs font-semibold text-slate-400 uppercase">Deskripsi Destinasi</span>
                  <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line mt-1">{{ submission.description }}</p>
                </div>

                <div>
                  <span class="text-xs font-semibold text-slate-400 uppercase">Alamat Lengkap</span>
                  <p class="text-sm text-slate-700 mt-1 font-medium">{{ submission.address }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase">Latitude</span>
                    <p class="text-sm text-slate-700 mt-1 font-mono">{{ submission.latitude || '-' }}</p>
                  </div>
                  <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase">Longitude</span>
                    <p class="text-sm text-slate-700 mt-1 font-mono">{{ submission.longitude || '-' }}</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Conversion Details if Approved -->
            <div v-if="submission.converted_destination_id" class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-emerald-200 p-6 bg-emerald-50/20">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="text-sm font-bold text-emerald-800">✅ Terkonversi Ke Katalog Utama</h4>
                  <p class="text-xs text-emerald-600 mt-1">Destinasi wisata ini telah otomatis disalin ke katalog utama pariwisata (sebagai status draft).</p>
                </div>
                <Link 
                  :href="route('admin.tourism-destinations.edit', submission.converted_destination_id)"
                  class="text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-3 py-2 rounded-md shadow transition"
                >
                  Edit di Katalog Wisata
                </Link>
              </div>
            </div>

            <!-- Review History -->
            <div v-if="submission.reviewed_by" class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100 p-6">
              <h4 class="text-sm font-semibold text-slate-800 mb-3 border-b border-slate-100 pb-2">Informasi Peninjauan</h4>
              <div class="grid grid-cols-2 gap-4 text-xs">
                <div>
                  <span class="text-slate-400 block">Ditinjau Oleh</span>
                  <span class="font-medium text-slate-700">{{ submission.reviewed_by?.name || 'Staf Admin' }}</span>
                </div>
                <div>
                  <span class="text-slate-400 block">Tanggal Peninjauan</span>
                  <span class="font-medium text-slate-700">{{ new Date(submission.reviewed_at).toLocaleString('id-ID') }}</span>
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
                  <option value="disetujui">Setujui & Konversi</option>
                  <option value="ditolak">Tolak</option>
                </select>
                <div v-if="form.errors.status" class="text-xs text-rose-600 mt-1">{{ form.errors.status }}</div>
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Catatan Admin</label>
                <textarea 
                  v-model="form.admin_note"
                  rows="4"
                  placeholder="Tambahkan catatan hasil peninjauan destinasi wisata..."
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
