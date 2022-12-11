<script setup lang="ts">
import { pick } from 'lodash';

const props = defineProps<{
  id: number
}>();
const state = reactive<{
  thumbnail: ProductImage | null
  images: ProductImage[]
}>({
  thumbnail: null,
  images: [],
});
const fileHovering = ref(false);

function fileDragging(e: DragEvent) {
  e.preventDefault();
}

function fileEnter(e: DragEvent) {
  e.preventDefault();
  fileHovering.value = true;
}

function fileExit(e: DragEvent) {
  e.preventDefault();

  if ((e.target as HTMLElement).id !== 'image-bag')
    return;

  fileHovering.value = false;
}

function fileDrop(e: DragEvent) {
  e.preventDefault();
  fileHovering.value = false;
  if (e.dataTransfer)
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

axios.get<Product>(`/products/${props.id}`, {
  params: {
    select: 'id',
    relations: ['thumbnail', 'images'],
  },
}).then((res) => {
  Object.assign(state, pick(res.data, ['thumbnail', 'images']));
});
</script>

<template>
  <VCard
    id="image-bag"
    @dragover="fileDragging"
    @dragenter="fileEnter"
    @dragleave="fileExit"
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
      <div v-if="state.images.length > 0">
        {{ state.images }}
      </div>
      <div v-else class="text-3xl text-center py-5 font-bold">
        No Images Found
      </div>
    </VCardText>
  </VCard>
</template>
