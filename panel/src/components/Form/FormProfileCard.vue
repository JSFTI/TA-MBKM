<script setup lang="ts">
import type { AxiosError } from 'axios';
import { pick } from 'lodash';
import { useUser } from '~/stores/useUser';

const initInvalidFeedbacks = {
  name: '',
  email: '',
}

const user = useUser();
const toast = useToast();
const profile = reactive({
  id: '',
  name: '',
  email: '',
});
const invalidFeedbacks = reactive({ ...initInvalidFeedbacks });
const loading = ref(false);

axios.get<User>('profile')
  .then((res) => {
    Object.assign(profile, pick(res.data, ['id', 'name', 'email']));
  });

function handleSubmitForm() {
  loading.value = true;
  Object.assign(invalidFeedbacks, initInvalidFeedbacks);
  axios.patch(`users/${profile.id}`, profile)
    .then((res) => {
      user.name = res.data.name;
      toast.success('Profile updated');
    })
    .catch((res: AxiosError<ApiInvalidFeedback>) => {
      Object.assign(invalidFeedbacks, res.response!.data.errors);
    })
    .finally(() => {
      loading.value = false;
    });
}
</script>

<template>
  <VCard class="!p-5" title="Profile">
    <VForm class="flex-grow-2 lg:flex-grow-1" @submit.prevent="handleSubmitForm">
      <div class="flex flex-col gap-3">
        <VTextField
          v-model="profile.name" label="Name"
          :error="!!invalidFeedbacks.name"
          :error-messages="invalidFeedbacks.name"
        />
        <VTextField
          v-model="profile.email" label="Email"
          :error="!!invalidFeedbacks.email"
          :error-messages="invalidFeedbacks.email"
        />
      </div>
      <VBtn class="mt-4" type="submit" color="primary" :loading="loading">
        Submit
      </VBtn>
    </VForm>
  </VCard>
</template>
