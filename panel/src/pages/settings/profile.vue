<template>
  <h1 class="text-3xl text-themeable">
    Profile Settings
  </h1>
  <VDivider class="my-4" />
  <div class="flex flex-col lg:flex-row gap-5">
    <FormProfileCard class="flex-grow-1" />
    <ProfilePictureCard class="flex-grow-1" />
  </div>
  <ChangePasswordCard class="w-full md:w-md" />
</template>
