<script setup lang="ts">
import type { AxiosError } from 'axios';

const emit = defineEmits<{
  (event: 'submitted', user: User): void
}>();

const defaultValues = {
  name: '',
  email: '',
  role_id: null,
  password: '',
  repeatPassword: '',
};

const initInvalidFeedbacks = {
  name: '',
  email: '',
  role_id: '',
  password: '',
  repeatPassword: '',
};

const loading = ref(false);
const form = reactive({ ...defaultValues });
const invalidFeedbacks = reactive({ ...initInvalidFeedbacks });
const shows = reactive({
  password: false,
  repeatPassword: false,
});
const roles = ref<Role[]>([]);

function handleSubmit() {
  loading.value = true;
  Object.assign(invalidFeedbacks, initInvalidFeedbacks);
  axios.post<User>('users', form)
    .then((res) => {
      emit('submitted', res.data);
    })
    .catch((res: AxiosError<ApiInvalidFeedback>) => {
      Object.assign(invalidFeedbacks, res.response!.data.errors);
    })
    .finally(() => {
      loading.value = false;
    });
}

axios.get<Role[]>('roles')
  .then((res) => {
    roles.value = res.data;
  });
</script>

<template>
  <VForm class="p-5" @submit.prevent="handleSubmit">
    <div class="flex flex-col gap-5">
      <VTextField
        v-model="form.name" label="Name"
        :error="!!invalidFeedbacks.name"
        :error-messages="invalidFeedbacks.name"
      />
      <VTextField
        v-model="form.email" label="Email"
        :error="!!invalidFeedbacks.email"
        :error-messages="invalidFeedbacks.email"
      />
      <VSelect
        v-model="form.role_id"
        :items="roles"
        item-title="display_name"
        item-value="id"
        label="Role"
        :error="!!invalidFeedbacks.role_id"
        :error-messages="invalidFeedbacks.role_id"
      />
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
    </div>
    <VBtn type="submit" class="mt-5" color="success">
      Submit
    </VBtn>
  </VForm>
</template>
