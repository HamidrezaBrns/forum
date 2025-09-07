<script setup lang="ts">
import ShowUserAvatar from '@/components/ShowUserAvatar.vue';
import { User } from '@/types';
import { relativeDate } from '@/utilities/date';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    comment: {
        user: User;
        created_at: string;
    };
}>();

const profileLink = computed(() => (props.comment.user ? route('profile.activities', props.comment.user.username) : null));
</script>

<template>
    <div class="flex items-center gap-1 text-xs">
        <component
            :is="profileLink ? Link : 'div'"
            v-bind="profileLink ? { href: profileLink, class: 'group' } : {}"
            class="inline-flex items-center gap-2"
        >
            <ShowUserAvatar :entity="comment" />
            <div class="font-semibold group-hover:underline">{{ comment.user?.username ?? '[Deleted User]' }}│</div>
        </component>

        <span>{{ relativeDate(comment.created_at) }}</span>
    </div>
</template>
