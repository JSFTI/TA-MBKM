<script setup lang="tsx">
import type { SortingState } from '@tanstack/vue-table';
import { FlexRender, createColumnHelper, getCoreRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
import { useRouteQuery } from '@vueuse/router';
import dayjs from 'dayjs';
import { pick } from 'lodash';

const columnHelper = createColumnHelper<Product>();

const router = useRouter();

const selectableCategories = ref<Category[]>([]);
const showFilters = ref(false);
const loading = ref(true);
const data = ref<ApiPagination<Product> | null>(null);

const page = useRouteQuery<string>('page', '1');
const limit = useRouteQuery<string>('limit', '10');
const search = useRouteQuery<string | null>('search', null);
const filters = reactive(pick(router.currentRoute.value.query, ['categories', 'tags', 'price', 'stock']));

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
    params: router.currentRoute.value.query,
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

function handleFilter(filter: any) {
  Object.assign(filters, filter);
  router.replace({
    query: {
      ...router.currentRoute.value.query,
      ...filter,
    },
  });
}

const searchValue = useDebounceFn((v: string) => {
  search.value = v || null;
}, 200);

watch(() => router.currentRoute.value.query, () => {
  getData();
}, { deep: true });

getData();

axios.get('categories')
  .then((res) => {
    selectableCategories.value = res.data;
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
        </div>
      </div>
      <div>
        <CollapseTransition>
          <div v-if="showFilters" class="mb-5">
            <ComplexProductFilter :filters="(filters as any)" @update:filters="handleFilter" />
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
