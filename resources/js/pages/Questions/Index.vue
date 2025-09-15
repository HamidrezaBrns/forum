<script setup lang="ts">
import Container from '@/components/Container.vue';
import DetailedPagination from '@/components/DetailedPagination.vue';
import QuestionStats from '@/components/QuestionStats.vue';
import ShowUserAvatar from '@/components/ShowUserAvatar.vue';
import SimplePagination from '@/components/SimplePagination.vue';
import Tags from '@/components/Tags.vue';
import { Button } from '@/components/ui/button';
import Container from '@/components/ui/Container.vue';
import UserInfoSimpleCard from '@/components/UserInfoSimpleCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

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
            <div class="border-b px-6 pb-4">
                <template v-if="tag">
                    <h1 class="mb-2 text-3xl font-bold">
                        [{{ tag.name }}] <span class="text-sm font-medium">{{ questions.meta.total }} Questions</span>
                    </h1>

                    <p class="text-sm">{{ tag.description }}</p>
                </template>

                <template v-else>
                    <div class="flex flex-wrap justify-between">
                        <h1 class="text-3xl font-medium">{{ questions.meta.total }} Questions</h1>

                        <Link :href="route('questions.create')">
                            <Button variant="outline" type="button">Ask Question</Button>
                        </Link>
                    </div>
                </template>
            </div>

            <ul class="divide-y">
                <li v-for="question in questions.data" :key="question.id" class="p-4 sm:px-6">
                    <div class="flex items-start gap-3">
                        <component
                            :is="question.user ? Link : 'div'"
                            v-bind="question.user ? { href: route('profile.activities', question.user.username) } : {}"
                        >
                            <ShowUserAvatar :entity="question" class="size-10 flex-shrink-0" />
                        </component>

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

                            <div class="flex flex-shrink-0 flex-col items-start gap-2 text-sm text-gray-500 sm:items-end">
                                <QuestionStats :question="question" />

                                <UserInfoSimpleCard :post="question" />
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <DetailedPagination :meta="questions.meta" details :only="['questions']" />
        </Container>
    </AppLayout>
</template>

<style lang="scss" scoped>
:deep([style*='text-align: right']) {
    direction: rtl;
}
</style>
