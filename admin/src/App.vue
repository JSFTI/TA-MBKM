<script setup lang="ts">
import { isDark, toggleDark } from './composables'
import { baseUrl } from '~/helpers'

const showNavigation = ref(true)
</script>

<template>
  <VApp>
    <VThemeProvider :theme="isDark ? 'dark' : 'light'">
      <VLayout>
        <VAppBar title="Store Admin Panel">
          <template #prepend>
            <VAppBarNavIcon icon="i-ic:round-menu" @click="showNavigation = !showNavigation" />
          </template>
          <div class="flex gap-3 mr-5">
            <VBtn :icon="isDark ? 'i-ic:baseline-dark-mode' : 'i-ic:baseline-light-mode'" @click="toggleDark(!isDark)" />
            <VBtn :href="baseUrl('/logout')" icon="i-ic:round-log-out" @click="toggleDark(!isDark)" />
          </div>
        </VAppBar>
        <VNavigationDrawer v-model="showNavigation" class="pt-3" elevation="10">
          <VListItem
            prepend-avatar="https://randomuser.me/api/portraits/men/85.jpg"
            subtitle="Administrator"
            nav
          >
            <template #title>
              <div class="text-lg">
                Jason Surya Faylim
              </div>
            </template>
          </VListItem>
          <VDivider class="my-5" />
          <VListItem prepend-icon="i-ic:round-dashboard" to="/" nav title="Dashboard" />
          <VListItem prepend-icon="i-ic:baseline-inventory" to="/inventory" nav title="Products" />
          <VListItem prepend-icon="i-ic:baseline-receipt" to="/orders" nav title="Orders" />
          <VListItem prepend-icon="i-ic:round-settings" to="/settings" nav title="Settings" />
        </VNavigationDrawer>
        <VMain tag="main">
          <RouterView />
        </VMain>
      </VLayout>
    </VThemeProvider>
  </VApp>
</template>
