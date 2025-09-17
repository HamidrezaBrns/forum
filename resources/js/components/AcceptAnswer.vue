<script setup lang="ts">
import { Answer } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    answer: Answer;
}>();

const page = usePage();

const acceptAnswer = () =>
    router.post(
        route('questions.answers.accept', [page.props.question, props.answer]),
        {},
        {
            only: ['question'],
            preserveScroll: true,
            onSuccess: () => {
                if (page.props.question.accepted_answer_id) {
                    toast.success(trans('This answer accepted as correct.'));
                } else {
                    toast.info(trans('Correct answer selection canceled.'));
                }
            },
        },
    );

const canAccept = computed(() => page.props.auth && page.props.question.user?.id === page.props.auth.user?.id);
const isAccepted = computed(() => page.props.question.accepted_answer_id === props.answer.id);
</script>

<template>
    <div>
        <div v-if="canAccept" class="border-t py-4">
            <button
                @click="acceptAnswer"
                class="cursor-pointer rounded-full border px-2 py-1 not-disabled:hover:opacity-70 disabled:cursor-default disabled:bg-gray-100 disabled:text-gray-400"
                :class="{ 'bg-green-300': isAccepted }"
                :title="!answer.can?.accept ? $t('You can\'t select your own answer as accepted.') : $t('Accept')"
                :disabled="!answer.can?.accept"
            >
                <i class="ri-check-line"></i>
            </button>
        </div>

        <div v-else-if="isAccepted" :title="$t('Accepted as the best answer.')" class="text-4xl font-semibold text-green-700">
            <i class="ri-check-line"></i>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
