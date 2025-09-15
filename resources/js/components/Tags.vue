<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Link } from '@inertiajs/vue3';

defineProps({
    tags: Object,
});
</script>

<template>
    <ul class="flex flex-wrap gap-1">
        <li v-for="(tag, index) in tags" :key="index">
            <Link :href="route('questions.tagged', tag)">
                <Badge variant="secondary" class="font-bold text-gray-600 dark:text-gray-400 hover:opacity-80">{{ tag }}</Badge>
            </Link>
        </li>
    </ul>
</template>
