<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PostField from '@/components/PostField.vue';
import SimplePagination from '@/components/SimplePagination.vue';
import TiptapEditor from '@/components/TiptapEditor.vue';
import { Button } from '@/components/ui/button';
import Container from '@/components/ui/Container.vue';
import { useConfirm } from '@/composables/useConfirm';
import AppLayout from '@/layouts/AppLayout.vue';
import { Answer } from '@/types';
import { formatFull } from '@/utilities/date';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, nextTick, ref, useTemplateRef } from 'vue';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

const props = defineProps(['question', 'answers']);

// Answers CRUD
const answerForm = useForm({
    body: '',
});

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
const userAnswer = computed(() => props.answers.data.find((answer: Answer) => answer.user.id === usePage().props.auth.user?.id));

const addAnswer = () =>
    answerForm.post(route('questions.answers.store', props.question.id), {
        preserveScroll: true,
        onSuccess: () => {
            answerForm.reset();
            toast.success('Answer successfully created.', {
                description: () => formatFull(),
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
                    description: formatFull(),
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
    if (!(await confirmation('Delete Question', 'Are you sure you want to delete this question?'))) {
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

    <AppLayout>
        <Container class="lg:max-w-[1000px]">
            <!-- question -->
            <div class="border-b-4 border-dashed">
                <h1 class="mb-6 ml-2 text-2xl font-bold break-words">{{ question.title }}</h1>
                <PostField class="mb-4" :post="question" @edit="editQuestion" @delete="deleteQuestion" />
            </div>

            <!-- answers -->
            <div>
                <div class="mt-6">
                    <Heading
                        :title="`${answers.meta.total} ${answers.meta.total > 1 ? 'Answers' : 'Answer'}`"
                        description="Your answers are sorted first by highest score, then by posting time."
                        class="ml-2"
                    />

                    <div class="divide-y-2">
                        <div v-for="answer in answers.data" :key="answer.id">
                            <PostField ref="answersDiv" :post="answer" class="py-4" @edit="editAnswer" @delete="deleteAnswer" />
                        </div>
                    </div>

                    <SimplePagination :meta="answers.meta" :only="['answers']" />
                </div>

                <div v-if="$page.props.auth.user">
                    <div v-if="!question.can?.create_answer && !editingAnswer" class="mt-6 rounded bg-amber-100 px-2 text-amber-800">
                        You have already submitted an answer to this question. If you want to change it, you can
                        <Button variant="link" size="icon" @click="editAnswer(userAnswer.id)" class="cursor-pointer text-blue-500">edit </Button>
                        it.
                    </div>

                    <form v-else @submit.prevent="() => (answerIdBeingEdited ? updateAnswer() : addAnswer())">
                        <h2 class="mt-8 mb-5 text-lg font-medium"><i class="ri-question-answer-fill"></i> Your Answer</h2>

                        <div class="mb-4">
                            <TiptapEditor
                                ref="answerTextarea"
                                v-model="answerForm.body"
                                placeholder="Here's what I think..."
                                editorClass="!min-h-[160px]"
                            />
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
                </div>

                <div v-else>
                    <Link :href="route('login')" class="text-blue-500 hover:underline">Log in</Link>
                    , for posting your answer...
                </div>
            </div>
        </Container>
    </AppLayout>
</template>
