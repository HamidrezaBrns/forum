<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { User } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    user?: User;

    entity?: {
        user: User;
    };

    fallbackClass?: string;
}>();

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const user = computed(() => props.user ?? props.entity?.user ?? authUser.value);

const isDeletedUser = computed(() => props.entity && !props.entity.user);
const hasAvatar = computed(() => !!user.value.avatar);
const avatarSrc = computed(() => (hasAvatar.value ? `/storage/${user.value.avatar}` : ''));
const fallbackText = computed(() => getInitials(user.value.name ?? user.value.username));
</script>

<template>
    <Avatar class="size-8 overflow-hidden rounded-lg" :title="isDeletedUser ? 'Account deleted' : user.username">
        <template v-if="isDeletedUser">
            <AvatarImage src="/storage/avatars/user-deleted-1.jpg" alt="user deleted" />
        </template>

        <template v-else>
            <AvatarImage v-if="hasAvatar" :src="avatarSrc" :alt="user.username" />
            <AvatarFallback :class="['rounded-lg text-black dark:text-white', fallbackClass]">
                {{ fallbackText }}
            </AvatarFallback>
        </template>
    </Avatar>
</template>
