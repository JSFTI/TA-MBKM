<script setup lang="tsx">
import { FlexRender, createColumnHelper, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { useRouteQuery } from '@vueuse/router';
import { useUser } from '~/stores/useUser';

const columnHelper = createColumnHelper<User>();

const router = useRouter();
const user = useUser();
const showModal = ref<number | null>(null);
const data = ref<ApiPagination<User> | null>(null);
const loading = ref(true);
const page = useRouteQuery<string>('page', '1');
const limit = useRouteQuery<string>('limit', '10');
const search = useRouteQuery<string | null>('search', null);

const columns = [
  columnHelper.display({
    id: 'actions',
    minSize: 50,
    cell: props => (
      <div class="flex gap-3">
        <v-btn icon="i-ic:baseline-edit" size="small" class="!bg-warning !text-dark" onclick={() => showModal.value = props.row.original.id ?? null}/>
        {
          user.id !== props.row.original.id
            ? <v-btn icon="i-mdi:trash-can" size="small" class="!bg-danger !text-dark" onclick={() => handleDelete(props.row.original.id!)} />
            : null
        }
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
    return data?.value?.data ?? [];
  },
  columns,
  getCoreRowModel: getCoreRowModel(),
});

function getData() {
  loading.value = true;
  axios.get('users', { params: router.currentRoute.value.query })
    .then((res) => {
      data.value = res.data;
    })
    .finally(() => {
      loading.value = false;
    });
}

function handleSubmittedUser(u: User) {
  if (u.id === user.id)
    user.$patch(u);

  nextTick(() => {
    if (showModal.value === 0)
      showModal.value = u.id!;

    getData()
  });
}

function handleDelete(id: number) {
  axios.delete(`/users/${id}`)
    .then(() => {
      getData();
    });
}

const handleSearch = useDebounceFn((v) => {
  search.value = v || undefined;
}, 200)

getData();

watch(() => router.currentRoute.value.query, () => {
  getData();
}, { deep: true });
</script>

<template>
  <VCard>
    <VToolbar color="rgba(0,0,0,0)">
      <VToolbarTitle class="text-2xl">
        Staffs
      </VToolbarTitle>
      <VToolbarItems>
        <VBtn icon="i-ic:round-add" class="!bg-success !text-white" @click="(showModal = 0)" />
      </VToolbarItems>
    </VToolbar>
    <div class="p-5">
      <div class="flex gap-2">
        <div class="w-30">
          <VSelect v-model="limit" :items="[10, 25, 50, 100]" label="Limit" />
        </div>
        <div class="!w-100 ml-auto">
          <VTextField
            :model-value="search"
            label="Search"
            @update:model-value="handleSearch"
          />
        </div>
      </div>
      <div class="relative rounded">
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
      </div>
      <div class="mt-5">
        <VPagination
          :model-value="+page"
          :length="data?.last_page"
          rounded="circle"
          @update:model-value="p => page = p.toString()"
        />
      </div>
    </div>
  </VCard>
  <VDialog :model-value="(showModal !== null)" persistent>
    <VCard v-if="showModal" class="mx-auto w-full">
      <VToolbar color="rgba(0,0,0,0)" title="Edit User">
        <template #append>
          <VBtn icon="i-ic:baseline-cancel" @click="(showModal = null)" />
        </template>
      </VToolbar>

      <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 px-5">
        <div class="lg:row-span-2">
          <UserPictureForm
            :id="showModal"
            @submitted="(p) => showModal === user.id && (user.profile_picture = p || 'null')"
          />
        </div>
        <div>
          <UserForm :id="showModal" @submitted="handleSubmittedUser" />
        </div>
        <div>
          <ForcePasswordForm :id="showModal" />
        </div>
      </div>
    </VCard>
    <VCard v-else-if="(showModal === 0)" class="mx-auto w-full md:w-xl">
      <VToolbar color="rgba(0,0,0,0)" title="Add User">
        <template #append>
          <VBtn icon="i-ic:baseline-cancel" @click="(showModal = null)" />
        </template>
      </VToolbar>
      <NewUserForm @submitted="handleSubmittedUser" />
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
