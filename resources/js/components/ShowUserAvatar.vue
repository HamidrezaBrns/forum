<script setup lang="ts">
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';
import { computed } from 'vue';

const props = defineProps({
    entity: {
        type: Object,
        required: true,
    },
    avatarClass: String,
});

// Compute whether we should show the avatar image
const showAvatar = computed(() => props.entity.user.avatar && props.entity.user.avatar !== '');
</script>

<template>
    <div>
        <Avatar :class="`h-8 w-8 overflow-hidden rounded-lg ${avatarClass}`">
            <AvatarImage v-if="showAvatar" :src="entity.user.avatar!" :alt="entity.user.name" />
            <AvatarFallback class="rounded-lg text-black dark:text-white">
                {{ getInitials(entity.user.name) }}
            </AvatarFallback>
        </Avatar>
    </div>
</template>

<style scoped lang="scss"></style>
