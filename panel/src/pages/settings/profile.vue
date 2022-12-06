<template>
  <h1 class="text-3xl text-themeable">
    Profile Settings
  </h1>
  <VDivider class="my-4" />
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div>
      <FormProfileCard />
    </div>
    <div class="lg:row-span-2">
      <ProfilePictureCard />
    </div>
    <div>
      <ChangePasswordCard class="grid-c" />
    </div>
  </div>
</template>

<style scoped lang="scss">
.d-grid{
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(2, 1fr);
  grid-template-areas: "a b"
                       "c b";
  .grid-a{
    grid: a;
  }

  .grid-b{
    grid: b;
    @apply row-span-2;
  }

  .grid-c{
    grid: c;
  }
}
</style>
