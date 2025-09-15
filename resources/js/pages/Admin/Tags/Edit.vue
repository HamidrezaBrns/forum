<script setup lang="ts">
import ContainerAdmin from '@/components/ContainerAdmin.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

const props = defineProps(['tag']);

const tagForm = useForm({
    name: props.tag.name,
    description: props.tag.description,
});

const editTag = () =>
    tagForm.patch(route('admin.tags.update', props.tag.id), {
        onSuccess: () => toast.success(`Tag ${tagForm.name} successfully edited`),
    });

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tags', href: route('admin.tags.index') },
    { title: props.tag.id, href: route('admin.tags.show', props.tag.id) },
    { title: 'Edit', href: route('admin.tags.edit', props.tag.id) },
];
</script>
<template>
    <Head title="Admin - Edit Tag" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <Heading title="Edit Tag" />

            <form @submit.prevent="editTag">
                <div class="mb-4">
                    <Label for="name" class="mb-1">Name</Label>
                    <Input id="name" type="text" autofocus v-model="tagForm.name" />
                    <InputError :message="tagForm.errors.name" class="mt-1" />
                </div>

                <div class="mb-4">
                    <Label for="description" class="mb-1">Description</Label>
                    <Textarea id="description" v-model="tagForm.description" />
                    <InputError :message="tagForm.errors.description" class="mt-1" />
                </div>

                <Button type="submit" :disabled="tagForm.processing">
                    <LoaderCircle v-if="tagForm.processing" class="h-4 w-4 animate-spin" />
                    Edit
                </Button>
            </form>
        </ContainerAdmin>
    </AdminLayout>
</template>
