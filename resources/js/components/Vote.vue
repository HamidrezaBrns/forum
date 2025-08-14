<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps(['post']);

const processing = ref(false);

const toggleVote = (isUpvote: boolean) => {
    if (props.post.vote && props.post.vote?.is_upvote == isUpvote) {
        router.delete(route('votes.destroy', [props.post.type, props.post.id]), {
            preserveScroll: true,
            onStart: () => (processing.value = true),
            onFinish: () => (processing.value = false),
            // onError: (errors: any) => {
            //     if (errors.response && errors.response.status === 429) {
            //         toast.error('Too many requests');
            //     }
            // },
        });
    } else {
        router.post(
            route('votes.store', [props.post.type, props.post.id]),
            { is_upvote: isUpvote },
            {
                preserveScroll: true,
                onStart: () => (processing.value = true),
                onFinish: () => (processing.value = false),
            },
        );
    }
};
</script>

<template>
    <div class="text-center text-xl font-medium">
        <button
            @click="toggleVote(true)"
            :disabled="processing || !post.can?.vote"
            class="rounded-full border px-2 py-1 hover:opacity-70"
            :class="{ 'bg-green-200': post.vote?.is_upvote == true, 'bg-gray-100 text-gray-400': !post.can?.vote }"
        >
            ▲
        </button>

        <div class="p-2">{{ post.votes_count }}</div>

        <button
            @click="toggleVote(false)"
            :disabled="processing || !post.can?.vote"
            class="rounded-full border px-2 py-1 hover:opacity-70"
            :class="{ 'bg-orange-200': post.vote?.is_upvote == false, 'bg-gray-100 text-gray-400': !post.can?.vote }"
        >
            ▼
        </button>
    </div>
</template>
