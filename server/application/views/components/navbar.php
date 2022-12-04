<nav class="navbar-grid gap-5 shadow py-4 px-5 sm:px-10 md:px-20 lg:px-35 xl:px-50">
  <a href="<?= base_url() ?>" class="logo text-2xl gap-1 font-bold inline-flex items-center user-select-none">
    <div class="i-ic:twotone-store-mall-directory text-5xl"></div> Arkastore
  </a>
  <div class="flex-grow-1 search">
    <input class="a-form-control w-full" placeholder="Search Product" />
  </div>
  <div class="buttons flex items-center gap-4">
    <div class="i-ic:baseline-local-grocery-store text-3xl"></div>
    <button class="a-btn a-btn-primary-secondary text-white font-semibold hidden sm:block">
      Sign Up
    </button>
    <a
      href="/login" class="a-btn a-btn-red-gray font-semibold"
      x-init="$el.href += `?back_url=${encodeURIComponent(window.location.href)}`"
    >
      Sign In
    </a>
  </div>
</nav>