<script setup lang="tsx">
import { FlexRender, createColumnHelper, getCoreRowModel, useVueTable } from '@tanstack/vue-table';

const columnHelper = createColumnHelper<User>();
const showModal = ref<number | null>(null);
const data = ref<User[] | null>(null);
const loading = ref(true);

const columns = [
  columnHelper.display({
    id: 'actions',
    minSize: 50,
    cell: props => (
      <div class="flex gap-3">
        <v-btn icon="i-ic:baseline-edit" size="small" class="!bg-warning !text-dark" onclick={() => showModal.value = props.row.original.id ?? null}/>
        <v-btn icon="i-mdi:trash-can" size="small" class="!bg-danger !text-dark" />
      </div>
    ),
  }),
  columnHelper.accessor('name', {
    header: 'Name',
    minSize: 200,
    cell: v => <string>{ v.getValue() }</string>,
  }),
  columnHelper.accessor('email', {
    header: 'Email',
    minSize: 300,
    cell: v => <string>{ v.getValue() }</string>,
  }),
  columnHelper.accessor('role', {
    header: 'Role',
    minSize: 100,
    cell: v => <string>{ v.getValue()?.display_name }</string>,
  }),
];

const table = useVueTable({
  get data() {
    return data?.value ?? [];
  },
  columns,
  getCoreRowModel: getCoreRowModel(),
});

function getData() {
  loading.value = true;
  axios.get('users')
    .then((res) => {
      data.value = res.data;
    })
    .finally(() => {
      loading.value = false;
    });
}

getData();
</script>

<template>
  <VCard class="!p-5">
    <VOverlay :model-value="loading" persistent contained class="items-center justify-center">
      <VProgressCircular size="64" color="primary" indeterminate />
    </VOverlay>
    <VToolbar color="rgba(0,0,0,0)">
      <VToolbarTitle class="text-2xl">
        Staffs
      </VToolbarTitle>
      <template #append>
        <VBtn icon="i-ic:round-add" class="!bg-success !text-white" @click="(showModal = 0)" />
      </template>
    </VToolbar>
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
            <td v-for="cell in row.getVisibleCells()" :key="cell.id" class="py-2">
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
  </VCard>
  <VDialog :model-value="(showModal !== null)" persistent>
    <VCard class="mx-auto w-full md:w-xl">
      <VToolbar color="rgba(0,0,0,0)">
        <VToolbarTitle>
          {{ showModal === null ? '' : `${showModal ? 'Edit' : 'Add'} User` }}
        </VToolbarTitle>
        <template #append>
          <VBtn icon="i-ic:baseline-cancel" @click="(showModal = null)" />
        </template>
      </VToolbar>
      <UserForm :id="showModal" class="p-5" @submitted="getData()" />
    </VCard>
  </VDialog>
</template>

<style scoped lang="scss">
table{
  th:nth-child(1), td:nth-child(1){
    position: sticky;
    left: 0;
    @apply bg-white dark:bg-dark;
  }
}
</style>
