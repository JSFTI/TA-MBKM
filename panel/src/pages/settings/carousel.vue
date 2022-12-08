<script setup lang="ts">
import { sortBy } from 'lodash';
import dayjs from 'dayjs';

const toast = useToast();
const approvedCarousels = ref<Carousel[]>([]);
const carousels = ref<Carousel[]>([]);
const fileHovering = ref(false);
const isDirty = ref(false);

function fileDragging(e: DragEvent) {
  e.preventDefault();
}

function fileEnter(e: DragEvent) {
  e.preventDefault();
  fileHovering.value = true;
}

function fileExit(e: DragEvent) {
  e.preventDefault();
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

  axios.post<Carousel[]>('carousels', formData)
    .then((res) => {
      carousels.value.push(...res.data);
      carousels.value = sortBy(carousels.value, [x => x.filename?.toLowerCase()]);
    });
}

function handleApprove(carousel: Carousel) {
  axios.put<Carousel>(`/carousels/${carousel.id}/approved`)
    .then((res) => {
      Object.assign(carousel, res.data);

      approvedCarousels.value.push(carousel);
      carousels.value = carousels.value.filter(x => !x.approved);
    });
}

function handleDisapprove(carousel: Carousel) {
  axios.delete<Carousel>(`/carousels/${carousel.id}/approved`)
    .then((res) => {
      Object.assign(carousel, res.data);

      approvedCarousels.value = approvedCarousels.value.filter(x => x.id !== carousel.id);
      carousels.value.push(carousel);
      carousels.value = carousels.value.filter(x => !x.approved);
    });
}

function handleSaveOrder() {
  const data = approvedCarousels.value.map((x, i) => {
    x.priority = i + 1;
    return x;
  })

  axios.patch('carousels/approved', data).then(() => {
    approvedCarousels.value = data;
    toast.success('Carousel order updated.');
  });
}

function handleDelete(id: number) {
  axios.delete(`/carousels/${id}`)
    .then(() => {
      approvedCarousels.value = approvedCarousels.value.filter(x => x.id !== id);
      carousels.value = carousels.value.filter(x => x.id !== id);
    });
}

axios.get<Carousel[]>('carousels')
  .then((res) => {
    carousels.value = res.data;
    approvedCarousels.value = carousels.value
      .filter(x => x.approved)
      .map(x => ({ ...x, priority: x.priority ?? Number.MAX_SAFE_INTEGER }))
      .sort((a, b) => (a.priority - b.priority) || dayjs(a.approved_at).diff(dayjs(b.approved_at)));
  });

const nonApprovedCarousels = computed(() => {
  return sortBy(carousels.value.filter(x => !x.approved), [x => x.filename?.toLowerCase()]);
});
</script>

<template>
  <VCard title="Carousel Setting">
    <div class="p-5">
      <Draggable
        v-model="approvedCarousels" item-key="id"
        class="flex gap-5 flex-wrap justify-center"
        @change="(isDirty = true)"
      >
        <template #item="{ element }">
          <VCard
            class="cursor-pointer rounded-0 !max-w-80 flex items-center gap-5 flex-col" flat
          >
            <VImg cover class="!max-w-80 !h-35 aspect-ratio-16/9" :src="element.url" />
            <VCardText class="py-0">
              {{ element.filename }}
            </VCardText>
            <VCardActions class="py-0 flex gap-5">
              <VBtn icon="i-mdi:cancel" size="small" class="!bg-danger !text-black" @click="handleDisapprove(element)" />
              <VBtn icon="i-mdi:trash-can" size="small" class="!bg-danger !text-black" @click="handleDelete(element.id)" />
            </VCardActions>
          </VCard>
        </template>
      </Draggable>
      <div class="text-center">
        <VBtn color="primary" @click="handleSaveOrder">
          Update Carousel Order
        </VBtn>
      </div>
      <VCard
        class="mt-10"
        @dragover="fileDragging"
        @dragenter="fileEnter"
        @dragexit="fileExit"
        @drop="fileDrop"
      >
        <VOverlay :model-value="fileHovering" persistent contained class="items-center justify-center">
          <div class="i-mdi:upload text-7xl" />
          <div class="select-none">
            Drop Carousel
          </div>
        </VOverlay>
        <VToolbar title="Carousels" color="rgba(0,0,0,0)">
          <VBtn color="primary" />
          <template #append>
            <VBtn icon="i-ic:round-add" class="!bg-success !text-white" tag="label" for="carousel" />
            <input
              id="carousel" type="file" class="w-0 h-0 invisible m-0 p-0"
              multiple @change="handleChange"
            />
          </template>
        </VToolbar>
        <VCardText>
          <div class="flex gap-5 flex-wrap">
            <VCard
              v-for="carousel in nonApprovedCarousels" :key="carousel.id"
              class="cursor-pointer rounded-0 !w-60 flex items-center gap-5 flex-col" flat
            >
              <img draggable="false" class="object-cover !w-60 aspect-ratio-16/9" :src="carousel.url" />
              <VCardText class="!py-0">
                {{ carousel.filename }}
              </VCardText>
              <VCardActions class="!py-0 flex gap-5">
                <VBtn icon="i-mdi:check" size="small" class="!bg-success !text-black" @click="handleApprove(carousel)" />
                <VBtn icon="i-mdi:trash-can" size="small" class="!bg-danger !text-black" @click="handleDelete(carousel.id!)" />
              </VCardActions>
            </VCard>
          </div>
        </VCardText>
      </VCard>
    </div>
  </VCard>
</template>
