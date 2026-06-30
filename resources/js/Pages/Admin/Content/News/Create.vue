<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import TiptapEditor from '@/Components/TiptapEditor.vue'

defineProps({
  categories: Array
})

const form = useForm({
  title: '',
  news_category_id: '',
  content: '',
  status: 'draft',
  thumbnail: null
})

const submit = () => {
  form.post(route('admin.news.store'), {
    forceFormData: true
  })
}

const handleThumbnailChange = (e) => {
  form.thumbnail = e.target.files[0]
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-slate-800 leading-tight">Tambah Berita Baru</h2>
    </template>

    <div class="py-12 bg-slate-50 min-h-screen">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-tr-2xl rounded-bl-2xl border border-slate-100">
          <div class="p-6 text-slate-900">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Title -->
              <div>
                <label for="title" class="block text-sm font-semibold text-slate-700">Judul Berita</label>
                <input 
                  id="title"
                  v-model="form.title"
                  type="text" 
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                  placeholder="Masukkan judul berita..."
                />
                <span v-if="form.errors.title" class="text-xs text-rose-600 mt-1 block">{{ form.errors.title }}</span>
              </div>

              <!-- Category -->
              <div>
                <label for="category" class="block text-sm font-semibold text-slate-700">Kategori</label>
                <select 
                  id="category"
                  v-model="form.news_category_id"
                  class="mt-1 block w-full border border-slate-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                >
                  <option value="">Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <span v-if="form.errors.news_category_id" class="text-xs text-rose-600 mt-1 block">{{ form.errors.news_category_id }}</span>
              </div>

              <!-- Content (Tiptap) -->
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Konten Berita</label>
                <TiptapEditor v-model="form.content" />
                <span v-if="form.errors.content" class="text-xs text-rose-600 mt-1 block">{{ form.errors.content }}</span>
              </div>

              <!-- Thumbnail -->
              <div>
                <label class="block text-sm font-semibold text-slate-700">Gambar Cover/Thumbnail</label>
                <input 
                  type="file" 
                  accept="image/*"
                  @change="handleThumbnailChange"
                  class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                />
                <p class="text-xs text-slate-400 mt-1">Format gambar: JPG, PNG, WEBP (Maks. 2MB)</p>
                <span v-if="form.errors.thumbnail" class="text-xs text-rose-600 mt-1 block">{{ form.errors.thumbnail }}</span>
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
                  :href="route('admin.news.index')" 
                  class="px-4 py-2 border border-slate-300 rounded-md text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                >
                  Batal
                </Link>
                <button 
                  type="submit" 
                  :disabled="form.processing"
                  class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-tr-xl rounded-bl-xl font-medium text-sm shadow transition duration-150 ease-in-out disabled:opacity-50"
                >
                  {{ form.processing ? 'Menyimpan...' : 'Simpan Berita' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
