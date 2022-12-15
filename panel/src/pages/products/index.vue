<script setup lang="tsx">
import type { SortingState } from '@tanstack/vue-table';
import { FlexRender, createColumnHelper, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import dayjs from 'dayjs';
import { cloneDeep } from 'lodash';
import { useUser } from '~/stores/useUser';

const columnHelper = createColumnHelper<Product>();

const router = useRouter();
const user = useUser();

const selectableCategories = ref<Category[]>([]);
const showFilters = ref(false);
const loading = ref(true);
const error = ref(false);
const data = ref<ApiPagination<Product> | null>(null);

const params = reactive<{
  page?: number | null
  limit?: number | null
  search?: string | null
  sortBy?: string | null
  categories?: string[] | null
  tags?: string[] | null
  price?: {
    gte?: number | null
    lte?: number | null
  }
  stock?: {
    gte?: number | null
    lte?: number | null
  } | number | 'infinite'
  published?: number | null
}>({
  page: 1,
  limit: 10,
  ...cloneDeep(router.currentRoute.value.query) as any,
});

const sorting = ref<SortingState>([]);

const columns = [
  columnHelper.display({
    id: 'action',
    maxSize: 40,
    header: '',
    cell: props => (
      <div class="flex justify-center">
        <v-btn to={`/products/${props.row.original.id}`} icon="i-mdi:magnify" />
      </div>
    ),
  }),
  columnHelper.display({
    id: 'published',
    maxSize: 50,
    header: 'Published',
    cell: props => (
      <div class="flex justify-center">
        {
          user.role?.name === 'admin'
            ? <v-checkbox
              hide-details={true} inline={true} class="place-items-center"
              onchange={(e: Event) => updatePublication(props.row.original, !!(e.target as HTMLInputElement).checked)}
              true-value={true}
              model-value={props.row.original.published}
            />
            : <div class={props.row.original.published ? 'i-mdi:check' : ''} />
        }
      </div>
    ),
  }),
  columnHelper.accessor('thumbnail', {
    header: 'Thumbnail',
    id: 'thumbnail',
    minSize: 200,
    cell: (v) => {
      const value = v.getValue();
      return value ? <v-img contain src={v.getValue()?.url} class="h-25" /> : 'No thumbnail set'
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
});

function getData() {
  loading.value = true;
  error.value = false;
  axios.get<ApiPagination<Product>>('products', {
    params: {
      ...params,
      relations: ['thumbnail'],
    },
  }).then((res) => {
    data.value = res.data;
  }).catch(() => {
    error.value = true;
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
  Object.assign(params, filter);
}

const searchValue = useDebounceFn((v: string) => {
  params.search = v || undefined;
}, 200);

watch(() => sorting.value, () => {
  let sortByQuery = '';
  for (const sort of sorting.value)
    sortByQuery += `${(sort.desc ? '-' : '+') + sort.id},`;

  params.sortBy = sortByQuery.slice(0, -1) || null;
});

watch(params, () => {
  router.replace({ query: { ...params } as any });
  getData();
});

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
            <VSelect v-model="params.limit" class="w-30" :items="[10, 25, 50, 100]" label="Limit" hide-details />
          </div>
        </div>
        <div class="flex gap-5 ml-auto">
          <div class="w-100">
            <VTextField
              :model-value="params.search"
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
            <ComplexProductFilter :filters="(params as any)" @update:filters="handleFilter" />
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
                  <div class="flex items-center gap-4">
                    <FlexRender
                      v-if="!header.isPlaceholder"
                      :render="header.column.columnDef.header"
                      :props="header.getContext()"
                    />
                    <div
                      :class="{
                        asc: 'i-mdi:chevron-up', desc: 'i-mdi:chevron-down',
                      }[header.column.getIsSorted() as string]"
                    />
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <template v-if="!error">
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
              </template>
              <template v-else>
                <tr>
                  <td :colspan="columns.length" class="text-center font-bold !text-2xl">
                    Server Error
                  </td>
                </tr>
              </template>
            </tbody>
          </VTable>
        </div>
      </div>
      <div class="mt-5">
        <VPagination
          :model-value="params.page ?? 0"
          :length="data?.last_page"
          rounded="circle"
          @update:model-value="(p) => params.page = p"
        />
      </div>
    </VCardText>
  </VCard>
</template>
