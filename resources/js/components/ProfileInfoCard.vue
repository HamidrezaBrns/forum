<script setup lang="ts">
import ShowUserAvatar from '@/components/ShowUserAvatar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { User } from '@/types';
import { formatRelative } from '@/utilities/date';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    user: User;
}>();

const page = usePage();
const authUser = page.props.auth.user;

const isAuthUser = computed(() => props.user?.username === authUser?.username);
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle class="flex min-w-0 items-center gap-5 wrap-anywhere">
                <ShowUserAvatar :user="user" class="size-[100px]" fallback-class="text-xl font-medium" />
                <h2 class="text-xl font-medium">{{ user.username }}</h2>
            </CardTitle>

            <CardDescription class="min-w-0">
                <p v-if="user.bio" class="break-words">{{ user.bio }}</p>
                <p v-else-if="isAuthUser" class="break-words">
                    This is your optional bio. Right now, only you can see it. You can edit it by clicking the EDIT button underneath your avatar and
                    it will display here for all to see.
                </p>
            </CardDescription>
        </CardHeader>

        <CardContent class="border-y py-4">
            <dl class="space-y-5">
                <div v-if="user.location" class="flex items-center justify-between">
                    <dt class="font-medium text-slate-500">
                        <i class="ri-user-location-line"></i>
                        Location
                    </dt>
                    <dt>{{ user.location }}</dt>
                </div>
                <div v-if="user.created_at" class="flex items-center justify-between">
                    <dt class="font-medium text-slate-500"><i class="ri-time-line"></i> Member Since</dt>
                    <dt>{{ formatRelative(user.created_at) }}</dt>
                </div>
                <div v-if="user.job_title" class="flex items-center justify-between">
                    <dt class="font-medium text-slate-500"><i class="ri-briefcase-line"></i> Profession</dt>
                    <dt>{{ user.job_title }}</dt>
                </div>
            </dl>
        </CardContent>

        <CardFooter class="flex flex-wrap gap-2">
            <Link v-if="isAuthUser" :href="route('account.profile.edit')" class="min-w-0 flex-1">
                <Button variant="outline" class="text-md w-full break-words">
                    <i class="ri-edit-2-fill text-xl"></i>
                    Edit Profile
                </Button>
            </Link>

            <Button v-else variant="outline" class="text-md flex-1">
                <i class="ri-user-follow-fill text-xl"></i>
                Follow
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
</template>
