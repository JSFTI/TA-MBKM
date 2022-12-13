<main class="mx-auto">
  <header>
    <div class="page-thumbnail" style="--image: url('<?= $product->thumbnail?->url ?>')"></div>
    <div class="container mx-auto mt-5">
      <h1 class="main-title text-4xl">
        <?= $product->name ?>
      </h1>
    </div>
  </header>
  <article class="container mx-auto mt-10">
    <div
      class="product-images" x-data="{ glide: null }" 
      x-init="
        glide = new Glide('.product-images', {
          autoplay: false,
          perView: 5,
          breakpoints: {
            1280: {
              perView: 4
            },
            1024: {
              perView: 3
            },
            768: {
              perView: 2
            }
          }
        });

        glide.on('mount.after', () => $el.querySelector('.glide__track').classList.remove('!invisible'));

        glide.mount();
      "
    >
      <div data-glide-el="track" class="glide__track !invisible h-75 relative">
        <ul class="glide__slides h-full">
          <?php foreach($product->images as $image): ?>
            <li class="glide__slide h-full">
              <img
                src="<?= $image->url ?>"
                class="w-full h-full object-cover object-center"
              />
            </li>
          <?php endforeach; ?>
        </ul>
        <div data-glide-el="controls" class="product-image-controls">
          <button
            data-glide-dir="<"
            class="absolute top-1/2 translate-y--1/2 bg-gray bg-opacity-50 hover:bg-opacity-100 text-5xl rounded-full transition left-10"
          >
            <div class="i-ic:round-keyboard-arrow-left"></div>
            <span class="sr-only">Previous Picture</span>
          </button>
          <button
            data-glide-dir=">"
            class="absolute top-1/2 translate-y--1/2 bg-gray bg-opacity-50 hover:bg-opacity-100 text-5xl rounded-full transition right-10"
          >
            <div class="i-ic:round-keyboard-arrow-right"></div>
            <span class="sr-only">Next Picture</span>
          </button>
        </div>
      </div>
    </div>
    <div class="text-2xl font-600 mt-5">
      Rp. <?= number_format($product->price, 2, ',', '.') ?>
    </div>
    <div class="mt-2">
      Stock: <?= $product->stock ?: 'Out of Stock' ?>
    </div>
    <section class="product-details">
      <h2 class="text-2xl font-700 my-5">Product Detail</h2>
      <?= $product->detail ?>
    </section>
  </article>
</main>