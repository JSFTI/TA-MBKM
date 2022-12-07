<script setup lang="ts">
const props = defineProps<{
  id: number
}>();

const emit = defineEmits<{
  (event: 'submitted', value: string | null): void
}>();

const picture = ref<File | null>(null);
const original = ref<File | null>(null);

axios.get(`users/${props.id}/profile-picture`, { responseType: 'blob' })
  .then((res) => {
    picture.value = original.value = new File([res.data], 'old-file');
  });

function handleSaveImage(file: File | null) {
  if (file === null) {
    axios.delete(`users/${props.id}/profile-picture`)
      .then(() => {
        picture.value = null;
        emit('submitted', null);
      });
    return;
  }

  const formData = new FormData();
  formData.append('image', file, file.name);
  axios.post(`users/${props.id}/profile-picture`, formData)
    .then((res) => {
      picture.value = file;
      emit('submitted', res.data.profile_picture);
    });
}
</script>

<template>
  <div class="flex-grow-1 flex items-center flex-col">
    <ProfilePictureUpdater :value="picture" @update:value="handleSaveImage" />
  </div>
</template>
