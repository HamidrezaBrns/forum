<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PostField from '@/components/PostField.vue';
import SimplePagination from '@/components/SimplePagination.vue';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import { useConfirm } from '@/composables/useConfirm';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { formattedDate } from '@/utilities/date';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, nextTick, ref, useTemplateRef } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps(['question', 'answers']);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '',
        href: '',
    },
];

const answerForm = useForm({
    body: '',
});

const answerTextareaRef = useTemplateRef('answerTextarea');
const answerIdBeingEdited = ref<null | number>(null);
const answerBeingEdited = computed(() => props.answers.data.find((answer: { id: number }) => answer.id === answerIdBeingEdited.value));
const editAnswer = (answerId: number) => {
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
    answerIdBeingEdited.value = null;
    answerForm.clearErrors();
    answerForm.reset();
};

const addAnswer = () =>
    answerForm.post(route('questions.answers.store', props.question.id), {
        preserveScroll: true,
        onSuccess: () => {
            answerForm.reset();
            toast('Answer successfully created.', {
                description: formattedDate,
            });
        },
    });

const { confirmation } = useConfirm();

const updateAnswer = async () => {
    if (!(await confirmation('Update Answer', 'Are you sure you want to update this answer?'))) {
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
                toast('Your answer successfully edited.', {
                    description: formattedDate,
                });
            },
        },
    );
};

const deleteAnswer = async (answerId: number) => {
    if (!(await confirmation('Delete Answer', 'Are you sure you want to delete this answer?'))) {
        return;
    }

    router.delete(route('answers.destroy', { answer: answerId, page: props.answers.meta.current_page }), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Answer has been deleted.');
        },
    });
};

// Question
const editQuestion = (questionId: number) => router.get(route('questions.edit', questionId));

const deleteQuestion = async (questionId: number) => {
    if (!(await confirmation('Delete Question', 'Are you sure you want to delete this Question?'))) {
        return;
    }

    router.delete(route('questions.destroy', questionId), {
        preserveScroll: true,
        onSuccess: () => {
            toast('Question has been deleted.');
        },
    });
};
</script>

<template>
    <Head :title="question.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mb-40 max-w-2/3 p-4">
            <!-- question -->
            <div>
                <h1 class="mb-2 text-2xl font-bold">{{ question.title }}</h1>
                <PostField :post="question" @edit="editQuestion" @delete="deleteQuestion" />
            </div>

            <!-- answers -->
            <div>
                <div class="mt-10">
                    <Heading
                        :title="`${answers.meta.total} ${answers.meta.total > 1 ? 'Answers' : 'Answer'}`"
                        description="Answers are ordered by votes."
                    />

                    <div class="divide-y">
                        <div v-for="answer in answers.data" :key="answer.id">
                            <PostField :post="answer" class="py-4" @edit="editAnswer" @delete="deleteAnswer" />
                        </div>
                    </div>

                    <SimplePagination :meta="answers.meta" :only="['answers']" />
                </div>

                <form v-if="$page.props.auth.user" @submit.prevent="() => (answerIdBeingEdited ? updateAnswer() : addAnswer())">
                    <h2 class="mb-5 text-xl">Your Answer</h2>

                    <div class="mb-4">
                        <Textarea ref="answerTextarea" v-model="answerForm.body" placeholder="Here's what I think..." />
                        <InputError :message="answerForm.errors.body" />
                    </div>

                    <Button type="submit" :disabled="answerForm.processing">
                        <LoaderCircle v-if="answerForm.processing" class="h-4 w-4 animate-spin" />
                        {{ answerIdBeingEdited ? 'Update Answer' : 'Post Answer' }}
                    </Button>
                    <Button
                        type="button"
                        v-if="answerIdBeingEdited"
                        @click="cancelEditAnswer"
                        variant="outline"
                        class="ml-2"
                        :disabled="answerForm.processing"
                    >
                        Cancel
                    </Button>
                </form>
                <div v-else>
                    <Link :href="route('login')" class="text-blue-500">Log in</Link>
                    , for posting your answer...
                </div>
            </div>
        </div>
    </AppLayout>
</template>
