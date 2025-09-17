<script setup lang="ts">
import AcceptAnswer from '@/components/AcceptAnswer.vue';
import CommentSection from '@/components/CommentSection.vue';
import Tags from '@/components/Tags.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import UserInfoPostCard from '@/components/UserInfoPostCard.vue';
import Voting from '@/components/Voting.vue';
import { Answer, Question } from '@/types';
import { formatFull } from '@/utilities/date';
import hljs from 'highlight.js';
import 'highlight.js/styles/atom-one-dark.min.css';
import { Pause, PencilLine, Trash2 } from 'lucide-vue-next';
import { computed, onMounted, watch } from 'vue';

const props = defineProps<{
    post: Question | Answer;
}>();

const question = computed(() => (props.post.type === 'question' ? (props.post as Question) : null));
const answer = computed(() => (props.post.type === 'answer' ? (props.post as Answer) : null));

defineEmits(['close', 'edit', 'delete']);

//
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
        <div class="border-s-2 ps-2 pe-6 text-center text-xl font-medium">
            <Voting :post="post" class="pb-4" />
            <AcceptAnswer v-if="answer" :answer="answer" />
        </div>

        <div class="min-w-0 flex-1">
            <article
                class="!prose mb-4 !max-w-none break-words dark:!prose-invert prose-a:break-words prose-code:break-words prose-pre:max-h-[800px]"
                v-html="post.body"
            />

            <Tags v-if="question" :tags="question.tags" />

            <div class="mt-6 flex justify-between">
                <div class="flex space-x-2">
                    <template v-if="question ? question?.status === 'open' : true">
                        <form v-if="question && question.can?.close" @submit.prevent="$emit('close', post.id)">
                            <Button variant="outline">
                                <Pause />
                                {{ $t('Close') }}
                            </Button>
                        </form>

                        <form v-if="post.can?.update" @submit.prevent="$emit('edit', post.id)">
                            <Button variant="outline">
                                <PencilLine />
                                {{ $t('Edit') }}
                            </Button>
                        </form>

                        <form v-if="post.can?.delete" @submit.prevent="$emit('delete', post.id)">
                            <Button variant="outline">
                                <Trash2 />
                                {{ $t('Delete') }}
                            </Button>
                        </form>
                    </template>

                    <Card v-else-if="question && question.closed_at" class="self-start font-medium text-amber-800">
                        <CardContent>
                            <i class="ri-alarm-warning-line text-xl"></i>
                            {{ $t('Question has been closed on :date.', { date: formatFull(question.closed_at) }) }}
                        </CardContent>
                    </Card>
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
