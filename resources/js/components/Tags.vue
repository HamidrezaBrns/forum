<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Link } from '@inertiajs/vue3';

defineProps({
    tags: Object,
    disable: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <ul class="flex flex-wrap gap-1">
        <li v-for="(tag, index) in tags" :key="index">
            <component :is="disable ? 'div' : Link" v-bind="!disable ? { href: route('questions.tagged', tag), class: 'hover:opacity-80' } : {}">
                <Badge :variant="disable ? 'outline' : 'secondary'" class="font-bold text-gray-600 dark:text-gray-400">
                    {{ tag }}
                </Badge>
            </component>
        </li>
    </ul>
</template>
