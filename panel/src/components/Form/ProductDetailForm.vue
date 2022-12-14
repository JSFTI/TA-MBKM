<script setup lang="ts">
import { AxiosError } from 'axios';
import { pick, uniqBy } from 'lodash';
import { useUser } from '~/stores/useUser';

const props = withDefaults(defineProps<{
  id?: number | null
}>(), {
  id: null,
});

const initForm = {
  name: '',
  slug: '',
  category_id: null,
  price: null,
  stock: 0 as number | null,
  published: false,
  tags: [] as Tag[],
  detail: '',
};

const initInvalidFeedbacks = {
  name: '',
  slug: '',
  category_id: '',
  price: '',
  stock: '',
  published: '',
  detail: '',
  tags: '',
};

const user = useUser();
const toast = useToast();
const router = useRouter();
const form = reactive({ ...initForm });
const invalidFeedbacks = reactive({ ...initInvalidFeedbacks });
const categories = ref<Category[]>([]);
const tags = ref<Tag[]>([]);
const loading = reactive({
  form: false,
  search: false,
});

axios.get('categories')
  .then((res) => {
    categories.value = res.data;
  });

const searchTag = useDebounceFn((search: string) => {
  if (search.length === 0) {
    tags.value = [];
    return;
  }
  loading.search = true;
  axios.get<Tag[]>('tags', {
    params: {
      search,
    },
  }).then((res) => {
    const currentTagIds: number[] = form.tags.map(x => x.id!);

    tags.value = res.data.filter(x => !currentTagIds.includes(x.id!));

    if (search && (!res.data.length || res.data.find(x => x.name !== search))) {
      tags.value.unshift({
        id: 0,
        name: search,
      });
    } else {
      tags.value = tags.value.filter(x => !!x.id);
    }
  }).finally(() => {
    loading.search = false;
  });
}, 200);

async function handleSubmit() {
  loading.form = true;
  Object.assign(invalidFeedbacks, initInvalidFeedbacks);

  const formData = { ...form };
  formData.tags = formData.tags.map(x => x.name) as any;

  try {
    if (props.id) {
      await axios.put(`products/${props.id}`, formData);
      toast.success('Product updated');
      return;
    }
    const res = await axios.post<Product>('products', formData);
    toast.success('Product created');
    router.push(`/products/${res.data.id}`);
  } catch (e: unknown) {
    if (e instanceof AxiosError<ApiInvalidFeedback>)
      Object.assign(invalidFeedbacks, e.response!.data.errors);
  } finally {
    loading.form = false;
  }
}

function getData() {
  axios.get<Product>(`/products/${props.id}`, {
    params: {
      relations: ['tags'],
    },
  }).then((res) => {
    Object.assign(form, pick(res.data, ['name', 'category_id', 'price', 'stock', 'published', 'slug', 'tags', 'detail']));
  });
}

watch(() => form.tags.length, () => {
  form.tags = uniqBy(form.tags, 'name');
});

if (props.id)
  getData();
</script>

<template>
  <VForm @submit.prevent="handleSubmit">
    <div class="flex flex-col gap-2 py-5">
      <div class="grid gap-3 md:grid-cols-1 lg:grid-cols-2">
        <VTextField
          v-model="form.name" label="Product Name"
          :error="!!invalidFeedbacks.name"
          :error-messages="invalidFeedbacks.name"
        />
        <VTextField
          v-model="form.slug" label="Product Slug"
          :error="!!invalidFeedbacks.slug"
          :error-messages="invalidFeedbacks.slug"
        />
      </div>
      <div class="grid gap-3 md:grid-cols-1 lg:grid-cols-2">
        <ACurrencyInput
          v-model="form.price" label="Price" inputmode="numeric"
          :error="!!invalidFeedbacks.price"
          :error-messages="invalidFeedbacks.price"
        />
        <div class="flex gap-5 items-center mb-5">
          <VCheckboxBtn
            :model-value="(form.stock !== null)"
            @update:model-value="(form.stock = form.stock === null ? 0 : null)"
          />
          <VTextField
            v-model="form.stock" type="number" min="0" label="Stock" :disabled="(form.stock === null)"
            hide-details
            hint="Disable if stock is infinite"
            :error="!!invalidFeedbacks.stock"
            :error-messages="invalidFeedbacks.stock"
          />
        </div>
      </div>
      <div class="grid gap-3 md:grid-cols-1 lg:grid-cols-2">
        <VSelect
          v-model="form.category_id" :items="categories" item-title="name"
          item-value="id" label="Category"
          :error="!!invalidFeedbacks.category_id"
          :error-messages="invalidFeedbacks.category_id"
        />
        <VAutocomplete
          v-model="form.tags" :items="tags" multiple label="Tags"
          item-title="name" item-value="name" chips closable-chips
          return-object placeholder="Duplicate tags will be truncated"
          :loading="loading.search" :error="!!invalidFeedbacks.tags"
          :error-messages="invalidFeedbacks.tags"
          @update:search="searchTag"
        >
          <template #chip="{ props: p, item }">
            <VChip
              v-bind="p"
              :text="`${item.value.name} ${item.value.products_count ? `(${item.value.products_count})` : ''}`"
            />
          </template>
          <template #item="{ props: p, item }">
            <VListItem
              v-bind="p"
              :title="`${item.value.name} ${item.value.products_count ? `(${item.value.products_count})` : ''}`"
            />
          </template>
        </VAutocomplete>
      </div>
      <VCheckbox
        v-if="user.role?.name === 'admin'" v-model="form.published" label="Publish"
        :error="!!invalidFeedbacks.published"
        :error-messages="invalidFeedbacks.published"
      />
      <AEditor v-model:content="form.detail" />
    </div>
    <VBtn type="submit" color="primary">
      Submit
    </VBtn>
  </VForm>
</template>
