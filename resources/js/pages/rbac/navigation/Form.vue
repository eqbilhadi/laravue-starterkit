<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { router, useForm, Head, Link, usePage } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import { BreadcrumbItem, SharedData } from "@/types";

interface MenuFormData {
  label_name: string;
  controller_name: string;
  route_name: string;
  url: string;
  icon: string;
  is_divider: number;
  [key: string]: string | number;
}

const props = defineProps<{
  menu?: Partial<MenuFormData> & { id: string };
}>();

const page = usePage<SharedData>();
const isEdit = computed(() => !!props.menu?.id);

const form = useForm<MenuFormData>({
  label_name: props.menu?.label_name ?? "",
  controller_name: props.menu?.controller_name ?? "",
  route_name: props.menu?.route_name ?? "",
  url: props.menu?.url ?? "",
  icon: props.menu?.icon ?? "",
  is_divider: props.menu?.is_divider ?? 0,
});

const handleSubmit = () => {
  if (isEdit.value) {
    form.put(route("rbac.nav.update", { id: props.menu?.id }));
  } else {
    form.post(route("rbac.nav.store"));
  }
};

watch(
  () => page.props.flash,
  (flash) => {
    if (flash.success) form.reset();
  }
);

const breadcrumbs: BreadcrumbItem[] = [
  { title: "Dashboard", href: "/dashboard" },
  { title: "Navigation Management", href: route("rbac.nav.index") },
  { title: isEdit.value ? "Edit Navigation" : "Add Navigation", href: "#" },
];
</script>

<template>
  <Head :title="isEdit ? 'Edit Navigation' : 'Add Navigation'" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-4">
      <h2 class="text-xl font-semibold">
        {{ isEdit ? "Edit Navigation" : "Add Navigation" }}
      </h2>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm mb-1">Label</label>
          <Input v-model="form.label_name" :error="form.errors.label_name" />
        </div>

        <div>
          <label class="block text-sm mb-1">Controller</label>
          <Input v-model="form.controller_name" :error="form.errors.controller_name" />
        </div>

        <div>
          <label class="block text-sm mb-1">Route Name</label>
          <Input v-model="form.route_name" :error="form.errors.route_name" />
        </div>

        <div>
          <label class="block text-sm mb-1">URL</label>
          <Input v-model="form.url" :error="form.errors.url" />
        </div>

        <div>
          <label class="block text-sm mb-1">Icon</label>
          <Input v-model="form.icon" :error="form.errors.icon" />
        </div>

        <div>
          <label class="block text-sm mb-1">Is Divider</label>
          <select v-model="form.is_divider" class="form-select">
            <option :value="0">No</option>
            <option :value="1">Yes</option>
          </select>
        </div>

        <div class="flex items-center justify-between mt-6">
          <Link
            :href="route('rbac.nav.index')"
            class="text-sm text-gray-600 hover:underline"
          >
            Cancel
          </Link>

          <Button type="submit" :disabled="form.processing">
            {{ isEdit ? "Update" : "Save" }}
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
