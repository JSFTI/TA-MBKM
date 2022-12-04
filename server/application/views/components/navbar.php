<nav class="navbar-grid gap-5 shadow py-4 px-5 sm:px-10 md:px-20 lg:px-35 xl:px-50">
  <a href="<?= base_url() ?>" class="logo text-2xl gap-1 font-bold inline-flex items-center user-select-none">
    <div class="i-ic:twotone-store-mall-directory text-5xl"></div> Arkastore
  </a>
  <div class="flex-grow-1 search">
    <input class="a-form-control w-full" placeholder="Search Product" />
  </div>
  <div class="buttons flex items-center gap-4">
    <div class="i-ic:baseline-local-grocery-store text-3xl mr-5"></div>
    <template x-data x-if="!$store.user.data">
      <div class="flex gap-4">
        <button class="a-btn a-btn-primary-secondary font-semibold hidden sm:block">
          Sign Up
        </button>
        <a
          href="<?= base_url('/login') ?>" class="a-btn a-btn-red-gray font-semibold"
          x-init="$el.href += `?back_url=${encodeURIComponent(window.location.href)}`"
        >
          Sign In
        </a>
      </div>
    </template>
    <template x-data x-if="$store.user.data">
      <div class="flex gap-4 items-center">
        <a
          href="<?= base_url('/panel') ?>"
          class="inline-block h-10 aspect-ratio-1"
          x-data="{imageError: false}"
        >
          <template x-if="!imageError">
            <img src="https://example.com" class="rounded-1/2 w-full h-full" @error="imageError = true" />
          </template>
          <template x-if="imageError">
            <div class="rounded-1/2 bg-gray w-full h-full p-1 text-neutral border-2 border-secondary">
              <div class="i-ic:baseline-person?mask h-full w-full"></div>
            </div>
          </template>
        </a>
        <a href="<?= base_url('/logout') ?>" class="a-btn a-btn-red-gray font-semibold">
          Logout
        </a>
      </div>
    </template>
  </div>
</nav>