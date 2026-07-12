<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TiptapEditor from '@/Components/TiptapEditor.vue'

const form = useForm({
  name: '',
  type: '',
  description: '',
  address: '',
  phone: '',
  price_range: '',
  latitude: '',
  longitude: '',
  status: 'draft',
  cover_image: null
})

const submit = () => {
  form.post(route('admin.culinary-places.store'), {
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
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Tambah Tempat Kuliner Baru</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
          <div class="p-6 text-slate-900">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Nama Tempat Kuliner / Rumah Makan</label>
                <input 
                  id="name"
                  v-model="form.name"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan nama tempat kuliner..."
                />
                <span v-if="form.errors.name" class="text-xs text-rose-600 mt-1 block">{{ form.errors.name }}</span>
              </div>

              <!-- Type -->
              <div>
                <label for="type" class="block text-sm font-semibold text-slate-700">Tipe Tempat Kuliner</label>
                <select 
                  id="type"
                  v-model="form.type"
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                >
                  <option value="">Pilih Tipe</option>
                  <option value="restoran">Restoran</option>
                  <option value="cafe">Cafe</option>
                  <option value="warung">Warung Makan / Kedai</option>
                  <option value="rumah_makan">Rumah Makan Sunda / Umum</option>
                </select>
                <span v-if="form.errors.type" class="text-xs text-rose-600 mt-1 block">{{ form.errors.type }}</span>
              </div>

              <!-- Price Range -->
              <div>
                <label for="price_range" class="block text-sm font-semibold text-slate-700">Estimasi Range Harga Makanan</label>
                <input 
                  id="price_range"
                  v-model="form.price_range"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Contoh: Rp 15.000 - Rp 100.000"
                />
                <span v-if="form.errors.price_range" class="text-xs text-rose-600 mt-1 block">{{ form.errors.price_range }}</span>
              </div>

              <!-- Phone -->
              <div>
                <label for="phone" class="block text-sm font-semibold text-slate-700">Nomor Telepon / Kontak</label>
                <input 
                  id="phone"
                  v-model="form.phone"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan nomor telepon..."
                />
                <span v-if="form.errors.phone" class="text-xs text-rose-600 mt-1 block">{{ form.errors.phone }}</span>
              </div>

              <!-- Address -->
              <div>
                <label for="address" class="block text-sm font-semibold text-slate-700">Alamat Lengkap</label>
                <input 
                  id="address"
                  v-model="form.address"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan alamat lengkap..."
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

              <!-- Description (Tiptap) -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi & Menu Andalan</label>
                <TiptapEditor v-model="form.description" />
                <span v-if="form.errors.description" class="text-xs text-rose-600 mt-1 block">{{ form.errors.description }}</span>
              </div>

              <!-- Cover Image -->
              <div>
                <label class="block text-sm font-semibold text-slate-700">Gambar Cover / Foto Makanan</label>
                <input 
                  type="file" 
                  accept="image/*"
                  @change="handleCoverImageChange"
                  class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                />
                <p class="text-xs text-slate-400 mt-1">Format gambar: JPG, PNG, WEBP (Maks. 2MB)</p>
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
                  :href="route('admin.culinary-places.index')" 
                  class="px-4 py-2 border border-slate-300 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                >
                  Batal
                </Link>
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md font-medium text-sm shadow transition duration-150 ease-in-out disabled:opacity-50"
                >
                  {{ form.processing ? 'Menyimpan...' : 'Simpan Kuliner' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
