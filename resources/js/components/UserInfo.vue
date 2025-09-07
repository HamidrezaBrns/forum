<script setup lang="ts">
import ShowUserAvatar from '@/components/ShowUserAvatar.vue';
import type { User } from '@/types';

interface Props {
    user: User;
    showEmail?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});
</script>

<template>
    <ShowUserAvatar />

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">{{ user.name ?? user.username }}</span>
        <span v-if="showEmail" class="truncate text-xs text-muted-foreground">{{ user.email }}</span>
    </div>
</template>
