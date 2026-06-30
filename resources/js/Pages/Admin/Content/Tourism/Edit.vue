<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TiptapEditor from '@/Components/TiptapEditor.vue'

const props = defineProps({
  destination: Object,
  categories: Array
})

const form = useForm({
  _method: 'PUT',
  name: props.destination.name,
  tourism_category_id: props.destination.tourism_category_id,
  description: props.destination.description,
  address: props.destination.address,
  latitude: props.destination.latitude || '',
  longitude: props.destination.longitude || '',
  status: props.destination.status,
  cover_image: null
})

const submit = () => {
  // Use POST with spoofed PUT method for multipart/form-data support in PHP
  form.post(route('admin.tourism-destinations.update', props.destination.id), {
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
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Edit Destinasi Wisata</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100">
          <div class="p-6 text-slate-900">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Nama Destinasi</label>
                <input 
                  id="name"
                  v-model="form.name"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan nama destinasi..."
                />
                <span v-if="form.errors.name" class="text-xs text-rose-600 mt-1 block">{{ form.errors.name }}</span>
              </div>

              <!-- Category -->
              <div>
                <label for="category" class="block text-sm font-semibold text-slate-700">Kategori Wisata</label>
                <select 
                  id="category"
                  v-model="form.tourism_category_id"
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                >
                  <option value="">Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <span v-if="form.errors.tourism_category_id" class="text-xs text-rose-600 mt-1 block">{{ form.errors.tourism_category_id }}</span>
              </div>

              <!-- Description (Tiptap) -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi Destinasi</label>
                <TiptapEditor v-model="form.description" />
                <span v-if="form.errors.description" class="text-xs text-rose-600 mt-1 block">{{ form.errors.description }}</span>
              </div>

              <!-- Address -->
              <div>
                <label for="address" class="block text-sm font-semibold text-slate-700">Alamat Lengkap</label>
                <input 
                  id="address"
                  v-model="form.address"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan alamat destinasi..."
                />
                <span v-if="form.errors.address" class="text-xs text-rose-600 mt-1 block">{{ form.errors.address }}</span>
              </div>

              <!-- Coordinates (Latitude & Longitude) -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="latitude" class="block text-sm font-semibold text-slate-700">Latitude</label>
                  <input 
                    id="latitude"
                    v-model="form.latitude"
                    type="text" 
                    class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    placeholder="Contoh: -6.302445"
                  />
                  <span v-if="form.errors.latitude" class="text-xs text-rose-600 mt-1 block">{{ form.errors.latitude }}</span>
                </div>
                <div>
                  <label for="longitude" class="block text-sm font-semibold text-slate-700">Longitude</label>
                  <input 
                    id="longitude"
                    v-model="form.longitude"
                    type="text" 
                    class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                    placeholder="Contoh: 107.305678"
                  />
                  <span v-if="form.errors.longitude" class="text-xs text-rose-600 mt-1 block">{{ form.errors.longitude }}</span>
                </div>
              </div>

              <!-- Cover Image -->
              <div>
                <label class="block text-sm font-semibold text-slate-700">Gambar Cover</label>
                <div class="flex items-center gap-4 mt-1 mb-2" v-if="destination.cover_image">
                  <img :src="destination.cover_image" class="h-20 w-32 object-cover rounded-md border" alt="Cover Saat Ini" />
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
                  :href="route('admin.tourism-destinations.index')" 
                  class="px-4 py-2 border border-slate-300 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                >
                  Batal
                </Link>
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-tr-xl rounded-bl-xl font-medium text-sm shadow transition duration-150 ease-in-out disabled:opacity-50"
                >
                  {{ form.processing ? 'Memperbarui...' : 'Perbarui Destinasi' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
