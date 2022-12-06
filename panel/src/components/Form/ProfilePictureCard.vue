<script setup lang="ts">
import { useUser } from '~/stores/useUser';

const user = useUser();
const picture = ref<File | null>(null);
const original = ref<File | null>(null);

axios.get(`users/${user.id}/profile-picture`, { responseType: 'blob' })
  .then((res) => {
    picture.value = original.value = new File([res.data], 'old-file');
  });

function handleSaveImage(file: File | null) {
  if (file === null) {
    axios.delete(`users/${user.id}/profile-picture`)
      .then(() => {
        picture.value = null;
        user.profile_picture = 'null';
      });
    return;
  }

  const formData = new FormData();
  formData.append('image', file, file.name);
  axios.post(`users/${user.id}/profile-picture`, formData)
    .then((res) => {
      user.profile_picture = res.data.profile_picture;
      picture.value = file;
    });
}
</script>

<template>
  <VCard class="p-5" title="Profile Picture">
    <div class="flex-grow-1 flex items-center flex-col">
      <ProfilePictureUpdater :value="picture" @update:value="handleSaveImage" />
    </div>
  </VCard>
</template>
