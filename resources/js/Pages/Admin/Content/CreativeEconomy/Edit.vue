<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TiptapEditor from '@/Components/TiptapEditor.vue'

const props = defineProps({
  item: Object
})

const form = useForm({
  _method: 'PUT',
  name: props.item.name,
  description: props.item.description,
  owner_name: props.item.owner_name || '',
  contact: props.item.contact || '',
  address: props.item.address || '',
  status: props.item.status,
  cover_image: null
})

const submit = () => {
  form.post(route('admin.creative-economies.update', props.item.id), {
    forceFormData: true
  })
}

const handleCoverImageChange = (e) => {
  form.cover_image = e.target.files[0]
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Edit Pelaku Ekraf</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100">
          <div class="p-6 text-slate-900">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Nama Usaha / Pelaku Ekraf</label>
                <input 
                  id="name"
                  v-model="form.name"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan nama usaha..."
                />
                <span v-if="form.errors.name" class="text-xs text-rose-600 mt-1 block">{{ form.errors.name }}</span>
              </div>

              <!-- Owner Name -->
              <div>
                <label for="owner_name" class="block text-sm font-semibold text-slate-700">Nama Pemilik / Pengelola</label>
                <input 
                  id="owner_name"
                  v-model="form.owner_name"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan nama pemilik..."
                />
                <span v-if="form.errors.owner_name" class="text-xs text-rose-600 mt-1 block">{{ form.errors.owner_name }}</span>
              </div>

              <!-- Contact -->
              <div>
                <label for="contact" class="block text-sm font-semibold text-slate-700">Nomor Telepon / WhatsApp / Media Sosial</label>
                <input 
                  id="contact"
                  v-model="form.contact"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Contoh: 08123456789 atau @instagram"
                />
                <span v-if="form.errors.contact" class="text-xs text-rose-600 mt-1 block">{{ form.errors.contact }}</span>
              </div>

              <!-- Address -->
              <div>
                <label for="address" class="block text-sm font-semibold text-slate-700">Alamat Usaha</label>
                <input 
                  id="address"
                  v-model="form.address"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan alamat lengkap usaha..."
                />
                <span v-if="form.errors.address" class="text-xs text-rose-600 mt-1 block">{{ form.errors.address }}</span>
              </div>

              <!-- Description (Tiptap) -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Produk & Usaha</label>
                <TiptapEditor v-model="form.description" />
                <span v-if="form.errors.description" class="text-xs text-rose-600 mt-1 block">{{ form.errors.description }}</span>
              </div>

              <!-- Cover Image -->
              <div>
                <label class="block text-sm font-semibold text-slate-700">Gambar Cover / Foto Usaha</label>
                <div class="flex items-center gap-4 mt-1 mb-2" v-if="item.cover_image">
                  <img :src="item.cover_image" class="h-20 w-32 object-cover rounded-md border" alt="Cover Saat Ini" />
                  <span class="text-xs text-slate-400">Gambar cover saat ini</span>
                </div>
                <input 
                  type="file" 
                  accept="image/*"
                  @change="handleCoverImageChange"
                  class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                />
                <p class="text-xs text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah gambar (Maks. 2MB)</p>
                <span v-if="form.errors.cover_image" class="text-xs text-rose-600 mt-1 block">{{ form.errors.cover_image }}</span>
              </div>

              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-semibold text-slate-700">Status</label>
                <select 
                  id="status"
                  v-model="form.status"
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                >
                  <option value="draft">Draft</option>
                  <option value="published">Published</option>
                </select>
                <span v-if="form.errors.status" class="text-xs text-rose-600 mt-1 block">{{ form.errors.status }}</span>
              </div>

              <!-- Submit Buttons -->
              <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <Link 
                  :href="route('admin.creative-economies.index')" 
                  class="px-4 py-2 border border-slate-300 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                >
                  Batal
                </Link>
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-tr-xl rounded-bl-xl font-medium text-sm shadow transition duration-150 ease-in-out disabled:opacity-50"
                >
                  {{ form.processing ? 'Memperbarui...' : 'Perbarui Pelaku Ekraf' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
