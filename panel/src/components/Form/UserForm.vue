<script setup lang="ts">
import { AxiosError } from 'axios';
import { pick } from 'lodash';

const props = defineProps<{
  id: number | null
}>();

const emit = defineEmits<{
  (event: 'submitted', data: User): void
}>();

const roles = ref<Role[]>([]);
const loading = ref(false);
const submitting = ref(false);

const defaultValues = {
  name: '',
  email: '',
  role_id: null,
};

const initInvalidFeedbacks = {
  name: '',
  email: '',
  role_id: '',
};

const form = reactive({ ...defaultValues });
const invalidFeedbacks = reactive({ ...initInvalidFeedbacks });

function getData(id: number) {
  loading.value = true;
  Object.assign(form, defaultValues);
  axios.get<User>(`users/${id}`)
    .then((res) => {
      Object.assign(form, pick(res.data, ['name', 'email', 'role_id']))
    })
    .finally(() => {
      loading.value = false;
    });
}

async function handleSubmit() {
  submitting.value = true;
  Object.assign(invalidFeedbacks, initInvalidFeedbacks);
  try {
    let user: User;
    if (props.id) {
      user = await axios.put<User>(`/users/${props.id}`, form)
        .then(res => res.data);
    } else {
      user = await axios.post<User>('/users', form)
        .then(res => res.data);
    }

    emit('submitted', user);
  } catch (e: unknown) {
    if (e instanceof AxiosError<ApiInvalidFeedback>)
      Object.assign(invalidFeedbacks, e.response!.data.errors);
  } finally {
    submitting.value = false;
  }
}

axios.get<Role[]>('roles')
  .then((res) => {
    roles.value = res.data;
  });

if (props.id)
  getData(props.id);
</script>

<template>
  <VForm @submit.prevent="handleSubmit">
    <VOverlay :model-value="loading" persistent contained class="align-center justify-center">
      <div class="flex justify-center">
        <VProgressCircular size="64" color="primary" indeterminate />
      </div>
    </VOverlay>
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
    </div>
    <div>
      <VBtn class="mt-5" color="success" :loading="submitting" type="submit">
        Submit
      </VBtn>
    </div>
  </VForm>
</template>
