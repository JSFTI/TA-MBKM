<script setup lang="ts">
import { cloneDeep, isEmpty, isEqual } from 'lodash';

interface Filter {
  categories?: string[]
  tags?: string[]
  price?: {
    gte?: string
    lte?: string
  }
  stock?: {
    gte?: string
    lte?: string
  } | 'infinite' | '0'
  published: 1 | 0 | null
}

const props = defineProps<{
  filters: Filter
}>();

const emit = defineEmits<{
  (event: 'update:filters', filter: Filter): void
}>();

const filters = reactive(cloneDeep(props.filters));
const selectableCategories = ref<Category[]>([]);
const selectableTags = ref<Tag[]>([]);

const searchTags = useDebounceFn((v: string) => {
  if (v.length === 0) {
    selectableTags.value = [];
    return;
  }
  axios.get<Tag[]>('tags', {
    params: {
      search: v,
      limit: 10,
    },
  }).then((res) => {
    selectableTags.value = res.data.filter(x => !filters.tags?.includes(x.name!));
  });
}, 200);

function handleReset() {
  Object.assign(filters, {
    categories: [],
    tags: [],
    price: undefined,
    stock: undefined,
    published: null,
  });

  emit('update:filters', filters);
}

function handleSubmit() {
  emit('update:filters', filters);
}

axios.get('categories')
  .then((res) => {
    selectableCategories.value = res.data;
  });

const priceMin = computed({
  get() {
    return filters.price?.gte ?? null
  },
  set(v) {
    filters.price = {
      ...filters.price,
      gte: v ?? undefined,
    }
  },
});

const priceMax = computed({
  get() {
    return filters.price?.lte ?? null
  },
  set(v) {
    filters.price = {
      ...filters.price,
      lte: v ?? undefined,
    }
  },
});

const stockMin = computed({
  get() {
    if (typeof filters.stock === 'string')
      return null;
    return filters.stock?.gte ?? null
  },
  set(v) {
    if (v == null)
      return;

    filters.stock = {
      ...(typeof filters.stock === 'object' ? filters.stock : {}),
      gte: v ?? undefined,
    }
  },
});

const stockMax = computed({
  get() {
    if (typeof filters.stock === 'string')
      return null;
    return filters.stock?.lte ?? null
  },
  set(v) {
    if (v == null)
      return;
    filters.stock = {
      ...(typeof filters.stock === 'object' ? filters.stock : {}),
      lte: v ?? undefined,
    }
  },
});

const filled = computed(() => {
  const { filters } = props;

  return filters.categories?.length
    || filters.tags?.length
    || (typeof filters.stock !== 'object' && filters.price != null)
    || !isEmpty(filters.price)
    || (typeof filters.stock !== 'object' && filters.stock != null)
    || !isEmpty(filters.stock)
    || filters.published !== null;
});
</script>

<template>
  <div class="flex flex-col gap-2">
    <div class="flex flex-col md:flex-row gap-5">
      <VSelect
        v-model="filters.categories" class="w-full md:w-1/2"
        multiple :items="selectableCategories"
        closable-chips item-title="name" item-value="name" label="Category"
        chips clearable hide-details
      >
        <template #chip="{ props: p, item }">
          <VChip
            v-bind="p"
            :text="item.value"
          />
        </template>
      </VSelect>
      <VAutocomplete
        v-model="filters.tags" class="w-full md:w-1/2"
        :items="selectableTags" closable-chips
        multiple item-title="name" item-value="name" clearable
        label="Tags" chips hide-details @update:search="searchTags"
      >
        <template #chip="{ props: p, item }">
          <VChip
            v-bind="p"
            :text="item.value"
          />
        </template>
        <template #item="{ props: p, item }">
          <VListItem
            v-bind="p"
            :title="item.value"
          />
        </template>
      </VAutocomplete>
    </div>
    <div class="flex flex-col md:flex-row gap-5">
      <ACurrencyInput v-model="priceMin" label="Min. Price" hide-details />
      <ACurrencyInput v-model="priceMax" label="Max. Price" hide-details />
    </div>
    <div class="flex flex-col md:flex-row gap-5">
      <ACurrencyInput
        v-model="stockMin" :disabled="(filters.stock != null && ['number', 'string'].includes(typeof filters.stock))"
        label="Min. Stock" hide-details
      />
      <ACurrencyInput
        v-model="stockMax" :disabled="(filters.stock != null && ['number', 'string'].includes(typeof filters.stock))"
        label="Max. Stock" hide-details
      />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
      <VCheckbox
        v-model="filters.stock" box :true-value="0" :false-value="{}"
        label="Stock empty" hide-details
      />
      <VCheckbox
        v-model="filters.stock" box true-value="infinite" :false-value="{}"
        label="Stock infinite" hide-details
      />
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2">
      <VCheckbox
        v-model="filters.published" box :true-value="1" :false-value="null"
        label="Show published products" hide-details
      />
      <VCheckbox
        v-model="filters.published" box true-value="0" :false-value="null"
        label="Show unpublished products" hide-details
      />
    </div>
    <div class="flex justify-center gap-10">
      <VBtn
        color="primary" size="large" :disabled="isEqual(props.filters, filters)"
        @click="handleSubmit()"
      >
        <div class="i-mdi:filter text-2xl" />
      </VBtn>
      <VBtn color="danger" size="large" :disabled="!filled" @click="handleReset()">
        <div class="i-mdi:filter-remove text-2xl" />
      </VBtn>
    </div>
  </div>
</template>
