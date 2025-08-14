<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps(['question']);
</script>

<template>
    <ul class="flex gap-1.5 text-xs">
        <li v-for="tag in question.tags" :key="tag.id">
            <Link
                :href="route('questions.tagged', tag)"
                class="inline-block rounded bg-gray-100 px-1.5 py-1 font-semibold text-gray-600 hover:bg-gray-200 hover:text-black"
            >
                {{ tag.name }}
            </Link>
        </li>
    </ul>
</template>
