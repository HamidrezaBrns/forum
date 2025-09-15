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

const tagForm = useForm({
    name: '',
    description: '',
});

const createTag = () =>
    tagForm.post(route('admin.tags.store'), {
        onSuccess: () => toast.success(`Tag ${tagForm.name} successfully created`),
    });

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tags', href: route('admin.tags.index') },
    { title: 'Create', href: route('admin.tags.create') },
];
</script>
<template>
    <Head title="Admin - Create Tag" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <Heading title="Create Tag" />

            <form @submit.prevent="createTag">
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
                    Create
                </Button>
            </form>
        </ContainerAdmin>
    </AdminLayout>
</template>
