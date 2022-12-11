<script setup lang="ts">
import type { AxiosError } from 'axios';

const props = defineProps<{
  productId: string
}>();

const router = useRouter();
const toast = useToast();

function handleDelete() {
  axios.delete(`/products/${props.productId}`)
    .then(() => {
      router.push('/products');
    })
    .catch((res: AxiosError<ApiInvalidFeedback>) => {
      toast.error(res.response!.data.message!);
    });
}
</script>

<template>
  <VCard title="Edit Product">
    <VCardText>
      <ProductDetailForm :id="+productId" />
    </VCardText>
  </VCard>
  <ProductImages :id="+productId" class="mt-5" />
  <div class="mt-5 text-center">
    <VBtn color="danger" @click="handleDelete">
      DELETE
    </VBtn>
  </div>
</template>
