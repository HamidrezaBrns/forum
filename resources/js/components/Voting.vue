<script setup lang="ts">
import { VisitOptions } from '@inertiajs/core';
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps(['post']);

const processing = ref(false);

const voteOptions: VisitOptions = {
    preserveScroll: true,
    onStart: () => (processing.value = true),
    onFinish: () => (processing.value = false),
    only: [props.post.type === 'question' ? 'question' : 'answers'],
};

const toggleVote = (isUpvote: boolean) => {
    if (props.post.vote && props.post.vote?.is_upvote == isUpvote) {
        router.delete(route('votes.destroy', [props.post.type, props.post.id]), voteOptions);
    } else {
        router.post(route('votes.store', [props.post.type, props.post.id]), { is_upvote: isUpvote }, voteOptions);
    }
};

const cannotVote = usePage().props.auth.user && !props.post.can?.vote;
const isDisabled = computed(() => processing.value || cannotVote);
</script>

<template>
    <div class="text-center text-xl font-medium">
        <button
            @click="toggleVote(true)"
            :disabled="isDisabled"
            class="cursor-pointer rounded-full border px-2 py-1 hover:opacity-70 disabled:cursor-default"
            :class="{ 'bg-green-200': post.vote?.is_upvote == true, 'bg-gray-100 text-gray-400': cannotVote }"
            :title="cannotVote ? 'You can\'t vote your own post': 'Upvote'"
        >
            <i class="ri-arrow-up-s-fill"></i>
        </button>

        <div class="p-2">{{ post.votes_count }}</div>

        <button
            @click="toggleVote(false)"
            :disabled="isDisabled"
            class="cursor-pointer rounded-full border px-2 py-1 hover:opacity-70 disabled:cursor-default"
            :class="{ 'bg-orange-200': post.vote?.is_upvote == false, 'bg-gray-100 text-gray-400': cannotVote }"
            :title="cannotVote ? 'You can\'t vote your own post': 'Downvote'"
        >
            <i class="ri-arrow-down-s-fill"></i>
        </button>
    </div>
</template>
