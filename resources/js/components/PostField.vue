<script setup lang="ts">
import Tags from '@/components/Tags.vue';
import UserInfoCard from '@/components/UserInfoCard.vue';
import Vote from '@/components/Vote.vue';
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
    document.querySelectorAll('pre code').forEach((el) => {
        hljs.highlightElement(el as HTMLElement);
    });
};
</script>

<template>
    <div class="flex">
        <Vote :post="post" class="pr-6 pl-2" />

        <div class="w-full">
            <article class="!prose !prose-sm mb-4 !max-w-none break-after-all dark:!prose-invert" v-html="post.body" />

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

                <UserInfoCard :post="post" />
            </div>
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
