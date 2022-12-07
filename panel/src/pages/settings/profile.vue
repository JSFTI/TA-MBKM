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
      <ChangePasswordCard />
    </div>
  </div>
</template>
