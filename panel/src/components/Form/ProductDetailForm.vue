<script setup lang="ts">
withDefaults(defineProps<{
  id?: number | null
}>(), {
  id: null,
});

const initForm = {
  name: '',
  category_id: null,
  price: 0,
  stock: null,
  published: false,
};

const initInvalidFeedbacks = {
  name: '',
  category_id: '',
  price: '',
  stock: '',
  published: '',
};

const form = reactive({ ...initForm });
const invalidFeedbacks = reactive({ ...initInvalidFeedbacks });
</script>

<template>
  <VForm>
    <div class="flex flex-col gap-5">
      <VTextField v-model="form.name" label="Product Name" />
      <VTextField v-model.number="form.price" label="Price" />
    </div>
  </VForm>
</template>
