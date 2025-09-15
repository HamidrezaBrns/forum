<script setup lang="ts">
import Container from '@/components/Container.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import TiptapEditor from '@/components/TiptapEditor.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { useConfirm } from '@/composables/useConfirm';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { formatFull } from '@/utilities/date';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref, watchEffect } from 'vue';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

const props = defineProps(['answer']);

const answerForm = useForm({
    body: props.answer.body,
});

const isDirty = ref();
watchEffect(() => {
    isDirty.value = answerForm.isDirty;
});

const { confirmation } = useConfirm();
const discardEditAnswer = async () => {
    if (isDirty.value) {
        if (!(await confirmation('Discard', 'Ignore all changes?'))) {
            return;
        }
    }
    answerForm.reset();
};

const updateAnswer = async () => {
    if (!(await confirmation('Update Answer', 'Are you sure?'))) {
        return;
    }

    answerForm.put(route('admin.answers.update', props.answer.id), {
        onSuccess: () => {
            toast.success(`Answer ${props.answer.id} edited.`, {
                description: formatFull(),
            });
        },
    });
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Answer', href: route('admin.answers.index') },
    { title: props.answer.id, href: route('admin.answers.show', props.answer.id) },
    { title: 'Edit', href: route('admin.answers.edit', props.answer.id) },
];
</script>
<template>
    <Head title="Admin - Edit Answer" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <Container>
            <Heading title="Edit Answer" />

            <form @submit.prevent="updateAnswer">
                <div class="mb-4">
                    <Label class="mb-1" for="body">Body</Label>
                    <TiptapEditor v-model="answerForm.body" />
                    <InputError :message="answerForm.errors.body" class="mt-1" />
                </div>

                <Button type="submit" :disabled="answerForm.processing">
                    <LoaderCircle v-if="answerForm.processing" class="h-4 w-4 animate-spin" />
                    Update Answer
                </Button>
                <Button type="button" @click="discardEditAnswer" variant="outline" class="ml-2" :disabled="answerForm.processing || !isDirty">
                    Discard
                </Button>
            </form>
        </Container>
    </AdminLayout>
</template>
