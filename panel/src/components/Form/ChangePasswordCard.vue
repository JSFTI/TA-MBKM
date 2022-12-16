<script setup lang="ts">
import type { AxiosError } from 'axios';

const form = reactive({
  oldPassword: '',
  newPassword: '',
  repeatPassword: '',
});
const shows = reactive({
  oldPassword: false,
  newPassword: false,
  repeatPassword: false,
});
const initialInvalids = {
  oldPassword: '',
  newPassword: '',
  repeatPassword: '',
};

const toast = useToast();
const invalidFeedbacks = reactive({ ...initialInvalids });
const loading = ref(false);

function handleSubmit() {
  loading.value = true;
  Object.assign(invalidFeedbacks, initialInvalids);
  axios.post('profile/password', form)
    .then(() => {
      toast.success('Password updated.');
      Object.assign(form, initialInvalids);
    })
    .catch((res: AxiosError<ApiInvalidFeedback>) => {
      if (res.response?.status === 422)
        return Object.assign(invalidFeedbacks, res.response!.data.errors);

      toast.error(res.message);
    })
    .finally(() => {
      loading.value = false;
    });
}
</script>

<template>
  <VCard title="Change Password" class="!p-5">
    <VForm @submit.prevent="handleSubmit">
      <div class="flex flex-col gap-4">
        <VTextField
          id="old-password"
          v-model="form.oldPassword"
          :type="shows.oldPassword ? 'text' : 'password'"
          label="Old Password"
          :append-icon="shows.oldPassword ? 'i-mdi:eye' : 'i-mdi:eye-off'"
          :error="!!invalidFeedbacks.oldPassword"
          :error-messages="invalidFeedbacks.oldPassword"
          @click:append="(shows.oldPassword = !shows.oldPassword)"
        />
        <VTextField
          id="new-password"
          v-model="form.newPassword"
          autocomplete="new-password"
          :type="shows.newPassword ? 'text' : 'password'"
          label="New Password"
          :append-icon="shows.newPassword ? 'i-mdi:eye' : 'i-mdi:eye-off'"
          :error="!!invalidFeedbacks.newPassword"
          :error-messages="invalidFeedbacks.newPassword"
          @click:append="(shows.newPassword = !shows.newPassword)"
        />
        <VTextField
          id="confirm-password"
          v-model="form.repeatPassword"
          autocomplete="confirm-password"
          :type="shows.repeatPassword ? 'text' : 'password'"
          label="Repeat Password"
          :append-icon="shows.repeatPassword ? 'i-mdi:eye' : 'i-mdi:eye-off'"
          :error="!!invalidFeedbacks.repeatPassword"
          :error-messages="invalidFeedbacks.repeatPassword"
          @click:append="(shows.repeatPassword = !shows.repeatPassword)"
        />
      </div>
      <VBtn
        class="!bg-danger text-white dark:text-dark mt-4"
        type="submit" :loading="loading"
      >
        Change Password
      </VBtn>
    </VForm>
  </VCard>
</template>
