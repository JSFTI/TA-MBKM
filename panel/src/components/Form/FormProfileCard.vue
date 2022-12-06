<script setup lang="ts">
import type { AxiosError } from 'axios';
import { pick } from 'lodash';
import { baseUrl } from '~/helpers';
import { useUser } from '~/stores/useUser';

const initialInvalidForm = {
  name: '',
  email: '',
}

const profile = reactive({
  name: '',
  email: '',
});

const toast = useToast();
const user = useUser();
const picture = ref<null | string | File>(null);
const originalProfile = ref<UserSession | null>(null);
const invalidFeedbacks = reactive({
  image: null,
  form: { ...initialInvalidForm },
});
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
  setTimeout(() => {
    Object.assign(invalidFeedbacks.form, initialInvalidForm);
    axios.patch(`users/${originalProfile.value?.id}`, profile)
      .then((res) => {
        user.name = res.data.name;
        toast.success('Profile updated');
      })
      .catch((res: AxiosError<ApiInvalidFeedback>) => {
        Object.assign(invalidFeedbacks.form, res.response!.data.errors);
      })
      .finally(() => {
        loading.form = false;
      });
  }, 10000);
}

const imageDirty = computed(() => {
  return picture.value instanceof File;
});
</script>

<template>
  <VCard class="p-5" title="Profile">
    <div v-if="originalProfile" class="flex flex-col md:flex-row gap-10">
      <div class="flex-grow-1 flex items-center flex-col">
        <ProfilePictureUpdater v-model:value="picture">
          <template #extra>
            <template v-if="imageDirty">
              <VBtn color="primary" icon="i-ic:baseline-save" @click="handleSaveImage" />
              <VBtn color="danger" class="text-white dark:text-dark" icon="i-ic:baseline-cancel" @click="picture = originalProfile?.profile_picture === null ? null : baseUrl(`api/users/${originalProfile!.id}/profile-picture`)" />
            </template>
            <template v-else-if="(!imageDirty && picture)">
              <VBtn color="danger" class="text-white dark:text-dark" icon="i-mdi:trash-can" @click="handleDeleteImage" />
            </template>
          </template>
        </ProfilePictureUpdater>
      </div>
      <VDivider class="hidden md:block" vertical />
      <VDivider class="block md:hidden" />
      <VForm class="flex-grow-2 lg:flex-grow-1" @submit.prevent="handleSubmitForm">
        <div class="flex flex-col gap-3">
          <VTextField
            v-model="profile.name" label="Name"
            :error="!!invalidFeedbacks.form.name"
            :error-messages="invalidFeedbacks.form.name"
          />
          <VTextField
            v-model="profile.email" label="Email"
            :error="!!invalidFeedbacks.form.email"
            :error-messages="invalidFeedbacks.form.email"
          />
        </div>
        <VBtn class="mt-4" type="submit" color="primary" :loading="loading.form">
          Submit
        </VBtn>
      </VForm>
    </div>
  </VCard>
</template>
