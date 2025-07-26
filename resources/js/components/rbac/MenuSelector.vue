<script setup lang="ts">
import { ref, computed } from "vue";
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from "@/components/ui/table";
import ScrollArea from "@/components/ui/scroll-area/ScrollArea.vue";
import CheckboxGroup from "@/components/rbac/CheckboxGroup.vue";
import InputError from "@/components/InputError.vue";
import Button from "@/components/ui/button/Button.vue";
const props = defineProps<{
  menus: { id: number; label_name: string; link: string }[];
  modelValue: number[];
  error?: string;
}>();

const emit = defineEmits(["update:modelValue"]);
const menuSearch = ref("");

const filteredMenus = computed(() => {
  return props.menus.filter(m =>
    m.label_name.toLowerCase().includes(menuSearch.value.toLowerCase())
  );
});

const toggleMenu = (id: number) => {
  emit("update:modelValue", props.modelValue.includes(id)
    ? props.modelValue.filter(i => i !== id)
    : [...props.modelValue, id]
  );
};

const toggleAll = () => {
  const allIds = Object.values(filteredMenus.value)
    .flat()
    .map((p) => p.id);

  const isAllSelected = allIds.every((id) => props.modelValue.includes(id));

  emit(
    "update:modelValue",
    isAllSelected
      ? props.modelValue.filter((id) => !allIds.includes(id)) // uncheck all
      : [...new Set([...props.modelValue, ...allIds])] // check all
  );
};

const allVisibleSelected = computed(() => {
  const allIds = Object.values(filteredMenus.value)
    .flat()
    .map((p) => p.id);
  return allIds.every((id) => props.modelValue.includes(id));
});
</script>

<template>
  <div class="w-full rounded-md border p-4">
    <div class="mb-2">
      <h3 class="text-sm font-semibold">Accessible Menus</h3>
      <p class="text-sm text-muted-foreground">Determine which menus this role can access in the application.</p>
    </div>
    <div class="flex justify-start mb-3 gap-2">
      <input v-model="menuSearch" placeholder="Search menu..." class="px-2 py-1 text-sm border rounded-md w-full" />
      <Button @click="toggleAll" class="text-sm h-8" type="button">
        {{ allVisibleSelected ? "Unselect All" : "Select All" }}
      </Button>
    </div>
    <ScrollArea class="h-[300px] mb-4">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Menu</TableHead>
            <TableHead class="text-center">Access</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow
            v-for="menu in filteredMenus"
            :key="menu.id"
            class="cursor-pointer hover:bg-muted/40 transition"
            @click="toggleMenu(menu.id)"
          >
            <TableCell>{{ menu.label_name }}</TableCell>
            <TableCell class="flex justify-center">
              <CheckboxGroup
                :value="menu.id"
                :model-value="modelValue"
                @update:modelValue="(val) => emit('update:modelValue', val)"
                @click.stop
              />
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </ScrollArea>
    <InputError :message="error" />
  </div>
</template>
