<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
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
  cover_image: null,
  photos: null,
  photo_captions: null,
  deleted_photo_ids: []
})

const existingPhotos = ref([...(props.item.photos || [])])
const newGalleryPhotos = ref([])

const deleteExistingPhoto = (id, index) => {
  existingPhotos.value.splice(index, 1)
  form.deleted_photo_ids.push(id)
}

const addNewGalleryPhoto = () => {
  newGalleryPhotos.value.push({ file: null, previewUrl: '', caption: '' })
}

const removeNewGalleryPhoto = (index) => {
  newGalleryPhotos.value.splice(index, 1)
}

const handleNewGalleryPhotoChange = (e, index) => {
  const file = e.target.files[0]
  if (file) {
    newGalleryPhotos.value[index].file = file
    newGalleryPhotos.value[index].previewUrl = URL.createObjectURL(file)
  }
}

const submit = () => {
  form.photos = newGalleryPhotos.value.filter(gp => gp.file).map(gp => gp.file)
  form.photo_captions = newGalleryPhotos.value.filter(gp => gp.file).map(gp => gp.caption)

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
        <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-slate-100">
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

              <!-- Gallery Photos -->
              <div class="border-t border-slate-100 pt-6">
                <!-- Existing Gallery Photos -->
                <div v-if="existingPhotos.length > 0" class="mb-6 space-y-3">
                  <h4 class="text-sm font-bold text-slate-700">Foto Galeri Saat Ini</h4>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div 
                      v-for="(photo, index) in existingPhotos" 
                      :key="photo.id"
                      class="flex items-center gap-4 p-3 border border-slate-200 rounded-lg relative bg-white"
                    >
                      <img :src="photo.photo" class="w-20 h-16 object-cover rounded border shrink-0" />
                      <div class="flex-1 min-w-0">
                        <p class="text-xs text-slate-500 truncate" :title="photo.caption">{{ photo.caption || 'Tanpa Caption' }}</p>
                      </div>
                      <button 
                        type="button" 
                        @click="deleteExistingPhoto(photo.id, index)"
                        class="p-1 rounded-full text-rose-600 hover:bg-rose-50 transition shrink-0"
                        title="Hapus foto dari database"
                      >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- New Gallery Photos -->
                <div class="flex justify-between items-center mb-4">
                  <h4 class="text-sm font-bold text-slate-700">Tambah Foto Galeri Baru</h4>
                  <button 
                    type="button" 
                    @click="addNewGalleryPhoto" 
                    class="inline-flex items-center px-3 py-1.5 border border-slate-300 shadow-sm text-xs font-semibold rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition"
                  >
                    + Tambah Foto Galeri
                  </button>
                </div>

                <div v-if="newGalleryPhotos.length === 0" class="text-center py-6 border border-dashed border-slate-300 rounded-lg text-slate-400 text-xs">
                  Belum ada foto galeri baru yang ditambahkan.
                </div>

                <div v-else class="space-y-4">
                  <div 
                    v-for="(photo, index) in newGalleryPhotos" 
                    :key="index"
                    class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 border border-slate-200 rounded-xl relative bg-slate-50/50"
                  >
                    <!-- Preview -->
                    <div class="w-full sm:w-28 h-20 bg-slate-100 rounded-lg border overflow-hidden flex items-center justify-center text-xs text-slate-400 shrink-0">
                      <img v-if="photo.previewUrl" :src="photo.previewUrl" class="w-full h-full object-cover" />
                      <span v-else>No Preview</span>
                    </div>

                    <!-- Inputs -->
                    <div class="flex-1 w-full space-y-2">
                      <input 
                        type="file" 
                        accept="image/*"
                        @change="handleNewGalleryPhotoChange($event, index)"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                      />
                      <input 
                        v-model="photo.caption"
                        type="text" 
                        class="block w-full border border-slate-300 rounded-md shadow-sm py-1.5 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 text-xs"
                        placeholder="Tulis caption untuk foto ini (opsional)..."
                      />
                    </div>

                    <!-- Remove Button -->
                    <button 
                      type="button" 
                      @click="removeNewGalleryPhoto(index)"
                      class="absolute top-2 right-2 sm:static p-1 rounded-full text-rose-600 hover:bg-rose-50 transition shrink-0"
                      title="Batal tambah foto"
                    >
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </div>
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
                  class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-md font-medium text-sm shadow transition duration-150 ease-in-out disabled:opacity-50"
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
