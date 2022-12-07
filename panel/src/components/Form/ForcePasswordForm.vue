<script setup lang="ts">
import type { AxiosError } from 'axios';

const props = defineProps<{
  id: number
}>();

const form = reactive({
  password: '',
  repeatPassword: '',
});
const shows = reactive({
  password: false,
  repeatPassword: false,
});
const initialInvalids = {
  password: '',
  repeatPassword: '',
};

const toast = useToast();
const invalidFeedbacks = reactive({ ...initialInvalids });
const loading = ref(false);

function handleSubmit() {
  loading.value = true;
  Object.assign(invalidFeedbacks, initialInvalids);
  axios.post(`users/${props.id}/password`, form)
    .then(() => {
      toast.success('Password updated.');
      Object.assign(form, initialInvalids);
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
  <VCard title="Force Change Password">
    <VForm class="p-5" @submit.prevent="handleSubmit">
      <VTextField
        id="new-password"
        v-model="form.password"
        :type="shows.password ? 'text' : 'password'"
        label="Password"
        :append-icon="shows.password ? 'i-mdi:eye' : 'i-mdi:eye-off'"
        :error="!!invalidFeedbacks.password"
        :error-messages="invalidFeedbacks.password"
        @click:append="(shows.password = !shows.password)"
      />
      <VTextField
        id="repeat-password"
        v-model="form.repeatPassword"
        :type="shows.repeatPassword ? 'text' : 'password'"
        label="Repeat Password"
        :append-icon="shows.repeatPassword ? 'i-mdi:eye' : 'i-mdi:eye-off'"
        :error="!!invalidFeedbacks.repeatPassword"
        :error-messages="invalidFeedbacks.repeatPassword"
        @click:append="(shows.repeatPassword = !shows.repeatPassword)"
      />
      <VBtn class="mt-5" color="danger" type="submit">
        Submit
      </VBtn>
    </VForm>
  </VCard>
</template>
