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

    <div class="rtl:text-right text-sm leading-tight">
        <div class="truncate font-medium">{{ user.name ?? user.username }}</div>
        <div v-if="showEmail" class="truncate text-xs text-muted-foreground">{{ user.email }}</div>
    </div>
</template>
