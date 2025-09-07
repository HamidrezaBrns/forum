<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    tags: Object,
});
</script>

<template>
    <ul class="flex flex-wrap gap-1.5 text-xs">
        <li v-for="(tag, index) in tags" :key="index">
            <Link
                :href="route('questions.tagged', tag)"
                class="inline-block rounded bg-gray-200 dark:bg-gray-800 dark:text-gray-400 px-1.5 py-0.5 font-semibold text-gray-600 hover:opacity-60 hover:text-black"
            >
                {{ tag }}
            </Link>
        </li>
    </ul>
</template>
