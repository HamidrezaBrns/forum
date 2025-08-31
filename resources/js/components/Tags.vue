<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    tags: Object,
});
</script>

<template>
    <ul class="flex gap-1.5 text-xs">
        <li v-for="(tag, index) in tags" :key="index">
            <Link
                :href="route('questions.tagged', tag)"
                class="inline-block rounded bg-gray-100 px-1.5 py-1 font-semibold text-gray-600 hover:bg-gray-200 hover:text-black"
            >
                {{ tag }}
            </Link>
        </li>
    </ul>
</template>
