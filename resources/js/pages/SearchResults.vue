<script setup lang="ts">
import SimplePagination from '@/components/SimplePagination.vue';
import Tags from '@/components/Tags.vue';
import { Button } from '@/components/ui/button';
import Container from '@/components/ui/Container.vue';
import UserInfoSimpleCard from '@/components/UserInfoSimpleCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps(['questions', 'tag']);
</script>

<template>
    <Head title="Questions" />

    <AppLayout>
        <Container>
            <div class="border-b px-6 pb-4">
                <div class="mb-6 flex flex-wrap justify-between">
                    <h1 class="text-3xl font-medium">Search Results</h1>

                    <Link :href="route('questions.create')">
                        <Button variant="outline" type="button">Ask Question</Button>
                    </Link>
                </div>

                <div class="text-sm font-medium">{{ questions.meta.total }} results</div>
            </div>

            <ul class="divide-y">
                <li v-for="question in questions.data" :key="question.id" class="px-6 py-4">
                    <div class="flex">
                        <div class="mr-4 shrink-0 space-y-1 text-right text-xs text-gray-500">
                            <div>{{ question.votes_count }} votes</div>
                            <div>{{ question.answers_count }} answers</div>
                            <div>{{ question.views_count }} views</div>
                        </div>

                        <div class="flex min-w-0 flex-1 flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 sm:pr-10">
                                <Link :href="route('questions.show', question.id)" class="group" :title="question.title">
                                    <h3
                                        class="mb-1 line-clamp-2 text-base leading-5 font-medium break-words group-hover:underline sm:line-clamp-1 sm:text-lg"
                                    >
                                        {{ question.title }}
                                    </h3>

                                    <p
                                        class="line-clamp-2 text-sm break-words text-gray-500 sm:line-clamp-1 dark:text-gray-400"
                                        v-html="question.body"
                                    ></p>
                                </Link>

                                <Tags :tags="question.tags" class="mt-2 sm:mt-3" />
                            </div>

                            <div class="flex flex-shrink-0 items-start gap-2 text-sm text-gray-500 sm:items-end">
                                <UserInfoSimpleCard :post="question" />
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <SimplePagination :meta="questions.meta" details :only="['questions']" />
        </Container>
    </AppLayout>
</template>
