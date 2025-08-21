<script setup lang="ts">
import { relativeDate } from '@/utilities/date';

defineProps({
    post: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="space-x-1 text-xs text-gray-500">
        <span class="font-semibold">{{ post.user.name }}<span>│</span></span>
        <span>asked {{ relativeDate(post.created_at) }}</span>
        <span v-if="post.created_at !== post.updated_at"><span>●</span> edited {{ relativeDate(post.updated_at) }}</span>
    </div>
</template>
