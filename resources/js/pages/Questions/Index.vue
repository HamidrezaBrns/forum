<script setup lang="ts">
import SimplePagination from '@/components/SimplePagination.vue';
import Tags from '@/components/Tags.vue';
import { Button } from '@/components/ui/button';
import UserInfoCard from '@/components/UserInfoCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

defineProps(['questions', 'tag']);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Questions',
        href: '/questions',
    },
];
</script>

<template>
    <Head title="Questions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="max-w-2/3 px-4 py-6">
            <div v-if="tag" class="border-b px-6 pb-4">
                <h1 class="text-3xl font-bold">[{{ tag.name }}]</h1>

                <p class="mt-2 text-sm">{{ tag.description }}</p>
            </div>

            <div v-else class="flex justify-between border-b px-6 pb-4">
                <h1 class="text-3xl font-medium">{{ questions.meta.total }} Questions</h1>

                <div v-if="$page.props.permissions.create_questions">
                    <Link :href="route('questions.create')">
                        <Button type="button">Ask Question</Button>
                    </Link>
                </div>
            </div>

            <ul class="divide-y">
                <li v-for="question in questions.data" :key="question.id" class="px-6 py-4">
                    <div class="flex">
                        <div class="mr-4 min-w-14 space-y-2 text-right text-xs text-gray-500">
                            <div>{{ question.votes_count }} votes</div>
                            <div>{{ question.answers_count }} answers</div>
                        </div>
                        <div class="w-full">
                            <h3 class="mb-1 break-all text-blue-500">
                                <Link :href="route('questions.show', question.id)">
                                    {{ question.title }}
                                </Link>
                            </h3>

                            <p class="mb-3 text-sm break-all">{{ question.body.substring(0, 170) }}...</p>

                            <div class="flex justify-between gap-2">
                                <Tags :question="question" />

                                <UserInfoCard :post="question" simple-badge />
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <SimplePagination :meta="questions.meta" details :only="['questions']" />
        </div>
    </AppLayout>
</template>
