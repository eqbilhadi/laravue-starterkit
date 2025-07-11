<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type Menus, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import * as icons from 'lucide-vue-next';
import { resolveDynamicComponent } from 'vue';

defineProps<{
  items: Menus[];
}>();

function toPascalCase(str: string): string {
  return str.replace(/(^\w|-\w)/g, s => s.replace('-', '').toUpperCase())
}

function getSafeIcon(name: string) {
  const pascal = toPascalCase(name) as keyof typeof icons;
  return resolveDynamicComponent(icons[pascal] ?? icons.FileLock);
}

const page = usePage<SharedData>();
</script>
<template>
  <SidebarGroup class="px-2 py-0">
    <div v-for="item in items" :key="item.id">
      <SidebarGroupLabel v-if="item.is_divider">{{ item.label_name }}</SidebarGroupLabel>
      <SidebarMenu v-else>
        <SidebarMenuItem>
          <SidebarMenuButton
            as-child
            :is-active="page.url.startsWith('/' + item.url)"
            :tooltip="item.label_name"
          >
            <Link :href="item.link">
              <component :is="getSafeIcon(item.icon)" class="w-4 h-4" />
              <span>{{ item.label_name }}</span>
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </div>
  </SidebarGroup>
</template>
