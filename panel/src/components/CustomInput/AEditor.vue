<script setup lang="ts">
import { EditorContent, useEditor } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder';
import Link from '@tiptap/extension-link';

const props = withDefaults(defineProps<{
  content: string | null
}>(), {
  content: null,
});

const emit = defineEmits<{
  (event: 'update:content', html: string): void
}>();

const editor = useEditor({
  content: props.content,
  extensions: [
    StarterKit,
    Placeholder.configure({
      placeholder: 'Product details',
    }),
    Link.configure({
      openOnClick: false,
    }),
  ],
  onUpdate: (e) => {
    emit('update:content', e.editor.getHTML());
  },
});
const setLinkOpened = ref(false);
const link = ref<string | null>(null);

function setLink() {
  setLinkOpened.value = false;

  if (link.value === null)
    return;

  if (link.value === '') {
    editor.value?.chain()
      .focus()
      .extendMarkRange('link')
      .unsetLink()
      .run();

    return
  }

  editor.value?.chain()
    .focus()
    .extendMarkRange('link')
    .setLink({ href: link.value })
    .run();
}

const headings = [
  {
    label: 'Header 1',
    level: 1,
  },
  {
    label: 'Header 2',
    level: 2,
  },
  {
    label: 'Header 3',
    level: 3,
  },
  {
    label: 'Header 4',
    level: 4,
  },
  {
    label: 'Header 5',
    level: 5,
  },
  {
    label: 'Header 5',
    level: 6,
  },
  {
    label: 'Paragraph',
    level: 0,
  },
];

const heading = computed({
  get() {
    return headings.find(x => editor.value?.isActive('heading', { level: x.level }))?.level ?? 0;
  },
  set(v) {
    if (v === undefined || v === 0) {
      editor.value?.chain().focus().setParagraph().run();
      return;
    }

    if (v >= 1 && v <= 6)
      editor.value?.chain().focus().toggleHeading({ level: v as 1 | 2 | 3 | 4 | 5 | 6 }).run();
  },
})

onBeforeUnmount(() => {
  editor.value?.destroy();
});
</script>

<template>
  <div v-if="editor" class="border-gray rounded border-1">
    <div class="flex flex-wrap items-center mt-4 px-5 pb-2 border-b-1 border-gray">
      <div class="mr-5">
        <VSelect
          v-model="heading"
          :items="headings" item-title="label" item-value="level" variant="plain"
          class="w-40" density="compact" label="Text size" hide-details
        />
      </div>
      <VBtn
        size="small" icon="i-mdi:format-bold"
        :ripple="false" variant="plain"
        :disabled="!editor.can().chain().focus().toggleBold().run()"
        :class="{ 'text-info': editor.isActive('bold') }"
        @click="editor?.chain().focus().toggleBold().run()"
      />
      <VBtn
        size="small" icon="i-mdi:format-italic"
        :ripple="false" variant="plain"
        :disabled="!editor.can().chain().focus().toggleItalic().run()"
        :class="{ 'text-info': editor.isActive('italic') }"
        @click="editor?.chain().focus().toggleItalic().run()"
      />
      <VBtn
        size="small" icon="i-mdi:format-strikethrough"
        :ripple="false" variant="plain"
        :disabled="!editor.can().chain().focus().toggleStrike().run()"
        :class="{ 'text-info': editor.isActive('strike') }"
        @click="editor?.chain().focus().toggleStrike().run()"
      />
      <VBtn
        size="small" icon="i-mdi:format-clear"
        :ripple="false" variant="plain"
        @click="editor?.chain().focus().unsetAllMarks().run()"
      />
      <VDivider vertical class="mx-2" />
      <VBtn
        size="small" icon="i-mdi:format-list-bulleted"
        :ripple="false" variant="plain"
        :class="{ 'text-info': editor.isActive('bulletList') }"
        @click="editor?.chain().focus().toggleBulletList().run()"
      />
      <VBtn
        size="small" icon="i-mdi:format-list-numbered"
        :ripple="false" variant="plain"
        :class="{ 'text-info': editor.isActive('orderedList') }"
        @click="editor?.chain().focus().toggleOrderedList().run()"
      />
      <VDivider vertical class="mx-2" />
      <VMenu v-model="setLinkOpened" :close-on-content-click="false" location="bottom center">
        <template #activator="{ props: p }">
          <VBtn
            size="small" icon="i-mdi:link-plus" v-bind="p"
            :ripple="false" variant="plain"
          />
        </template>
        <VCard min-width="400">
          <VCardText>
            <VTextField
              v-model="link" hint="Insert a link" persistent-hint
              label="Link" placeholder="https://example.com"
            >
              <template #append>
                <VIcon
                  icon="i-mdi:plus" tag="button" class="disabled:(text-gray cursor-default) focus:text-info hover:text-info transition cursor-pointer"
                  :disabled="!link" @click="setLink"
                />
              </template>
            </VTextField>
          </VCardText>
        </VCard>
      </VMenu>
      <VBtn
        size="small" icon="i-mdi:link-off"
        :ripple="false" variant="plain"
        :disabled="!editor.isActive('link')"
        @click="editor?.chain().focus().unsetLink().run()"
      />
    </div>
    <EditorContent class="a-editor-content" :editor="editor" />
  </div>
</template>

<style scoped lang="scss">
:deep(*){
  p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd;
    pointer-events: none;
    height: 0;
  }
}

.a-editor-content :deep(.ProseMirror){
  @apply px-2 py-5;

  ul, ol{
    @apply pl-5
  }
}
</style>
