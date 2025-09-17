<script setup lang="ts">
import Container from '@/components/Container.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import TagSelector from '@/components/TagSelector.vue';
import TiptapEditor from '@/components/TiptapEditor.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useConfirm } from '@/composables/useConfirm';
import AppLayout from '@/layouts/AppLayout.vue';
import { Question } from '@/types';
import { formatFull } from '@/utilities/date';
import { Head, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, ref, watchEffect } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    question: Question;
    tags: { id: number; name: string }[];
}>();

const questionForm = useForm({
    title: props.question.title,
    tags: props.question.tags as string[],
    body: props.question.body,
});

const tagError = computed(() => {
    if (questionForm.errors.tags) {
        return questionForm.errors.tags;
    }

    const tagKey = Object.keys(questionForm.errors).find((key) => key.startsWith('tags.'));
    return tagKey ? questionForm.errors[tagKey] : null;
});

const isDirty = ref();
watchEffect(() => {
    isDirty.value = questionForm.isDirty;
});

const discardEditQuestion = async () => {
    if (isDirty.value) {
        if (!(await confirmation('Discard', 'Ignore all changes?'))) {
            return;
        }
    }

    questionForm.reset();
};

const { confirmation } = useConfirm();
const updateQuestion = async () => {
    if (!(await confirmation('Update Question', 'Are you sure you want to update this question?'))) {
        return;
    }

    questionForm.put(route('questions.update', props.question.id), {
        onSuccess: () => {
            toast.success(trans('Your question edited successfully.'), {
                description: formatFull(),
            });
        },
    });
};
</script>
<template>
    <Head :title="$t('Edit Question')" />

    <AppLayout>
        <Container class="lg:max-w-[1000px]">
            <Heading :title="$t('Edit Question')" />

            <form @submit.prevent="updateQuestion">
                <div class="mb-4">
                    <Label for="title" class="mb-1">{{ $t('Title') }}</Label>
                    <Input
                        id="title"
                        type="text"
                        autofocus
                        v-model="questionForm.title"
                        :placeholder="$t('Write a clear, concise title for question...')"
                    />
                    <InputError :message="questionForm.errors.title" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label for="tags" class="mb-1">{{ $t('Tag') }}</Label>
                    <TagSelector :available-tags="tags" v-model="questionForm.tags" :placeholder="$t('Up to 5 tags - e.g. php, laravel...')" />
                    <InputError :message="tagError" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label for="body" class="mb-1">{{ $t('Body') }}</Label>
                    <TiptapEditor v-model="questionForm.body" />
                    <InputError :message="questionForm.errors.body" class="mt-1" />
                </div>

                <Button type="submit" :disabled="questionForm.processing">
                    <LoaderCircle v-if="questionForm.processing" class="h-4 w-4 animate-spin" />
                    {{ $t('Update') }}
                </Button>
                <Button type="button" @click="discardEditQuestion" variant="outline" class="ms-2" :disabled="questionForm.processing || !isDirty">
                    {{ $t('Discard') }}
                </Button>
            </form>
        </Container>
    </AppLayout>
</template>
