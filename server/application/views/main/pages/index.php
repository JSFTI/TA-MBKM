<main>
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
</main>