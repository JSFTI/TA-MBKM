<script setup lang="ts">
import axios from 'axios';
import { pick } from 'lodash';
import { baseUrl } from '~/helpers';
import { useUser } from '~/stores/useUser';

const profile = reactive({
  name: '',
  email: '',
});

const user = useUser();
const picture = ref<null | string | File>(null);
const originalProfile = ref<UserSession | null>(null);
const loading = reactive({
  form: false,
  picture: false,
});

axios.get<UserSession>('profile')
  .then((res) => {
    Object.assign(profile, pick(res.data, ['name', 'email']));
    picture.value = res.data.profile_picture ? baseUrl(`api/users/${res.data.id}/profile-picture`) : null;
    originalProfile.value = res.data;
  });

function handleSaveImage() {
  const formData = new FormData();
  if (!picture.value || !(picture.value instanceof File))
    return;

  formData.append('image', picture.value, picture.value.name);
  axios.post(`users/${originalProfile.value?.id}/profile-picture`, formData)
    .then((res) => {
      if (originalProfile.value) {
        originalProfile.value.profile_picture = res.data.profile_picture;
        picture.value = baseUrl(`api/users/${originalProfile.value.id}/profile-picture`);
        user.profile_picture = res.data.profile_picture;
      }
    });
}

function handleDeleteImage() {
  axios.delete(`users/${originalProfile.value?.id}/profile-picture`)
    .then(() => {
      if (originalProfile.value) {
        originalProfile.value.profile_picture = null;
        picture.value = null;
        user.profile_picture = 'null';
      }
    });
}

function handleSubmitForm() {
  loading.form = true;
  axios.patch(`users/${originalProfile.value?.id}`, profile)
    .then((res) => {
      user.name = res.data.name;
    });
}

const imageDirty = computed(() => {
  return picture.value instanceof File;
});
</script>

<template>
  <div>
    <h1 class="text-3xl text-themeable">
      Profile
    </h1>
    <VDivider class="my-4" />
    <VCard class="p-5">
      <div v-if="originalProfile" class="flex flex-col md:flex-row gap-10">
        <div class="flex-grow-1 flex items-center flex-col">
          <ProfilePictureUpdater v-model:value="picture">
            <template #extra>
              <template v-if="imageDirty">
                <VBtn color="primary" icon="i-ic:baseline-save" @click="handleSaveImage" />
                <VBtn class="bg-danger" icon="i-ic:baseline-cancel" @click="picture = originalProfile?.profile_picture === null ? null : baseUrl(`api/users/${originalProfile!.id}/profile-picture`)" />
              </template>
              <template v-else-if="(!imageDirty && picture)">
                <VBtn class="!bg-danger" icon="i-mdi:trash-can" @click="handleDeleteImage" />
              </template>
            </template>
          </ProfilePictureUpdater>
        </div>
        <VDivider class="hidden md:block" vertical />
        <VDivider class="block md:hidden" />
        <VForm class="flex-grow-2 lg:flex-grow-1" @submit.prevent="handleSubmitForm">
          <VTextField v-model="profile.name" label="Name">
          </VTextField>
          <VTextField v-model="profile.email" label="Email">
          </VTextField>
          <VBtn type="submit" color="primary">
            Submit
          </VBtn>
        </VForm>
      </div>
    </VCard>
  </div>
</template>
