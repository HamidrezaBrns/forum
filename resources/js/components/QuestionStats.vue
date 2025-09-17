<script setup lang="ts">
import { Question } from '@/types';
import { formatNumber } from '@/utilities/number';
import { Eye, MessageSquare, MessageSquareText, Vote } from 'lucide-vue-next';

defineProps<{
    question: Question;
}>();
</script>

<template>
    <div class="flex flex-wrap gap-4">
        <div :title="$t('Score of :count', { count: question.votes_count })" class="flex items-center gap-1">
            {{ formatNumber(question.votes_count) }}
            <Vote class="size-5 shrink-0" />
        </div>
        <div :title="$t(':count views', { count: question.views_count })" class="flex items-center gap-1">
            {{ formatNumber(question.views_count) }}
            <Eye class="size-5 shrink-0" />
        </div>
        <div
            :title="question.accepted_answer_id ? $t('Has an accepted answer') : $t(':count answers', { count: question.answers_count })"
            class="flex items-center gap-1"
            :class="{ 'text-green-700': question.accepted_answer_id }"
        >
            {{ formatNumber(question.answers_count) }}
            <Component :is="question.answers_count ? MessageSquareText : MessageSquare" class="size-5 shrink-0" />
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
