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
            <li class="glide_slide">
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
        </button>
        <button
          data-glide-dir=">"
          class="arrow-button right-10"
        >
          <div class="i-ic:round-keyboard-arrow-right"></div>
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
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>
  
  <section class="container mx-auto my-15 px-2">
    <div class="flex items-center justify-between">
      <h1 class="font-bold text-4xl">Explore Our Products</h1>
      <a class="font-bold underline text-primary" href="<?= base_url('products') ?>">See More >></a>
    </div>

    <div class="grid grid-cols-6 mt-10 gap-5">
      <?php foreach($products as $product): ?>
        <div class="product-card">
          <div class="product-thumbnail" x-data="{error: false}">
            <?php if($product->thumbnail): ?>
              <img src="<?= $product->thumbnail?->url ?>"
                alt="Picture of <?= htmlspecialchars($product->name) ?>"
                @error="error = true"
              />
              <div class="fallback-thumbnail">
                <div class="i-ic:round-image text-9xl text-gray"></div>
              </div>
            <?php else: ?>
              <div class="fallback-thumbnail">
                <div class="i-ic:round-image text-9xl text-gray"></div>
              </div>
            <?php endif; ?>
          </div>
          <div>
            <h4><?= htmlspecialchars($product->name) ?></h4>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>