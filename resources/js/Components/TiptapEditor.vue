<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { watch, onBeforeUnmount } from 'vue'

const props = defineProps({
  modelValue: { type: String, default: '' },
})

const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  content: props.modelValue,
  extensions: [StarterKit],
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
  editorProps: {
    attributes: {
      class: 'prose prose-sm max-w-none focus:outline-none border rounded-md p-4 min-h-[250px] bg-white text-slate-800 border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600',
    },
  },
})

watch(() => props.modelValue, (value) => {
  if (editor.value && editor.value.getHTML() !== value) {
    editor.value.commands.setContent(value, false)
  }
})

onBeforeUnmount(() => {
  if (editor.value) editor.value.destroy()
})
</script>

<template>
  <div class="tiptap-editor-container border border-slate-300 rounded-md overflow-hidden bg-slate-50">
    <div class="flex flex-wrap items-center gap-1 border-b border-slate-300 p-2 bg-slate-100">
      <button 
        type="button" 
        @click="editor.chain().focus().toggleBold().run()" 
        :class="{ 'bg-emerald-600 text-white': editor?.isActive('bold'), 'hover:bg-slate-200 text-slate-700': !editor?.isActive('bold') }" 
        class="px-2 py-1 rounded text-xs font-semibold transition"
      >
        Bold
      </button>
      <button 
        type="button" 
        @click="editor.chain().focus().toggleItalic().run()" 
        :class="{ 'bg-emerald-600 text-white': editor?.isActive('italic'), 'hover:bg-slate-200 text-slate-700': !editor?.isActive('italic') }" 
        class="px-2 py-1 rounded text-xs font-semibold transition"
      >
        Italic
      </button>
      <button 
        type="button" 
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" 
        :class="{ 'bg-emerald-600 text-white': editor?.isActive('heading', { level: 2 }), 'hover:bg-slate-200 text-slate-700': !editor?.isActive('heading', { level: 2 }) }" 
        class="px-2 py-1 rounded text-xs font-semibold transition"
      >
        H2
      </button>
      <button 
        type="button" 
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" 
        :class="{ 'bg-emerald-600 text-white': editor?.isActive('heading', { level: 3 }), 'hover:bg-slate-200 text-slate-700': !editor?.isActive('heading', { level: 3 }) }" 
        class="px-2 py-1 rounded text-xs font-semibold transition"
      >
        H3
      </button>
      <button 
        type="button" 
        @click="editor.chain().focus().toggleBulletList().run()" 
        :class="{ 'bg-emerald-600 text-white': editor?.isActive('bulletList'), 'hover:bg-slate-200 text-slate-700': !editor?.isActive('bulletList') }" 
        class="px-2 py-1 rounded text-xs font-semibold transition"
      >
        Bullet List
      </button>
      <button 
        type="button" 
        @click="editor.chain().focus().toggleOrderedList().run()" 
        :class="{ 'bg-emerald-600 text-white': editor?.isActive('orderedList'), 'hover:bg-slate-200 text-slate-700': !editor?.isActive('orderedList') }" 
        class="px-2 py-1 rounded text-xs font-semibold transition"
      >
        Numbered List
      </button>
    </div>
    <EditorContent :editor="editor" class="bg-white" />
  </div>
</template>

<style>
.prose ul {
  list-style-type: disc;
  padding-left: 1.25rem;
}
.prose ol {
  list-style-type: decimal;
  padding-left: 1.25rem;
}
</style>
