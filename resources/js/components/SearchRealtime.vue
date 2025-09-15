<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/vue3';
import { debouncedWatch } from '@vueuse/core';
import { ref } from 'vue';

const props = defineProps<{
    placeholder?: string;
    route: string;
    only?: string[];
    modelValue?: string;
}>();

const search = ref(props.modelValue ?? '');

debouncedWatch(
    search,
    (value) => {
        router.get(
            props.route,
            { search: value },
            {
                preserveState: true,
                replace: true,
                only: props.only ?? [],
            },
        );
    },
    { debounce: 300 },
);
</script>

<template>
    <Input v-model="search" type="search" class="max-w-1/3" :placeholder="placeholder ?? 'Search...'" />
</template>
