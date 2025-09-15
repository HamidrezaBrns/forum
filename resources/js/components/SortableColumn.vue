<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronDown, ChevronsUpDown, ChevronUp } from 'lucide-vue-next';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const props = withDefaults(
    defineProps<{
        // label: string;
        sortKey: string;
        sortMeta: { sort: string; dir: 'asc' | 'desc' };
        baseRoute: string;
        params?: Record<string, any>;
        only?: string[];
    }>(),
    {
        params: () => ({}),
        only: () => [],
    },
);

const isActive = computed(() => props.sortMeta.sort === props.sortKey);

const nextDirection = computed(() => (isActive.value && props.sortMeta.dir === 'desc' ? 'asc' : 'desc'));

const href = computed(() =>
    route(props.baseRoute, {
        ...props.params,
        sort: props.sortKey,
        dir: nextDirection.value,
    }),
);

const onlyWithMeta = computed(() => ['meta', ...props.only]);

const icon = computed(() => {
    return !isActive.value ? ChevronsUpDown : props.sortMeta.dir === 'desc' ? ChevronDown : ChevronUp;
});
</script>

<template>
    <Link :href="href" :only="onlyWithMeta" class="group flex items-center gap-1" :class="{ 'font-bold text-black dark:text-white': isActive }">
        <slot />
        <component :is="icon" class="inline size-3.5 group-hover:text-black dark:group-hover:text-white" />
    </Link>
</template>
