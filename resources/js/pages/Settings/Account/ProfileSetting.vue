<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { getInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { nextTick, ref, useTemplateRef } from 'vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Account Profile',
        href: '/settings/account/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const profileForm = useForm({
    name: user.name ?? '',
    bio: user.bio ?? '',
    job_title: user.job_title ?? '',
    location: user.location ?? '',
    website: user.website ?? '',
    github: user.github ?? '',
    twitter: user.twitter ?? '',
});

const fileInput = useTemplateRef('fileInput');

const preview = ref(user.avatar ? `/storage/${user.avatar}` : '');

const handleFileChange = (e: Event) => {
    const file = (e.target as HTMLInputElement).files?.item(0);
    if (!file) {
        return;
    }

    profileForm.transform((data) => ({ ...data, avatar: file }));

    if (preview.value) {
        URL.revokeObjectURL(preview.value);
    }
    preview.value = URL.createObjectURL(file);
};

const removeAvatar = () => {
    if (preview.value) {
        URL.revokeObjectURL(preview.value);
    }
    preview.value = '';

    profileForm.transform((data) => ({ ...data, avatar: null }));

    nextTick(() => {
        if (fileInput.value) {
            fileInput.value.$el.value = '';
        }
    });
};

const submit = () =>
    profileForm.post(route('account.profile.update', { _method: 'put' }), {
        preserveScroll: true,
    });
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Account Profile" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile Settings" description="Enter you profile information." />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="avatar">Profile Image</Label>

                        <div class="relative">
                            <Avatar class="size-40 overflow-hidden rounded-lg border-4">
                                <AvatarImage :src="preview" :alt="user.username" :key="preview" />
                                <AvatarFallback class="rounded-lg text-black dark:text-white">
                                    {{ getInitials(user.name ?? user.username) }}
                                </AvatarFallback>
                            </Avatar>
                            <Input
                                ref="fileInput"
                                type="file"
                                id="avatar"
                                @change="handleFileChange"
                                class="absolute bottom-0 mt-1 w-40 cursor-pointer rounded-t-none bg-gray-500"
                            />
                        </div>
                        <Button @click="removeAvatar" variant="link" type="button" size="icon" class="cursor-pointer text-blue-500"> Remove </Button>
                        <InputError class="mt-2" :message="profileForm.errors.avatar" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="name">Full Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="profileForm.name" />
                        <InputError class="mt-2" :message="profileForm.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="bio">About Me</Label>
                        <Textarea id="bio" class="mt-1 block w-full" v-model="profileForm.bio" />
                        <InputError class="mt-2" :message="profileForm.errors.bio" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="jobTitle">Job Title</Label>
                        <Input id="jobTitle" class="mt-1 block w-full" v-model="profileForm.job_title" />
                        <InputError class="mt-2" :message="profileForm.errors.job_title" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="location">Location</Label>
                        <Input id="location" class="mt-1 block w-full" v-model="profileForm.location" />
                        <InputError class="mt-2" :message="profileForm.errors.location" />
                    </div>

                    <fieldset class="rounded border p-4">
                        <legend class="text-xs">Links</legend>

                        <div class="flex gap-4">
                            <div>
                                <Label for="website">Website link</Label>
                                <Input id="website" class="mt-1 block w-full" v-model="profileForm.website" />
                                <InputError class="mt-2" :message="profileForm.errors.website" />
                            </div>

                            <div>
                                <Label for="github">GitHub link</Label>
                                <Input id="github" class="mt-1 block w-full" v-model="profileForm.github" />
                                <InputError class="mt-2" :message="profileForm.errors.github" />
                            </div>

                            <div>
                                <Label for="twitter">Twitter link</Label>
                                <Input id="twitter" class="mt-1 block w-full" v-model="profileForm.twitter" />
                                <InputError class="mt-2" :message="profileForm.errors.twitter" />
                            </div>
                        </div>
                    </fieldset>

                    <div class="flex items-center gap-4">
                        <Button :disabled="profileForm.processing">Save</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="profileForm.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
