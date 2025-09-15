<script setup lang="ts">
import { Answer, Question } from '@/types';
import { formatRelative } from '@/utilities/date';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    post: Question | Answer;
}>();

const profileLink = computed(() => (props.post.user ? route('profile.activities', props.post.user.username) : null));
</script>

<template>
    <div class="flex items-center gap-1 text-right text-sm text-gray-500 sm:block lg:space-y-2 dark:text-gray-400">
        <div>{{ formatRelative(post.created_at) }}</div>

        <component :is="profileLink ? Link : 'div'" v-bind="profileLink ? { href: profileLink, class: 'hover:underline' } : {}" class="font-semibold">
            {{ post.user?.username ?? '[Deleted User]' }}
        </component>
    </div>
</template>
