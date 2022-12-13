<main class="px-2 mx-auto mb-30">
  <div class="mt-5 flex flex-wrap gap-5 items-center justify-between">
    <h1 class="main-title">
      Arkastore <?= $_GET['category'] ?>s
    </h1>
    <a
      href="<?= site_url('/products') ?>?category=<?= $_GET['category'] === 'Application' ? 'Merchandise' : 'Application' ?>"
      class="ml-auto my-auto text-primary font-600 flex items-center justify-end gap-2 underline"
    >
      See <?= $_GET['category'] === 'Application' ? 'Merchandise' : 'Application' ?>s <div class="i-ic:baseline-keyboard-double-arrow-right"></div>
    </a>
  </div>
  <div class="relative grid grid-cols-1 lg:grid-cols-[350px_1fr] xl:grid-cols-[400px_1fr] mt-10 gap-4">
    <div
      x-data="{
        expanded: false,
        filter: {
          name: '',
          price: {
            gte: '',
            lte: ''
          },
          stock: {
            gte: '',
            lte: ''
          },
          ... <?= alpine_json($_GET) ?>
        },
        maskConfig: {
          alias: 'numeric',
          groupSeparator: '.',
          prefix: 'Rp ',
          rightAlign: false,
        },
        handleSubmit(e){
          const formData = new FormData(e.target);
          const query = [];

          for(let key in Object.fromEntries(formData)){
            let value = formData.get(key);

            if(key.startsWith('price')){
              value = Inputmask.unmask(value, this.maskConfig);
            }

            if(value.length > 0){
              query.push(`${key}=${value}`);
            }
          }

          window.location.href = `?${query.join('&')}`;
        },
        handleClear(){
          window.location.href = `?category=${this.filter.category}`;
        }
      }"
    >
      <form @submit.prevent="handleSubmit" method="GET" class="w-full p-5 shadow-lg lg:sticky lg:top-5">
        <div class="flex flex-col gap-2 mb-5">
          <input type="hidden" name="category" x-model="filter.category" />
          <div class="a-form-group">
            <input class="a-form-control" name="name" type="text" placeholder="Product Name" x-model="filter.name" />
          </div>
          <div class="a-form-group">
            <input
              class="a-form-control" name="price[gte]" type="text"
              placeholder="Minimal Price" x-model="filter.price.gte"
              id="_filter-price-min"
              inputmode="numeric"
              x-init="Inputmask(maskConfig).mask('#_filter-price-min');"
            />
          </div>
          <div class="a-form-group">
          <input
              class="a-form-control" name="price[lte]" type="text"
              placeholder="Minimal Price" x-model="filter.price.lte"
              id="_filter-price-max"
              inputmode="numeric"
              x-init="Inputmask(maskConfig).mask('#_filter-price-max');"
            />
          </div>
          <div class="a-form-group">
            <input class="a-form-control" name="stock[gte]" type="number" placeholder="Minimum Stock" x-model="filter.stock.gte" />
          </div>
          <div class="a-form-group">
            <input class="a-form-control" name="stock[lte]" type="number" placeholder="Maximum Stock" x-model="filter.stock.lte" />
          </div>
        </div>
        <div class="flex gap-10">
          <button class="a-btn a-btn-primary-dark text-light font-bold" type="submit">
            Filter
          </button>
          <button class="a-btn a-btn-danger-dark text-light font-bold" @click="handleClear" type="button">
            Clear Filter
          </button>
        </div>
      </form>
    </div>
    <div class="px-5">
      <div class="grid grid-cols-2  md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4 mt-10 gap-5" >
        <?php foreach($products->data as $product): ?>
          <div class="product-card">
            <div class="product-thumbnail">
              <?php if($product->thumbnail): ?>
                <img
                  src="<?= $product->thumbnail?->url ?>"
                  class="w-full h-full object-cover"
                  alt="Picture of <?= htmlspecialchars($product->name) ?>"
                  onerror="handleProductImageError(event)"
                />
                <div class="fallback-thumbnail !hidden">
                  <div class="i-ic:round-image text-9xl text-gray"></div>
                </div>
              <?php else: ?>
                <div class="fallback-thumbnail">
                  <div class="i-ic:round-image text-9xl text-gray"></div>
                </div>
              <?php endif; ?>
            </div>
            <div class="px-3 py-2 flex flex-col">
              <h4 class="font-600"><?= htmlspecialchars($product->name) ?></h4>
              <span class="font-bold block my-2">Rp. <?= number_format($product->price, 2, ',', '.') ?></span>
              <div class="flex justify-between">
                <small class="mt-auto">
                  <?php if($product->stock !== null): ?>
                  Stock: <?= $product->stock ?: 'Out of stock' ?>
                  <?php endif; ?>
                </small>
                <a href="<?= site_url("/products/$product->slug") ?>" class="a-btn a-btn-secondary-dark font-600 ml-auto">
                  Details
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <ul class="mt-10 flex gap-7 justify-center items-center text-xl user-select-none" x-data="{
        currentPage: <?= $products->current_page ?>,
        lastPage: <?= $products->last_page ?>,
        page: <?= $products->current_page ?>,
        params: Qs.parse(window.location.search.substr(1)),
      }">
        <template x-if="currentPage !== 1">
          <li class="flex items-center">
            <a
              :href="`?${Qs.stringify({...params, page: 1})}`"
              class="i-ic:baseline-keyboard-double-arrow-left hover:text-info transition"
            >
            </a>
          </li>
        </template>
        <template x-if="currentPage === 1">
          <li class="flex items-center">
            <span
              class="i-ic:baseline-keyboard-double-arrow-left text-gray"
            >
            </span>
          </li>
        </template>
        <template x-if="currentPage - 1 > 0">
          <li class="flex items-center">
            <a
              :href="`?${Qs.stringify({...params, page: currentPage - 1})}`"
              class=" hover:text-info transition" 
              x-text="currentPage - 1"
            >
            </a>
          </li>
        </template>
        <li class="flex items-center">
          <span
            class="text-gray"
            x-text="currentPage"
          >
          </span>
        </li>
        <template x-if="currentPage + 1 <= lastPage">
          <li class="flex items-center">
            <a
              :href="`?${Qs.stringify({...params, page: currentPage + 1})}`"
              class=" hover:text-info transition"
              x-text="currentPage + 1"
            >
            </a>
          </li>
        </template>
        <template x-if="currentPage !== lastPage">
          <li class="flex items-center">
            <a
              :href="`?${Qs.stringify({...params, page: lastPage})}`"
              class="i-ic:baseline-keyboard-double-arrow-right hover:text-info transition"
            >
            </a>
          </li>
        </template>
        <template x-if="currentPage === lastPage">
          <li class="flex items-center">
            <span
              class="i-ic:baseline-keyboard-double-arrow-right text-gray"
            >
            </span>
          </li>
        </template>
      </ul>
    </div>
  </div>
</main>