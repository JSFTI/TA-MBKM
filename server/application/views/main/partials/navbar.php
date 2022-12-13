<nav class="flex items-center  gap-5 shadow py-4 px-5 sm:px-10 md:px-20 lg:px-35 xl:px-50">
  <a href="<?= site_url() ?>" class="logo text-2xl gap-1 font-bold inline-flex items-center user-select-none">
    <div class="i-ic:twotone-store-mall-directory text-5xl"></div> Arkastore
  </a>
  <div class="buttons flex items-center gap-4 ml-auto">
    <template x-data x-if="!$store.user.data">
      <div class="flex gap-4">
        <a
          href="<?= site_url('/login') ?>" class="a-btn a-btn-red-gray font-semibold"
          x-init="$el.href += `?back_url=${encodeURIComponent(window.location.href)}`"
        >
          Sign In
        </a>
      </div>
    </template>
    <template x-data x-if="$store.user.data">
      <div class="flex gap-4 items-center">
        <a
          href="<?= site_url('/panel') ?>"
          class="inline-block h-10 aspect-ratio-1"
          x-data="{imageError: false}"
        >
          <template x-if="!imageError">
            <img :src="baseUrl(`api/users/${$store.user.data.id}/profile-picture`)" class="rounded-1/2 w-full h-full" @error="imageError = true" />
          </template>
          <template x-if="imageError">
            <div class="rounded-1/2 bg-gray w-full h-full p-1 text-neutral border-2 border-secondary">
              <div class="i-ic:baseline-person?mask h-full w-full"></div>
            </div>
          </template>
        </a>
      </div>
    </template>
  </div>
</nav>