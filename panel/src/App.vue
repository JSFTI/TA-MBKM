<script setup lang="ts">
import { isDark, toggleDark } from './composables'
import { useUser } from './stores/useUser';
import { baseUrl } from '~/helpers'

const showNavigation = ref(true)
const open = ref<string[]>([]);

const user = useUser();
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
          <VList v-model:opened="open">
            <VListItem
              :key="(user.profile_picture ?? 'nothing')"
              :prepend-avatar="baseUrl(`/api/users/${user.id}/profile-picture`)"
              :subtitle="user.role?.display_name"
              nav
            >
              <template #title>
                <div class="text-lg">
                  {{ user.name }}
                </div>
              </template>
            </VListItem>
            <VDivider class="my-5" />
            <VListItem prepend-icon="i-ic:round-dashboard" to="/" nav title="Dashboard" />
            <VListGroup value="Inventory" fluid collapse-icon="i-ic:round-keyboard-arrow-up" expand-icon="i-ic:round-keyboard-arrow-down">
              <template #activator="{ props }">
                <VListItem
                  v-bind="props"
                  prepend-icon="i-ic:baseline-inventory" nav
                  title="Inventory"
                />
              </template>
              <VListItem
                nav title="Applications"
                prepend-icon="i-ic:round-apps"
                to="/inventory/applications"
              />
              <VListItem
                nav title="Products"
                prepend-icon="i-mdi:package"
                to="/inventory/products"
              />
            </VListGroup>
            <VListItem prepend-icon="i-ic:baseline-receipt" to="/orders" nav title="Orders" />
            <VListGroup value="Settings" fluid collapse-icon="i-ic:round-keyboard-arrow-up" expand-icon="i-ic:round-keyboard-arrow-down">
              <template #activator="{ props }">
                <VListItem
                  v-bind="props"
                  prepend-icon="i-ic:round-settings" nav
                  title="Settings"
                />
              </template>
              <VListItem
                nav title="Carousel"
                prepend-icon="i-mdi:view-carousel-outline"
                to="/settings/carousel"
              />
              <VListItem
                nav title="Profile"
                prepend-icon="i-ic:baseline-person"
                to="/settings/profile"
              />
            </VListGroup>
          </VList>
        </VNavigationDrawer>
        <VMain tag="main" scrollable class="bg-light dark:bg-dark">
          <div class="m-5">
            <RouterView />
          </div>
        </VMain>
      </VLayout>
    </VThemeProvider>
  </VApp>
</template>
