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
import { formatFull } from '@/utilities/date';
import { Head, useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
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
        onSuccess: () =>
            toast.success(trans('Your question has been published successfully.'), {
                description: formatFull(),
            }),
    });

const saveDraft = () =>
    questionForm.post(route('questions.storeDraft'), {
        onSuccess: () =>
            toast.success(trans('Draft saved.'), {
                description: formatFull(),
            }),
    });
</script>

<template>
    <Head :title="$t('Create Question')" />

    <AppLayout>
        <Container class="lg:max-w-[1000px]">
            <Heading :title="$t('Create Question')" />

            <form @submit.prevent="createQuestion">
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

                <div class="flex gap-3">
                    <Button type="submit" :disabled="questionForm.processing">
                        <!--                        <LoaderCircle v-if="questionForm.processing" class="h-4 w-4 animate-spin" />-->
                        {{ $t('Publish') }}
                    </Button>

                    <Button type="button" variant="outline" :disabled="questionForm.processing" @click="saveDraft">
                        <!--                        <LoaderCircle v-if="questionForm.processing" class="h-4 w-4 animate-spin" />-->
                        {{ $t('Save as Draft') }}
                    </Button>
                </div>
            </form>
        </Container>
    </AppLayout>
</template>
