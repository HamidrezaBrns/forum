<script setup lang="ts">
import { Question } from '@/types';
import { Eye, MessageSquare, MessageSquareText, Vote } from 'lucide-vue-next';

defineProps<{
    question: Question;
}>();

const formatCounter = (counter: number) =>
    Intl.NumberFormat('en-Us', {
        notation: 'compact',
        maximumFractionDigits: 1,
    }).format(counter);
</script>

<template>
    <div class="flex flex-wrap gap-4">
        <div :title="`Score of ${question.votes_count}`" class="flex items-center gap-1">
            {{ formatCounter(question.votes_count) }}
            <Vote class="size-5 shrink-0" />
        </div>
        <div :title="`${question.views_count} views`" class="flex items-center gap-1">
            {{ formatCounter(question.views_count) }}
            <Eye class="size-5 shrink-0" />
        </div>
        <div
            :title="question.accepted_answer_id ? 'Has an accepted answer' : `${question.answers_count} answers`"
            class="flex items-center gap-1"
            :class="{ 'text-green-700': question.accepted_answer_id }"
        >
            {{ formatCounter(question.answers_count) }}
            <Component :is="question.answers_count ? MessageSquareText : MessageSquare" class="size-5 shrink-0" />
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
