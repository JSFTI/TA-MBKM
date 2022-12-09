<script setup lang="ts">
import { useCurrencyInput } from 'vue-currency-input';
import type { CurrencyInputOptions } from 'vue-currency-input';
import type { VInput } from 'vuetify/components';

const props = defineProps<InstanceType<typeof VInput>['$props'] & { config?: CurrencyInputOptions }>();

const { inputRef } = useCurrencyInput(props.config ?? {
  currency: 'IDR',
  precision: 2,
});
</script>

<template>
  <VTextField :ref="(el) => inputRef = el" inputmode="numeric" />
</template>
