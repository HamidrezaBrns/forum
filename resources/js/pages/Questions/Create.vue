<script setup lang="ts">
import Container from '@/components/Container.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import TagSelector from '@/components/TagSelector.vue';
import TiptapEditor from '@/components/TiptapEditor.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    tags: { id: number; name: string }[];
}>();

const questionForm = useForm({
    title: '',
    tags: [] as string[],
    body: '',
});

const tagError = computed(() => {
    if (questionForm.errors.tags) {
        return questionForm.errors.tags;
    }

    const tagKey = Object.keys(questionForm.errors).find((key) => key.startsWith('tags.'));
    return tagKey ? questionForm.errors[tagKey] : null;
});

const createQuestion = () =>
    questionForm.post(route('questions.store'), {
        onSuccess: () => toast.success('Your question created successfully.'),
    });
</script>

<template>
    <Head title="Ask Question" />

    <AppLayout>
        <Container class="lg:max-w-[1000px]">
            <Heading title="Ask a question" />

            <form @submit.prevent="createQuestion">
                <div class="mb-4">
                    <Label for="title" class="mb-1">Title</Label>
                    <Input id="title" type="text" autofocus v-model="questionForm.title" placeholder="Write a clear, concise question..." />
                    <InputError :message="questionForm.errors.title" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label for="tags" class="mb-1">Tags</Label>
                    <TagSelector :available-tags="tags" v-model="questionForm.tags" />
                    <InputError :message="tagError" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label for="body" class="mb-1">Body</Label>
                    <TiptapEditor v-model="questionForm.body" />
                    <InputError :message="questionForm.errors.body" class="mt-1" />
                </div>

                <Button type="submit" :disabled="questionForm.processing">
                    <LoaderCircle v-if="questionForm.processing" class="h-4 w-4 animate-spin" />
                    Post your question
                </Button>
            </form>
        </Container>
    </AppLayout>
</template>
