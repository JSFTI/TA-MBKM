<script setup lang="tsx">
import { FlexRender, createColumnHelper, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { useRouteQuery } from '@vueuse/router';
import dayjs from 'dayjs';

const columnHelper = createColumnHelper<Product>();

const route = useRoute();
// const router = useRouter();

const selectableCategories = ref<Category[]>([]);
const selectableTags = ref<Tag[]>([]);
const showFilters = ref(false);
const loading = ref(true);
const data = ref<ApiPagination<Product> | null>(null);

const limit = useRouteQuery<string>('limit', '10');
const search = useRouteQuery<string | null>('search', null);
const categories = useRouteQuery<string | string[] | null>('categories', null);
const tags = useRouteQuery<string | string[] | null>('tags', null);
const minPrice = useRouteQuery<string | null>('minPrice', null);
const maxPrice = useRouteQuery<string | null>('maxPrice', null);

const columns = [
  columnHelper.display({
    id: 'published',
    maxSize: 50,
    header: 'Published',
    cell: props => (
      <div class="flex justify-center">
        <v-checkbox
          hide-details={true} inline={true} class="place-items-center"
          true-icon="i-mdi:checkbox-marked" false-icon="i-mdi:checkbox-outline"
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
    cell: v => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(v.getValue()!),
  }),
  columnHelper.accessor('stock', {
    header: 'Stock',
    id: 'stock',
    minSize: 25,
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
  getCoreRowModel: getCoreRowModel(),
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
</script>

<template>
  <VCard>
    <VToolbar title="Products" color="rgba(0,0,0,0)">
      <VToolbarItems>
        <VBtn icon="i-ic:round-add" class="!bg-success !text-white" to="/products/new" />
      </VToolbarItems>
    </VToolbar>
    <VCardText>
      <div class="flex gap-5 px-4">
        <div>
          <VCheckbox
            v-model="showFilters" true-icon="i-mdi:filter-cog"
            false-icon="i-mdi:filter-cog-outline"
            inline
          />
        </div>
        <div class="w-30">
          <VSelect v-model="limit" :items="[10, 25, 50, 100]" label="Limit" />
        </div>
        <div class="!w-100 ml-auto">
          <VTextField
            :model-value="search"
            label="Search"
            @update:model-value="searchValue"
          />
        </div>
      </div>
      <div class="flex flex-col md:flex-row gap-5">
        <div
          class="w-full md:transition-all"
          :class="{
            'max-w-full md:max-w-70': showFilters,
            'h-0 max-w-0 invisible': !showFilters,
          }"
        >
          <VSelect
            v-model="categories" multiple :items="selectableCategories"
            closable-chips item-title="name" item-value="name" label="Category"
            chips
          >
            <template #chip="{ props, item }">
              <VChip
                v-bind="props" close-icon="i-mdi:close-circle"
                :text="item.value"
              />
            </template>
          </VSelect>
          <VAutocomplete
            v-model="tags" :items="selectableTags" closable-chips
            multiple item-title="name" item-value="name"
            label="Tags" chips @update:search="searchTags" @update:model-value="(v) => tags = v"
          >
            <template #chip="{ props: p, item }">
              <VChip
                v-bind="p" close-icon="i-mdi:close-circle"
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
          <ACurrencyInput v-model="minPrice" label="Min. Price" />
          <ACurrencyInput v-model="maxPrice" label="Max. Price" />
        </div>
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
                  :style="{ minWidth: `${header.column.getSize()}px` }"
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
      <!-- <div class="mt-5">
        <VPagination
          v-model="params.page"
          :length="data?.last_page"
          rounded="circle"
        />
      </div> -->
    </VCardText>
  </VCard>
</template>
