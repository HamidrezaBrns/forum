<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const auth = computed(() => page.props.auth);

const sidebarNavItems: NavItem[] = [
    {
        title: 'Account Credentials',
        href: '/settings/account',
        isActive: !!auth.value.user,
    },
    {
        title: 'Account Profile',
        href: '/settings/account/profile',
        isActive: !!auth.value.user,
    },
    {
        title: 'Appearance',
        href: '/settings/appearance',
        isActive: true,
    },
    {
        title: 'Language',
        href: '/settings/language',
        isActive: true,
    },
];

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="px-4 py-6">
        <Heading :title="$t('Settings')" />

        <div class="flex flex-col lg:flex-row lg:space-x-12">
            <aside class="w-full max-w-xl lg:w-48">
                <nav class="flex flex-col space-y-1 space-x-0">
                    <Button v-if="!auth.user" variant="ghost" :class="['w-full justify-start']" as-child>
                        <Link :href="route('register')">
                            {{ $t('Create Account') }}
                        </Link>
                    </Button>

                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
                        as-child
                    >
                        <Link v-if="item.isActive" :href="item.href">
                            {{ $t(item.title) }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 lg:hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
