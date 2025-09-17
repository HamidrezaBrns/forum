<script setup lang="ts">
import ShowUserAvatar from '@/components/ShowUserAvatar.vue';
import { Answer, Question } from '@/types';
import { formatRelative } from '@/utilities/date';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    post: Question | Answer;
}>();

const actionText = computed(() => (props.post.type === 'question' ? 'asked,' : 'answered,'));
const isEdited = computed(() => props.post.created_at !== props.post.updated_at);

const profileLink = computed(() => (props.post.user ? route('profile.activities', props.post.user.username) : null));
</script>

<template>
    <div class="w-[210px] rounded bg-blue-100 px-1.5 py-2 text-gray-600 dark:bg-slate-700 dark:text-gray-300">
        <ul class="mb-2 list-inside list-disc text-xs">
            <li>{{ $t(actionText) }} {{ formatRelative(post.created_at) }}</li>
            <li v-if="isEdited">{{ $t('edited,') }} {{ formatRelative(post.updated_at) }}</li>
        </ul>

        <component
            :is="profileLink ? Link : 'div'"
            v-bind="profileLink ? { href: profileLink, class: 'group' } : {}"
            class="inline-flex items-center gap-2"
        >
            <ShowUserAvatar :entity="post" class="size-10" />
            <div class="text-sm font-semibold" :class="profileLink ? 'group-hover:underline' : ''">
                {{ post.user?.username ?? $t('[Deleted User]') }}
            </div>
        </component>
    </div>
</template>
