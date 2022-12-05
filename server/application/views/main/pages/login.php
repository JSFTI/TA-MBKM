<main class="text-center pt-15" x-data="{
    showPassword: false,
    validationErrors: <?= alpine_json($this->session->flashdata('validation_errors')) ?>,
}">
  <a href="<?= base_url() ?>" rel="nofollow">
    <h1 class="inline-flex flex-col items-center text-primary">
      <div class="i-ic:twotone-store-mall-directory text-9xl"></div>
      <div class="text-5xl font-bold">Arkastore</div>
    </h1>
  </a>
  <form
    class="mt-5 text-left max-w-125 p-5 w-5/6 mx-auto shadow flex flex-col gap-5"
    method="POST"
    action="<?= base_url('/login' . (isset($_GET['back_url']) ? '?back_url=' . urlencode($_GET['back_url']) : '')) ?>"
    x-data="{
      values: {
        email: '',
        password: '',
        ...<?= alpine_json($this->session->flashdata('old_values')) ?>
      }
    }"
  >
    <div class="a-form-group" :class="{invalid: validationErrors?.email}">
      <input
        class="a-form-control" name="email" placeholder="Email"
        x-model="values.email"
      />
      <strong class="invalid-feedback" x-text="validationErrors?.email"></strong>
    </div>
    <div class="a-form-group" :class="{invalid: validationErrors?.password}">
      <input
        class="a-form-control"
        x-bind:type="showPassword ? 'text' : 'password'"
        name="password" placeholder="Password"
        x-model="values.password"
      />
      <strong class="invalid-feedback" x-text="validationErrors?.password"></strong>
    </div>
    <div class="a-form-checkbox mt-4">
      <input type="checkbox" class="a-form-checkbox-input" id="show-password" x-model="showPassword" />
      <label class="a-form-checkbox-label" for="show-password">
        Show password
      </label>
    </div>
    <button class="a-btn a-btn-primary-gray text-white font-bold mt-4">
      Sign In
    </button>
  </form>
</main>