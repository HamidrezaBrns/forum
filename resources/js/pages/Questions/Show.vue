<script setup lang="ts">
import Container from '@/components/Container.vue';
import DetailedPagination from '@/components/DetailedPagination.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PostField from '@/components/PostField.vue';
import TiptapEditor from '@/components/TiptapEditor.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { useConfirm } from '@/composables/useConfirm';
import AppLayout from '@/layouts/AppLayout.vue';
import { Answer } from '@/types';
import { formatFull } from '@/utilities/date';
import { formatNumber } from '@/utilities/number';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, nextTick, ref, useTemplateRef } from 'vue';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

const props = defineProps(['question', 'answers']);

// Answers CRUD
const answerForm = useForm({
    body: '',
});

const page = usePage();
const answerTextareaRef = useTemplateRef('answerTextarea');
const answerIdBeingEdited = ref<null | number>(null);
const answerBeingEdited = computed(() => props.answers.data.find((answer: { id: number }) => answer.id === answerIdBeingEdited.value));
const editAnswer = (answerId: number) => {
    editingAnswer.value = true;
    answerIdBeingEdited.value = answerId;

    if (answerBeingEdited.value) {
        answerForm.clearErrors();
        answerForm.body = answerBeingEdited.value.body;
    }

    nextTick(() => {
        answerTextareaRef.value?.$el.focus();
        answerTextareaRef.value?.$el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
};

const cancelEditAnswer = () => {
    editingAnswer.value = false;
    answerIdBeingEdited.value = null;
    answerForm.clearErrors();
    answerForm.reset();
};

// User can only add one answer for a question.
const editingAnswer = ref(false);
const userAnswer = computed(() => props.answers.data.find((answer: Answer) => answer.user.id === page.props.auth.user?.id));

const addAnswer = () =>
    answerForm.post(route('questions.answers.store', props.question.id), {
        preserveScroll: true,
        onSuccess: () => {
            answerForm.reset();
            toast.success(trans('Answer posted successfully.'), {
                description: () => formatFull(),
            });
        },
    });

const { confirmation } = useConfirm();

const updateAnswer = async () => {
    if (!(await confirmation(trans('Update Answer'), trans('Are you sure you want to update this answer?')))) {
        // await nextTick(() => {
        //     answerTextareaRef.value?.$el.focus();
        // });

        return;
    }

    answerForm.put(
        route('answers.update', {
            answer: answerIdBeingEdited.value,
            page: props.answers.meta.current_page,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                cancelEditAnswer();
                toast(trans('Your answer was successfully edited.'), {
                    description: formatFull(),
                });
            },
        },
    );
};

const deleteAnswer = async (answerId: number) => {
    if (!(await confirmation(trans('Delete Answer'), trans('Are you sure you want to delete this answer?')))) {
        return;
    }

    router.delete(route('answers.destroy', { answer: answerId, page: props.answers.meta.current_page }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.info(trans('Your answer has been deleted.'));
        },
    });
};

// Question
const editQuestion = (questionId: number) => router.get(route('questions.edit', questionId));

const deleteQuestion = async (questionId: number) => {
    if (!(await confirmation(trans('Delete Question'), trans('Are you sure you want to delete this question?')))) {
        return;
    }

    router.delete(route('questions.destroy', questionId), {
        preserveScroll: true,
        onSuccess: () => {
            toast.info(trans('Question has been deleted.'));
        },
    });
};

const closeQuestion = async (questionId: number) => {
    if (
        !(await confirmation(
            trans('Close Question'),
            trans('Are you sure you want to close this question? Closing a question prevents users from submitting or editing answers.'),
        ))
    ) {
        return;
    }

    router.patch(
        route('questions.close', questionId),
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.info(trans('Question has been closed.'));
            },
        },
    );
};
</script>

<template>
    <Head :title="question.title" />

    <AppLayout>
        <Container class="lg:max-w-[1000px]">
            <!-- question -->
            <div class="border-b-4 border-dashed">
                <h1 class="ms-2 mb-6 text-2xl font-bold break-words">{{ question.title }}</h1>
                <PostField class="mb-4" :post="question" @close="closeQuestion" @edit="editQuestion" @delete="deleteQuestion" />
            </div>

            <Card
                v-if="question.status === 'draft'"
                class="mt-6 bg-yellow-50 font-medium text-amber-800 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-200"
            >
                <CardContent>
                    <i class="ri-alarm-warning-line text-xl"></i>
                    {{ $t('This question is still a draft and only visible to you.') }}
                </CardContent>
            </Card>

            <!-- answers -->
            <div v-else>
                <div class="mt-6">
                    <Heading :title="$t(':count Answers', { count: formatNumber(answers.meta.total) })" class="ms-2" />

                    <div class="divide-y-2">
                        <div v-for="answer in answers.data" :key="answer.id">
                            <PostField ref="answersDiv" :post="answer" class="py-4" @edit="editAnswer" @delete="deleteAnswer" />
                        </div>
                    </div>

                    <DetailedPagination :meta="answers.meta" :only="['answers']" />
                </div>

                <div v-if="page.props.auth.user">
                    <Card
                        v-if="question.status === 'closed'"
                        class="bg-yellow-50 font-medium text-amber-800 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-200"
                    >
                        <CardContent>
                            <i class="ri-alarm-warning-line text-xl"></i>
                            {{ $t('This question has been closed by owner, you can no longer submit or edit your answer.') }}
                        </CardContent>
                    </Card>

                    <Card
                        v-else-if="!question.can?.create_answer && !editingAnswer"
                        class="bg-yellow-50 font-medium text-amber-800 dark:border-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-200"
                    >
                        <CardContent>
                            <i class="ri-alarm-warning-line text-xl"></i>
                            {{ $t('You have already submitted an answer to this question.') }}
                            <Button variant="link" size="icon" @click="editAnswer(userAnswer.id)" class="cursor-pointer ps-1 text-blue-500">
                                {{ $t('Edit') }}
                            </Button>
                        </CardContent>
                    </Card>

                    <form v-else @submit.prevent="() => (answerIdBeingEdited ? updateAnswer() : addAnswer())">
                        <h2 class="mt-8 mb-5 text-lg font-medium"><i class="ri-question-answer-fill"></i> {{ $t('Your Answer') }}</h2>

                        <div class="mb-4">
                            <TiptapEditor ref="answerTextarea" v-model="answerForm.body" editorClass="!min-h-[160px]" />
                            <InputError :message="answerForm.errors.body" />
                        </div>

                        <div class="space-x-2">
                            <Button type="submit" :disabled="answerForm.processing">
                                <LoaderCircle v-if="answerForm.processing" class="h-4 w-4 animate-spin" />
                                {{ answerIdBeingEdited ? $t('Update') : $t('Post') }}
                            </Button>
                            <Button
                                type="button"
                                v-if="answerIdBeingEdited"
                                @click="cancelEditAnswer"
                                variant="outline"
                                :disabled="answerForm.processing"
                            >
                                {{ $t('Cancel') }}
                            </Button>
                        </div>
                    </form>
                </div>

                <div v-else>
                    {{ $t('To post an answer, first') }}
                    <Link :href="route('login')" class="text-blue-500 hover:underline">{{ $t('Log in') }}</Link>
                    .
                </div>
            </div>
        </Container>
    </AppLayout>
</template>
