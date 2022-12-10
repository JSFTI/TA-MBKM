<script setup lang="tsx">
import type { SortingState } from '@tanstack/vue-table';
import { FlexRender, createColumnHelper, getCoreRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
import { useRouteQuery } from '@vueuse/router';
import dayjs from 'dayjs';

const columnHelper = createColumnHelper<Product>();

const route = useRoute();
const router = useRouter();

const selectableCategories = ref<Category[]>([]);
const selectableTags = ref<Tag[]>([]);
const showFilters = ref(false);
const loading = ref(true);
const data = ref<ApiPagination<Product> | null>(null);

const page = useRouteQuery<string>('page', '1');
const limit = useRouteQuery<string>('limit', '10');
const search = useRouteQuery<string | null>('search', null);
const categories = useRouteQuery<string[] | null>('categories', []);
const tags = useRouteQuery<string[] | null>('tags', []);
const price = {
  min: useRouteQuery<string | null>('price[gte]', null),
  max: useRouteQuery<string | null>('price[lte]', null),
};
const stock = {
  min: useRouteQuery<string | null>('stock[gte]', null),
  max: useRouteQuery<string | null>('stock[lte]', null),
};

const sorting = ref<SortingState>([]);

const columns = [
  columnHelper.display({
    id: 'published',
    maxSize: 50,
    header: 'Published',
    cell: props => (
      <div class="flex justify-center">
        <v-checkbox
          hide-details={true} inline={true} class="place-items-center"
          onchange={(e: Event) => updatePublication(props.row.original, !!(e.target as HTMLInputElement).checked)}
          true-value={true}
          model-value={props.row.original.published}
        />
      </div>
    ),
  }),
  columnHelper.accessor('thumbnail', {
    header: 'Thumbnail',
    id: 'thumbnail',
    minSize: 100,
    cell: (v) => {
      const value = v.getValue();
      return value ? <v-img cover src={v.getValue()?.url} /> : 'No thumbnail set'
    },
  }),
  columnHelper.accessor('name', {
    header: 'Name',
    id: 'name',
    minSize: 400,
    cell: v => v.renderValue(),
  }),
  columnHelper.accessor('category', {
    header: 'Category',
    id: 'category',
    minSize: 100,
    cell: v => v.getValue()?.name,
  }),
  columnHelper.accessor('price', {
    header: 'Price',
    id: 'price',
    minSize: 100,
    enableSorting: true,
    cell: v => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(v.getValue()!),
  }),
  columnHelper.accessor('stock', {
    header: 'Stock',
    id: 'stock',
    minSize: 25,
    enableSorting: true,
    cell: (v) => {
      const value = v.getValue();
      return value ?? '-'
    },
  }),
  columnHelper.accessor('tags', {
    header: 'Tags',
    id: 'tags',
    minSize: 500,
    cell: v => (
      <div class="flex gap-2 flex-wrap">
        {(v.getValue() ?? []).map(x => (
          <v-chip text={x.name} />
        ))}
      </div>
    ),
  }),
  columnHelper.accessor('created_at', {
    id: 'created_at',
    header: 'Created At',
    minSize: 200,
    cell: (v) => {
      const djs = dayjs(v.getValue());

      return <div>
        <div>{ djs.format('DD MMMM YYYY') }</div>
        <div>{ djs.format('HH:MM:ss') }</div>
      </div>
    },
  }),
  columnHelper.accessor('updated_at', {
    id: 'updated_at',
    header: 'Updated At',
    minSize: 200,
    cell: (v) => {
      if (!v.getValue())
        return '-';

      const djs = dayjs(v.getValue());

      return <div>
        <div>{ djs.format('DD MMMM YYYY') }</div>
        <div>{ djs.format('HH:MM:ss') }</div>
      </div>
    },
  }),
];

const table = useVueTable({
  columns,
  get data() {
    return data.value?.data ?? []
  },
  state: {
    get sorting() {
      return sorting.value
    },
  },
  onSortingChange: (updaterOrValue) => {
    sorting.value = typeof updaterOrValue === 'function' ? updaterOrValue(sorting.value) : updaterOrValue;
  },
  defaultColumn: {
    enableSorting: false,
  },
  getCoreRowModel: getCoreRowModel(),
  getSortedRowModel: getSortedRowModel(),
});

function getData() {
  loading.value = true;
  axios.get<ApiPagination<Product>>('products', {
    params: route.query,
  }).then((res) => {
    data.value = res.data;
  }).finally(() => {
    loading.value = false;
  });
}

function updatePublication(product: Product, published: boolean) {
  axios[published ? 'put' : 'delete'](`products/${product.id}/published`)
    .then(() => {
      product.published = published;
    });
}

function resetFilters() {
  router.replace({
    query: {
      search: undefined,
      categories: [],
      tags: [],
      price: undefined,
      stock: undefined,
    },
  });
}

const searchValue = useDebounceFn((v: string) => {
  search.value = v || null;
}, 200);

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
    selectableTags.value = res.data.filter(x => !tags.value?.includes(x.name!));
  });
}, 200);

watch(() => route.query, () => {
  getData();
}, { deep: true });

getData();

axios.get('categories')
  .then((res) => {
    selectableCategories.value = res.data;
  });

const filtersActive = computed(() => {
  return (
    search.value !== null
    || (categories.value?.length ?? 0) > 0
    || (tags.value?.length ?? 0) > 0
    || price.min.value !== null
    || price.max.value !== null
  );
});

watch(sorting, () => {
  console.log(sorting.value);
});
</script>

<template>
  <VCard>
    <VToolbar title="Products" color="rgba(0,0,0,0)">
      <VToolbarItems>
        <VBtn icon="i-ic:round-add" class="!bg-success !text-white" to="/products/new" />
      </VToolbarItems>
    </VToolbar>
    <VCardText>
      <div class="flex flex-col md:(items-center flex-row px-4) gap-5 mb-5">
        <div class="flex gap-5 items-center">
          <div class="w-30">
            <VSelect v-model="limit" class="w-30" :items="[10, 25, 50, 100]" label="Limit" hide-details />
          </div>
        </div>
        <div class="flex gap-5 ml-auto">
          <div class="w-100">
            <VTextField
              :model-value="search"
              label="Search" hide-details
              @update:model-value="searchValue"
            />
          </div>
          <div>
            <VCheckbox
              v-model="showFilters" true-icon="i-mdi:filter-cog"
              false-icon="i-mdi:filter-cog-outline"
              hide-details
            />
          </div>
          <VBtn v-if="filtersActive" icon="i-mdi:filter-remove" @click="resetFilters()" />
        </div>
      </div>
      <div>
        <CollapseTransition>
          <div v-if="showFilters" class="flex flex-col gap-5 mb-5">
            <div class="flex flex-col lg:flex-row gap-5">
              <VSelect
                v-model="categories"
                class="w-full lg:w-1/2" multiple :items="selectableCategories"
                closable-chips item-title="name" item-value="name" label="Category"
                chips clearable hide-details
              >
                <template #chip="{ props, item }">
                  <VChip
                    v-bind="props"
                    :text="item.value"
                  />
                </template>
              </VSelect>
              <VAutocomplete
                v-model="tags"
                class="w-full lg:w-1/2" :items="selectableTags" closable-chips hide-details
                multiple item-title="name" item-value="name" clearable
                label="Tags" chips @update:search="searchTags" @update:model-value="(v) => tags = v"
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
            <div class="flex flex-col lg:flex-row gap-5">
              <ACurrencyInput v-model="price.min.value" label="Min. Price" hide-details />
              <ACurrencyInput v-model="price.max.value" label="Max. Price" hide-details />
            </div>
            <div class="flex flex-col lg:flex-row gap-5">
              <VTextField v-model="stock.min.value" type="number" class="flex-grow-1" label="Min. Stock" hide-details />
              <div class="flex flex-grow-1">
                <VTextField v-model="stock.max.value" type="number" class="flex-grow-1" label="Max. Stock" hide-details />
              </div>
            </div>
          </div>
        </CollapseTransition>
        <div class="relative rounded overflow-x-auto w-full">
          <VOverlay :model-value="loading" persistent contained class="items-center justify-center">
            <VProgressCircular size="64" color="primary" indeterminate />
          </VOverlay>
          <VTable>
            <thead>
              <tr
                v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id"
              >
                <th
                  v-for="header in headerGroup.headers"
                  :key="header.id"
                  :colSpan="header.colSpan"
                  :class="{
                    'cursor-pointer select-none': header.column.getCanSort(),
                  }"
                  :style="{ minWidth: `${header.column.getSize()}px` }"
                  @click="header.column.getToggleSortingHandler()?.($event)"
                >
                  <FlexRender
                    v-if="!header.isPlaceholder"
                    :render="header.column.columnDef.header"
                    :props="header.getContext()"
                  />
                </th>
              </tr>
            </thead>
            <tbody>
              <template v-if="(table.getRowModel().rows.length > 0)">
                <tr v-for="row in table.getRowModel().rows" :key="row.id">
                  <td v-for="cell in row.getVisibleCells()" :key="cell.id">
                    <FlexRender
                      :render="cell.column.columnDef.cell"
                      :props="cell.getContext()"
                    />
                  </td>
                </tr>
              </template>
              <template v-else-if="!loading">
                <tr>
                  <td :colspan="columns.length" class="text-center font-bold !text-2xl">
                    No Data Available
                  </td>
                </tr>
              </template>
            </tbody>
          </VTable>
        </div>
      </div>
      <div class="mt-5">
        <VPagination
          :model-value="+page"
          :length="data?.last_page"
          rounded="circle"
          @update:model-value="(p) => page = p.toString()"
        />
      </div>
    </VCardText>
  </VCard>
</template>
