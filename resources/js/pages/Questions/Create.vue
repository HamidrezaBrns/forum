<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import TiptapEditor from '@/components/TiptapEditor.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Container from '@/components/ui/Container.vue';

const questionForm = useForm({
    title: '',
    tags: '',
    body: '',
});

const createQuestion = () => questionForm.post(route('questions.store'));
</script>
<template>
    <Head title="Ask Question" />

    <AppLayout>
        <Container>
            <Heading title="Ask a question" />

            <form @submit.prevent="createQuestion">
                <div class="mb-4">
                    <Label for="title" class="mb-1">Title</Label>
                    <Input id="title" type="text" autofocus v-model="questionForm.title" placeholder="Get to the point..." />
                    <InputError :message="questionForm.errors.title" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label for="tags" class="mb-1">Tags</Label>
                    <Input id="tags" type="text" autofocus v-model="questionForm.tags" placeholder="e.g. (php laravel linux)" />
                    <InputError :message="questionForm.errors.tags" class="mt-1" />
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
