<script setup lang="ts">
import SimplePagination from '@/components/SimplePagination.vue';
import Tags from '@/components/Tags.vue';
import UserInfoSimpleCard from '@/components/UserInfoSimpleCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import Container from '@/components/ui/Container.vue';

defineProps(['questions', 'tag']);
</script>

<template>
    <Head title="Questions" />

    <AppLayout>
        <Container>
            <div class="border-b px-6 pb-4">
                <div class="flex justify-between mb-6">
                    <h1 class="text-3xl font-medium">Search Results</h1>

                    <div>
                        <Link :href="route('questions.create')">
                            <Button type="button">Ask Question</Button>
                        </Link>
                    </div>
                </div>

                <div class="text-sm font-medium">{{ questions.meta.total }} results</div>
            </div>

            <ul class="divide-y">
                <li v-for="question in questions.data" :key="question.id" class="px-6 py-4">
                    <div class="flex">
                        <div class="mr-4 min-w-14 space-y-2 text-right text-xs text-gray-500">
                            <div>{{ question.votes_count }} votes</div>
                            <div>{{ question.answers_count }} answers</div>
                            <div>{{ question.views_count }} views</div>
                        </div>
                        <div class="w-full">
                            <h3 class="mb-2 break-all text-blue-500">
                                <Link :href="route('questions.show', question.id)">
                                    {{ question.title }}
                                </Link>
                            </h3>

                            <div class="flex justify-between gap-2">
                                <Tags :tags="question.tags" />

                                <UserInfoSimpleCard :post="question" simple-badge />
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <SimplePagination :meta="questions.meta" details :only="['questions']" />
        </Container>
    </AppLayout>
</template>
