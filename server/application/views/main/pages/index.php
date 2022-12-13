<main>
  <?php if($carousels->count() > 0): ?>
  <section>
    <div
      class="relative main-carousel" x-data="mainCarousel"
      x-init="glide.on('move', function(){ index = glide.index })"
    >
      <div data-glide-el="track" class="glide__track">
        <ul class="glide__slides h-80vh invisible">
          <?php foreach($carousels as $carousel): ?>
            <li class="glide__slide">
              <img
                src="<?= $carousel->url ?>" class="object-cover h-full w-full"
                alt="Carousel"
              />
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div data-glide-el="controls">
        <button
          data-glide-dir="<"
          class="arrow-button left-10"
        >
          <div class="i-ic:round-keyboard-arrow-left"></div>
          <span class="sr-only">Previous Carousel</span>
        </button>
        <button
          data-glide-dir=">"
          class="arrow-button right-10"
        >
          <div class="i-ic:round-keyboard-arrow-right"></div>
          <span class="sr-only">Next Carousel</span>
        </button>
      </div>
      <div
        class="glide__bullets flex gap-5 absolute bottom-10 left-1/2 translate-x--1/2 text-2xl text-white"
        data-glide-el="controls[nav]"
      >
        <?php foreach($carousels as $i => $carousel): ?>
          <button
            :class="index === <?= $i ?> ? 'i-ic:baseline-radio-button-checked' : 'i-ic:baseline-radio-button-unchecked'"
            data-glide-dir="=<?= $i ?>"
          >
            <span class="sr-only"><?= ordinal_format($i) ?> Carousel Image</span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
  
  <section class="container mx-auto my-15 px-2">
    <div class="flex items-center justify-between">
      <h1 class="font-bold text-2xl md:text-4xl">Our Applications</h1>
      <a
        class="text-primary font-600 underline flex items-center gap-2"
        href="<?= site_url('products') ?>?category=Application"
      >
        See more <div class="i-ic:baseline-keyboard-double-arrow-right"></div>
      </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mt-10 gap-5">
      <?php foreach($apps as $app): ?>
        <div class="product-card">
          <div class="product-thumbnail">
            <?php if($app->thumbnail): ?>
              <img
                src="<?= $app->thumbnail?->url ?>"
                class="w-full h-full object-cover"
                alt="Picture of <?= htmlspecialchars($app->name) ?>"
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
          <div class="px-1 py-2 flex flex-col">
            <h4 class="font-600"><?= htmlspecialchars($app->name) ?></h4>
            <span class="font-bold block my-2">Rp. <?= number_format($app->price, 2, ',', '.') ?></span>
            <a href="<?= site_url("/products/$app->slug") ?>" class="a-btn a-btn-secondary-dark font-600 ml-auto">
              Details
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="container mx-auto my-15 px-2">
    <div class="flex items-center justify-between">
      <h1 class="font-bold text-2xl md:text-4xl">Merchandises</h1>
      <a
        class="text-primary font-600 underline flex items-center gap-2"
        href="<?= site_url('products') ?>?category=Merchandise  "
      >
        See more <div class="i-ic:baseline-keyboard-double-arrow-right"></div>
      </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mt-10 gap-5">
      <?php foreach($merchandises as $merchandise): ?>
        <div class="product-card">
          <div class="product-thumbnail">
            <?php if($merchandise->thumbnail): ?>
              <img
                src="<?= $merchandise->thumbnail?->url ?>"
                class="w-full h-full object-cover"
                alt="Picture of <?= htmlspecialchars($merchandise->name) ?>"
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
          <div class="px-1 py-2 flex flex-col">
            <h4 class="font-600"><?= htmlspecialchars($merchandise->name) ?></h4>
            <span class="font-bold block my-2">Rp. <?= number_format($merchandise->price, 2, ',', '.') ?></span>
            <div class="flex justify-between">
              <small class="mt-auto">
                Stock: <?= $merchandise->stock ?: 'Out of stock' ?>
              </small>
              <a href="<?= site_url("/products/$merchandise->slug") ?>" class="a-btn a-btn-secondary-dark font-600 ml-auto">
                Details
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>