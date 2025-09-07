<script setup lang="ts">
import AcceptAnswer from '@/components/AcceptAnswer.vue';
import CommentSection from '@/components/CommentSection.vue';
import Tags from '@/components/Tags.vue';
import UserInfoPostCard from '@/components/UserInfoPostCard.vue';
import Voting from '@/components/Voting.vue';
import { Answer, Question } from '@/types';
import hljs from 'highlight.js';
import 'highlight.js/styles/atom-one-dark.min.css';
import { computed, onMounted, watch } from 'vue';

const props = defineProps<{
    post: Question | Answer;
}>();

const question = computed(() => (props.post.type === 'question' ? (props.post as Question) : null));
const answer = computed(() => (props.post.type === 'answer' ? (props.post as Answer) : null));

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
        <div class="border-l-2 pr-6 pl-2 text-center text-xl font-medium">
            <Voting :post="post" class="pb-4" />
            <AcceptAnswer v-if="answer" :answer="answer" />
        </div>

        <div class="min-w-0 flex-1">
            <article
                class="!prose mb-4 !max-w-none break-words dark:!prose-invert prose-a:break-words prose-code:break-words prose-pre:max-h-[800px]"
                v-html="post.body"
            />

            <Tags v-if="question" :tags="question.tags" />

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
:deep(article) {
    direction: initial;
}

:deep([style*='text-align: right']) {
    direction: rtl;
}
</style>
