<script setup lang="ts">
import CommentSection from '@/components/CommentSection.vue';
import Tags from '@/components/Tags.vue';
import UserInfoPostCard from '@/components/UserInfoPostCard.vue';
import Voting from '@/components/Voting.vue';
import hljs from 'highlight.js';
import 'highlight.js/styles/atom-one-dark.min.css';
import { onMounted, watch } from 'vue';

// question/answer
const props = defineProps(['post']);

defineEmits(['edit', 'delete']);

onMounted(() => highlightAll());

watch(
    () => props.post.body,
    () => highlightAll(),
);

const highlightAll = () => {
    document.querySelectorAll('pre code:not([data-highlighted])').forEach((el) => {
        hljs.highlightElement(el as HTMLElement);
    });
};
</script>

<template>
    <div class="flex">
        <Voting :post="post" class="pr-6 pl-2 border-l-2" />

        <div class="w-full">
            <article class="!prose mb-4 !max-w-none break-after-all dark:!prose-invert prose-pre:max-h-[800px]" v-html="post.body" />

            <Tags :question="post" />

            <div class="mt-4 flex justify-between">
                <div class="flex space-x-2 text-xs">
                    <form v-if="post.can?.update" @submit.prevent="$emit('edit', post.id)">
                        <button class="cursor-pointer font-mono text-gray-400 hover:font-bold">EDIT</button>
                    </form>

                    <form v-if="post.can?.delete" @submit.prevent="$emit('delete', post.id)">
                        <button class="cursor-pointer font-mono text-red-700 hover:font-bold">DELETE</button>
                    </form>
                </div>

                <UserInfoPostCard :post="post" />
            </div>

            <CommentSection :commentable-type="post.type" :commentable-id="post.id" :comments-count="post.comments_count" />
        </div>
    </div>
</template>

<style lang="scss" scoped>
:deep(code:not(pre code)) {
    ::before,
    ::after {
        content: none !important;
    }

    background-color: #f5f5f5;
    color: #d6336c;
    padding: 2px 4px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.9em;
}
</style>
