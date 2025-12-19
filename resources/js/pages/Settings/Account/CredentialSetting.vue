<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { ref } from 'vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Account Settings',
        href: '/settings/account',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const identityForm = useForm({
    username: user.username,
    email: user.email,
});

const updateIdentity = () => {
    identityForm.patch(route('account.identity-update'), {
        preserveScroll: true,
    });
};

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    passwordForm.put(route('account.password-update'), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
        onError: (errors: any) => {
            if (errors.password) {
                passwordForm.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }

            if (errors.current_password) {
                passwordForm.reset('current_password');
                if (currentPasswordInput.value instanceof HTMLInputElement) {
                    currentPasswordInput.value.focus();
                }
            }
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="$t('Account Credentials')" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall :title="$t('Account Credentials')" :description="$t('Update your username, email address ans password.')" />

                <fieldset class="rounded border p-4">
                    <legend class="text-xs">{{ $t('Change email and username') }}</legend>

                    <form @submit.prevent="updateIdentity" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="username">{{ $t('Username') }}</Label>
                            <Input
                                id="username"
                                class="mt-1 block w-full"
                                v-model="identityForm.username"
                                required
                                autocomplete="username"
                                placeholder="Enter username"
                            />
                            <InputError class="mt-2" :message="identityForm.errors.username" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">{{ $t('Email address') }}</Label>
                            <Input
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="identityForm.email"
                                required
                                autocomplete="email"
                                placeholder="Enter email address"
                            />
                            <InputError class="mt-2" :message="identityForm.errors.email" />
                        </div>

                        <div v-if="mustVerifyEmail && !user.email_verified_at">
                            <p class="-mt-4 text-sm text-muted-foreground">
                                {{ $t('Your email address is unverified.') }}
                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                                >
                                    {{ $t('Click here to resend the verification email.') }}
                                </Link>
                            </p>

                            <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                                {{ $t('A new verification link has been sent to your email address.') }}
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <Button :disabled="identityForm.processing">{{ $t('Save') }}</Button>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-show="identityForm.recentlySuccessful" class="text-sm text-neutral-600">
                                    {{ $t('Saved.') }}
                                </p>
                            </Transition>
                        </div>
                    </form>
                </fieldset>

                <fieldset class="rounded border p-4">
                    <legend class="text-xs">{{ $t('Change password') }}</legend>

                    <form @submit.prevent="updatePassword" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="current_password">{{ $t('Current password') }}</Label>
                            <Input
                                id="current_password"
                                ref="currentPasswordInput"
                                v-model="passwordForm.current_password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="current-password"
                                placeholder="Enter your current password"
                            />
                            <InputError :message="passwordForm.errors.current_password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password">{{ $t('New password') }}</Label>
                            <Input
                                id="password"
                                ref="passwordInput"
                                v-model="passwordForm.password"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                                placeholder="Enter new password"
                            />
                            <InputError :message="passwordForm.errors.password" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="password_confirmation">{{ $t('Confirm password') }}</Label>
                            <Input
                                id="password_confirmation"
                                v-model="passwordForm.password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                autocomplete="new-password"
                                placeholder="Confirm your new password"
                            />
                            <InputError :message="passwordForm.errors.password_confirmation" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button :disabled="passwordForm.processing">{{ $t('Save password') }}</Button>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-show="passwordForm.recentlySuccessful" class="text-sm text-neutral-600">
                                    {{ $t('Saved.') }}
                                </p>
                            </Transition>
                        </div>
                    </form>
                </fieldset>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
