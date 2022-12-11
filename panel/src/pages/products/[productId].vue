<script setup lang="ts">
// import { pick } from 'lodash';

defineProps<{
  productId: string
}>();

// const product = ref<Product | null>(null);

// axios.get<Product>(`/products/${props.productId}`, {
//   params: {
//     relations: ['tags', 'category', 'thumbnail', 'images'],
//   },
// }).then((res) => {
//   product.value = pick(res.data, ['id', 'name', 'price', 'detail', 'stock', 'published']);
// });
</script>

<template>
  <VCard title="Edit Product">
    <VCardText>
      <ProductDetailForm :id="+productId" />
    </VCardText>
  </VCard>
</template>
