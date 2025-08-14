<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { useConfirm } from '@/composables/useConfirm';
import AppLayout from '@/layouts/AppLayout.vue';
import { formattedDate } from '@/utilities/date';
import { Head, router, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const props = defineProps(['question']);

const questionForm = useForm({
    title: props.question.title,
    tags: props.question.tags.map((tag: { name: string }) => tag.name).join(' '),
    body: props.question.body,
});

const discardEditQuestion = async () => {
    if (questionForm.title !== props.question.title || questionForm.body !== props.question.body) {
        if (!(await confirmation('Discard', 'Are you sure you want to ignore all changes?'))) {
            return;
        }
    }

    router.get(route('questions.show', props.question.id));
};

const { confirmation } = useConfirm();
const updateQuestion = async () => {
    if (!(await confirmation('Update Question?', 'Are you sure you want to update this question?'))) {
        return;
    }

    questionForm.put(route('questions.update', props.question.id), {
        onError: () => console.log(questionForm.errors),
        onSuccess: () => {
            toast('Your question successfully edited.', {
                description: formattedDate,
            });
        },
    });
};
</script>
<template>
    <Head title="Edit Question" />

    <AppLayout>
        <div class="mb-40 max-w-2/3 p-4">
            <Heading title="Edit question" />

            <form @submit.prevent="updateQuestion">
                <div class="">
                    <div class="mb-4">
                        <Label for="title">Title</Label>
                        <Input id="title" type="text" autofocus v-model="questionForm.title" placeholder="Get to the point..." />
                        <InputError :message="questionForm.errors.title" class="mt-1" />
                    </div>

                    <div class="mb-4">
                        <Label for="tags" class="mb-1">Tags</Label>
                        <Input id="tags" type="text" autofocus v-model="questionForm.tags" placeholder="e.g. (php laravel linux)" />
                        <InputError :message="questionForm.errors.tags" class="mt-1" />
                        <div v-for="(message, key) in questionForm.errors" :key="key">
                            <InputError v-if="key.startsWith('tags.')" :message="message" class="mt-1" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <Label for="body">Body</Label>
                        <Textarea id="body" v-model="questionForm.body" placeholder="" />
                        <InputError :message="questionForm.errors.body" class="mt-1" />
                    </div>

                    <Button type="submit" :disabled="questionForm.processing">
                        <LoaderCircle v-if="questionForm.processing" class="h-4 w-4 animate-spin" />
                        Update Question
                    </Button>
                    <Button type="button" @click="discardEditQuestion" variant="outline" class="ml-2" :disabled="questionForm.processing">
                        Discard
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
