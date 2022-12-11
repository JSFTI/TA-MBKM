<script setup lang="ts">
defineProps<{
  productId: string
}>();
</script>

<template>
  <VCard title="Edit Product">
    <VCardText>
      <ProductDetailForm :id="+productId" />
    </VCardText>
  </VCard>
  <ProductImages :id="+productId" class="mt-5" />
</template>
