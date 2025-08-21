<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { relativeDate } from '@/utilities/date';
import { computed } from 'vue';

const props = defineProps({
    post: {
        type: Object,
        required: true,
    },
});

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.post.user.avatar && props.post.user.avatar !== '');
</script>

<template>
    <div class="w-[210px] rounded bg-blue-100 px-1.5 py-2">
        <ul class="mb-1 list-inside list-disc text-xs text-gray-600">
            <!-- created -->
            <li>{{ post.title ? 'asked' : 'answered' }} {{ relativeDate(post.created_at) }}</li>
            <!-- edited -->
            <li v-if="post.created_at !== post.updated_at">edited {{ relativeDate(post.updated_at) }}</li>
        </ul>

        <div class="flex items-center gap-2">
            <div>
                <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                    <AvatarImage v-if="showAvatar" :src="post.user.avatar!" :alt="post.user.name" />
                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                        {{ getInitials(post.user.name) }}
                    </AvatarFallback>
                </Avatar>
            </div>

            <div class="text-xs font-semibold text-gray-600 first-letter:uppercase">{{ post.user.name }}</div>
        </div>
    </div>
</template>
