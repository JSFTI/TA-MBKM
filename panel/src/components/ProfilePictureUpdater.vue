<script setup lang="ts">
import Cropper from 'cropperjs';
import { fileToBase64 } from '~/helpers';

const props = defineProps<{
  id?: string
  value: File | string | null
}>();

const emit = defineEmits<{
  (event: 'update:value', file: File | null): void
}>();

let cropper: Cropper | null;
const imageEl = ref();
const values = reactive<{
  current: null | string
  temp: null | string
  file: null | File
}>({
  current: null,
  temp: null,
  file: null,
});
const id = props.id ?? Math.ceil(Math.random() * 100000).toString();

function confirmCrop() {
  if (cropper) {
    const dataType = values.temp?.match(/[^:]\w+\/[\w-+\d.]+(?=;|,)/)?.[0];

    fetch(cropper.getCroppedCanvas().toDataURL(dataType))
      .then(res => res.blob())
      .then((blob) => {
        if (blob) {
          cropper?.destroy();
          cropper = null;
          values.temp = null;
          emit('update:value', new File([blob], values.file!.name, {
            type: values.file!.type,
          }));
        }
      });
  }
}

function handleCancel() {
  values.temp = null;
  cropper?.destroy();
}

function initCropper() {
  nextTick(() => {
    if (cropper)
      cropper.destroy();

    cropper = new Cropper(imageEl.value!, {
      viewMode: 3,
      dragMode: 'move',
      autoCropArea: 1,
      restore: false,
      modal: false,
      guides: false,
      highlight: false,
      cropBoxMovable: false,
      cropBoxResizable: false,
      toggleDragModeOnDblclick: false,
    });
  });
}

function processImage(image: File | null) {
  if (image === null) {
    values.temp = null;
    initCropper();
    return;
  }

  if (!image.type.startsWith('image/'))
    return;

  const reader = new FileReader();
  reader.onloadend = (e) => {
    if (e.target) {
      values.temp = e.target.result as string;
      values.file = image;
      initCropper();
    }
  }
  reader.readAsDataURL(image);
}

async function handleChange(e: Event) {
  const target = e.target as HTMLInputElement;
  if (target.files?.[0])
    processImage(target.files[0]);
    // value.value = await fileToBase64(target.files[0]);
}

async function processValue(newValue: null | File | string) {
  if (typeof newValue === 'string') {
    values.current = newValue;
    values.file = null;
    return;
  }

  if (newValue instanceof File) {
    values.current = await fileToBase64(newValue);
    values.file = newValue;
    return;
  }

  values.current = null;
}

processValue(props.value);

watch(() => props.value, () => {
  processValue(props.value);
});
</script>

<template>
  <div class="profile-picture-updater">
    <label :for="values.temp ? undefined : id" class="!h-50 !w-50 block">
      <img v-if="value || values.temp" ref="imageEl" :src="(values.temp || values.current || undefined)" alt="Current Profile Picture" class="rounded-full w-full h-full" />
      <div v-else class="bg-gray w-full h-full p-3 rounded-full">
        <div class="i-ic:baseline-person?mask h-full w-full text-white" />
      </div>
    </label>
    <div class="justify-center mt-5 flex gap-5">
      <VBtn v-if="values.temp" color="primary" icon="i-ic:baseline-crop" @click="confirmCrop" />
      <VBtn v-if="values.temp" color="secondary" icon="i-ic:baseline-cancel" @click="handleCancel" />
      <slot v-if="!values.temp" name="extra"></slot>
    </div>
    <input :id="id" type="file" accept="image/*" class="w-0 h-0 invisible" @change="handleChange" />
  </div>
</template>

<style lang="scss">
.profile-picture-updater{
  .cropper-container.cropper-bg,
  .cropper-wrap-box,
  .cropper-view-box{
    @apply rounded-full;
  }
}
</style>
