<script setup lang="ts">
import ShowUserAvatar from '@/components/ShowUserAvatar.vue';
import SimplePagination from '@/components/SimplePagination.vue';
import Tags from '@/components/Tags.vue';
import { Button } from '@/components/ui/button';
import Container from '@/components/ui/Container.vue';
import UserInfoSimpleCard from '@/components/UserInfoSimpleCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Eye, MessageSquareQuote, Vote } from 'lucide-vue-next';

defineProps({
    questions: {
        type: Object,
        required: true,
    },

    tag: Object,
});
</script>

<template>
    <Head title="Questions" />

    <AppLayout>
        <Container>
            <div v-if="tag" class="border-b px-6 pb-4">
                <h1 class="mb-2 text-3xl font-bold">
                    [{{ tag.name }}] <span class="text-sm font-medium">{{ questions.meta.total }} Questions</span>
                </h1>

                <p class="text-sm">{{ tag.description }}</p>
            </div>

            <div v-else class="flex justify-between border-b px-6 pb-4">
                <h1 class="text-3xl font-medium">{{ questions.meta.total }} Questions</h1>

                <div>
                    <Link :href="route('questions.create')">
                        <Button type="button">Ask Question</Button>
                    </Link>
                </div>
            </div>

            <ul class="divide-y">
                <li v-for="question in questions.data" :key="question.id" class="px-6 py-4">
                    <div class="flex">
                        <ShowUserAvatar :entity="question" class="mr-2" />

                        <div class="w-full">
                            <div class="flex justify-between">
                                <h3 class="mb-1 w-2xl pr-4 leading-5">
                                    <Link :href="route('questions.show', question.id)" class="font-medium text-blue-500 hover:text-blue-600/85">
                                        {{ question.title }}
                                    </Link>
                                </h3>

                                <div class="flex space-x-6 text-sm text-gray-500">
                                    <div class="flex gap-0.5" title="Votes">
                                        <span>{{ question.votes_count }}</span>
                                        <Vote class="size-5" />
                                    </div>
                                    <div class="flex gap-0.5" title="Views">
                                        <span>{{ question.views_count }}</span>
                                        <Eye class="size-5" />
                                    </div>
                                    <div class="flex gap-0.5" title="Answers">
                                        <span>{{ question.answers_count }}</span>
                                        <MessageSquareQuote class="size-5" />
                                    </div>
                                </div>
                            </div>

                            <p class="mb-3 text-sm break-words">{{ question.body.substring(0, 170) }}...</p>

                            <div class="flex items-center justify-between gap-2">
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
