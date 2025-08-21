<script setup lang="ts">
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import 'vue-sonner/style.css';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <Toaster />

    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>

    <ConfirmationModal />
</template>
