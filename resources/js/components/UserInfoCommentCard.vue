<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { relativeDate } from '@/utilities/date';
import { computed } from 'vue';

const props = defineProps({
    comment: {
        type: Object,
        required: true,
    },
});

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.comment.user.avatar && props.comment.user.avatar !== '');
</script>

<template>
    <div class="">
        <div class="flex items-center gap-2">
            <div>
                <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
                    <AvatarImage v-if="showAvatar" :src="comment.user.avatar!" :alt="comment.user.name" />
                    <AvatarFallback class="rounded-lg text-black dark:text-white">
                        {{ getInitials(comment.user.name) }}
                    </AvatarFallback>
                </Avatar>
            </div>

            <div class="space-x-1 text-xs text-gray-500">
                <span class="font-semibold">{{ comment.user.name }}<span>│</span></span>
                <span>{{ relativeDate(comment.created_at) }}</span>
                <span v-if="comment.created_at !== comment.updated_at"><span>●</span> edited {{ relativeDate(comment.updated_at) }}</span>
            </div>
        </div>
    </div>
</template>
