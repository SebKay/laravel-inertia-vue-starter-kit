<script setup lang="ts">
import type { LayoutProps } from '@js/types/inertia'

import AppSidebar from '@/components/AppSidebar.vue'
import SiteHeader from '@/components/SiteHeader.vue'
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar'

withDefaults(defineProps<LayoutProps>(), {
  heading: '',
})
</script>

<template>
  <SidebarProvider>
    <AppSidebar />
    <SidebarInset>
      <SiteHeader :title="heading" />
      <main class="flex-1 p-4 lg:p-6">
        <slot />
      </main>
    </SidebarInset>
  </SidebarProvider>
</template>

