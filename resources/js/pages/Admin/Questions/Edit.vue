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
import AdminLayout from '@/layouts/AdminLayout.vue';
import { BreadcrumbItem, Question } from '@/types';
import { formatFull } from '@/utilities/date';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed, ref, watchEffect } from 'vue';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

const props = defineProps<{
    question: Question;
    tags: { id: number; name: string }[];
}>();

const questionForm = useForm({
    title: props.question.title,
    tags: props.question.tags.map((tag) => tag.name),
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

const { confirmation } = useConfirm();
const discardEditQuestion = async () => {
    if (isDirty.value) {
        if (!(await confirmation('Discard', 'Ignore all changes?'))) {
            return;
        }
    }
    questionForm.reset();
};

const updateQuestion = async () => {
    if (!(await confirmation('Update Question?', 'Are you sure?'))) {
        return;
    }

    questionForm.put(route('admin.questions.update', props.question.id), {
        onSuccess: () => {
            toast(`Question ${props.question.id} edited.`, {
                description: formatFull(),
            });
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Questions', href: route('admin.questions.index') },
    { title: props.question.id, href: route('admin.questions.show', props.question.id) },
    { title: 'Edit', href: route('admin.questions.edit', props.question.id) },
];
</script>
<template>
    <Head title="Admin - Edit Question" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <Container>
            <Heading title="Edit question" />

            <form @submit.prevent="updateQuestion">
                <div class="mb-4">
                    <Label class="mb-1" for="title">Title</Label>
                    <Input id="title" type="text" autofocus v-model="questionForm.title" placeholder="Get to the point..." />
                    <InputError :message="questionForm.errors.title" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label class="mb-1" for="tags">Tags</Label>
                    <TagSelector :available-tags="tags" v-model="questionForm.tags" />
                    <InputError :message="tagError" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label class="mb-1" for="body">Body</Label>
                    <TiptapEditor v-model="questionForm.body" />
                    <InputError :message="questionForm.errors.body" class="mt-1" />
                </div>

                <Button type="submit" :disabled="questionForm.processing">
                    <LoaderCircle v-if="questionForm.processing" class="h-4 w-4 animate-spin" />
                    Update Question
                </Button>
                <Button type="button" @click="discardEditQuestion" variant="outline" class="ml-2" :disabled="questionForm.processing || !isDirty">
                    Discard
                </Button>
            </form>
        </Container>
    </AdminLayout>
</template>
