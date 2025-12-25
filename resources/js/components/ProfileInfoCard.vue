<script setup lang="ts">
import ShowUserAvatar from '@/components/ShowUserAvatar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { NavItem, User } from '@/types';
import { formatRelative } from '@/utilities/date';
import { Link, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { Activity, FilePenLine } from 'lucide-vue-next';
import { computed } from 'vue';
import { formatNumber } from '@/utilities/number';

const props = defineProps<{
    user: User;
}>();

const page = usePage();
const authUser = page.props.auth.user;

const sidebarNavItems: NavItem[] = [
    {
        title: trans('Activities'),
        href: `/@${props.user.username}`,
        icon: Activity,
    },
];

const draftsCount = computed(() => formatNumber(page.props.drafts_count));
const isAuthUser = computed(() => props.user?.username === authUser?.username);
const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div>
        <Card>
            <CardHeader>
                <CardTitle class="flex min-w-0 items-center gap-2.5 wrap-anywhere">
                    <ShowUserAvatar :user="user" class="size-[100px]" fallback-class="text-xl font-medium" />
                    <Link :href="route('profile.activities', user)" class="hover:underline">
                        <h2 class="text-xl font-medium">{{ user.username }}</h2>
                    </Link>
                </CardTitle>

                <CardDescription class="min-w-0">
                    <p class="break-words">
                        <template v-if="user.bio">{{ user.bio }}</template>
                        <template v-else-if="isAuthUser">{{ $t('Bio...') }}</template>
                    </p>
                </CardDescription>
            </CardHeader>

            <CardContent class="border-y py-4">
                <dl class="space-y-5">
                    <div v-if="user.location" class="flex items-center justify-between">
                        <dt class="font-medium text-slate-500">
                            <i class="ri-user-location-line"></i>
                            {{ $t('Location') }}:
                        </dt>
                        <dt>{{ user.location }}</dt>
                    </div>
                    <div v-if="user.created_at" class="flex items-center justify-between">
                        <dt class="font-medium text-slate-500"><i class="ri-time-line"></i> {{ $t('Member Since') }}:</dt>
                        <dt>{{ formatRelative(user.created_at) }}</dt>
                    </div>
                    <div v-if="user.job_title" class="flex items-center justify-between">
                        <dt class="font-medium text-slate-500"><i class="ri-briefcase-line"></i> {{ $t('Profession') }}:</dt>
                        <dt>{{ user.job_title }}</dt>
                    </div>
                </dl>
            </CardContent>

            <CardFooter class="flex flex-wrap gap-2">
                <Link v-if="isAuthUser" :href="route('account.profile.edit')" class="min-w-0 flex-1">
                    <Button variant="outline" class="text-md w-full break-words">
                        <i class="ri-edit-2-fill text-xl"></i>
                        {{ $t('Edit Profile') }}
                    </Button>
                </Link>

                <Button v-else variant="outline" class="text-md flex-1">
                    <i class="ri-user-follow-fill text-xl"></i>
                    {{ $t('Follow') }}
                </Button>

                <div class="space-x-2 sm:shrink-0">
                    <a v-if="user.website" :href="user.website" target="_blank">
                        <Button variant="outline" class="size-9" title="Website">
                            <i class="ri-links-line text-xl"></i>
                        </Button>
                    </a>

                    <a v-if="user.twitter" :href="user.twitter" target="_blank">
                        <Button variant="outline" class="size-9" title="X Twitter">
                            <i class="ri-twitter-x-fill text-xl"></i>
                        </Button>
                    </a>

                    <a v-if="user.github" :href="user.github" target="_blank">
                        <Button variant="outline" class="size-9" title="GitHub">
                            <i class="ri-github-fill text-xl"></i>
                        </Button>
                    </a>
                </div>
            </CardFooter>
        </Card>

        <div class="mt-4">
            <nav class="flex space-y-1 space-x-2 lg:flex-col">
                <Button
                    v-for="item in sidebarNavItems"
                    :key="item.href"
                    variant="ghost"
                    :class="[{ 'bg-muted': currentPath === item.href }, 'justify-start lg:w-full']"
                    as-child
                >
                    <Link :href="item.href">
                        <component v-if="item.icon" :is="item.icon" class="h-5 w-5" />
                        <span>{{ $t(item.title) }}</span>
                    </Link>
                </Button>

                <Button
                    v-if="isAuthUser"
                    variant="ghost"
                    :class="[{ 'bg-muted': currentPath === '/questions/drafts' }, 'justify-start lg:w-full']"
                    as-child
                >
                    <Link :href="route('questions.drafts')">
                        <FilePenLine class="h-5 w-5" />
                        <span>{{ $t('Drafts (:total)', { total: draftsCount }) }}</span>
                    </Link>
                </Button>
            </nav>

            <Separator class="my-2 lg:hidden" />
        </div>
    </div>
</template>
