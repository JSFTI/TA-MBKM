<script setup lang="ts">
import { pick } from 'lodash';

const props = defineProps<{
  id: number
}>();
const state = reactive<{
  thumbnail_id: number | null
  images: ProductImage[]
}>({
  thumbnail_id: null,
  images: [],
});
const fileHovering = ref(false);

function fileDragging(e: DragEvent) {
  e.preventDefault();
}

function fileEnter(e: DragEvent) {
  e.preventDefault();
  if ((e.dataTransfer?.items.length ?? 0) === 0)
    return;

  fileHovering.value = true;
}

function fileLeave(e: DragEvent) {
  e.preventDefault();

  if ((e.target as HTMLElement).id !== 'image-bag')
    return;

  fileHovering.value = false;
}

function fileDrop(e: DragEvent) {
  e.preventDefault();
  fileHovering.value = false;
  if (e.dataTransfer?.files.length)
    addImages(e.dataTransfer.files);
}

function handleChange(e: Event) {
  const target = e.target as HTMLInputElement;

  if (target.files) {
    addImages(target.files);
    target.value = '';
  }
}

function addImages(images: FileList) {
  const formData = new FormData();

  for (let i = 0; i < images.length; i++)
    formData.append('images[]', images[i], images[i].name);

  axios.post<ProductImage[]>(`products/${props.id}/images`, formData)
    .then((res) => {
      state.images.push(...res.data);
    });
}

function handleDelete(imageId: number) {
  axios.delete(`/product-images/${imageId}`)
    .then(() => {
      state.images = state.images.filter(x => x.id !== imageId);
    });
}

function handleReorder(newImages: ProductImage[]) {
  state.images = newImages.map((x, i) => ({
    ...x,
    priority: i + 1,
  }));

  axios.patch(`/products/${props.id}/images`, state.images);
}

function handleSetAsThumbnail(imageId: number | null) {
  axios.put(`/products/${props.id}/thumbnail`, {
    thumbnail_id: imageId,
  }).then(() => {
    state.thumbnail_id = imageId;
  });
}

axios.get<Product>(`/products/${props.id}`, {
  params: {
    select: 'id,thumbnail_id',
    relations: ['images'],
  },
}).then((res) => {
  Object.assign(state, pick(res.data, ['thumbnail_id', 'images']));
});
</script>

<template>
  <VCard
    id="image-bag" :class="{ 'is-file-hovering': fileHovering }"
    @dragover="fileDragging"
    @dragenter="fileEnter"
    @dragleave="fileLeave"
    @drop="fileDrop"
  >
    <div
      class="pointer-events-none absolute z-100 top-0 left-0 w-full h-full bg-light bg-opacity-50 items-center justify-center flex flex-col opacity-0 transition"
      :class="{
        '!opacity-100': fileHovering,
      }"
    >
      <div class="i-mdi:upload text-7xl text-dark" />
      <div class="select-none text-dark font-bold">
        Drop Image
      </div>
    </div>
    <VToolbar title="Product Images" color="rgba(0,0,0,0)">
      <VToolbarItems>
        <VBtn icon="i-mdi:plus" tag="label" class="!bg-success !text-white" for="images" />
        <input
          id="images" type="file" class="w-0 h-0 invisible m-0 p-0"
          multiple accept="image/*" @change="handleChange"
        />
      </VToolbarItems>
    </VToolbar>
    <VCardText>
      <Draggable
        v-if="state.images.length > 0" :model-value="state.images"
        class="flex gap-5 flex-wrap"
        @update:model-value="handleReorder"
      >
        <template #item="{ element }">
          <VCard
            class="rounded-0 !w-60 !flex items-center gap-5 flex-col" flat
            :class="element.id === state.thumbnail_id ? 'border-2 border-gray' : ''"
          >
            <img draggable="false" class="object-cover !w-60 aspect-ratio-16/9" :src="element.url" />
            <VCardActions class="!py-0 flex gap-5">
              <VBtn
                :icon="element.id === state.thumbnail_id ? 'i-mdi:image-off' : 'i-mdi:file-image-box'"
                size="small" color="primary" @click="handleSetAsThumbnail(element.id === state.thumbnail_id ? null : element.id!)"
              />
              <VBtn icon="i-mdi:trash-can" size="small" class="text-danger" @click="handleDelete(element.id!)" />
            </VCardActions>
          </VCard>
        </template>
      </Draggable>
      <div v-else-if="state.images.length === 0" class="text-3xl text-center py-5 font-bold">
        No Images Found
      </div>
    </VCardText>
  </VCard>
</template>
