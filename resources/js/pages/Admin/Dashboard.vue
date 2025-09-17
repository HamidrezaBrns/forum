<script setup lang="ts">
import Chart from '@/components/Chart.vue';
import ContainerAdmin from '@/components/ContainerAdmin.vue';
import DashboardTable from '@/components/DashboardTable.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { formatDate } from '@/utilities/date';
import { formatNumber } from '@/utilities/number';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    stats: {
        usersCount: number;
        questionsCount: number;
        answersCount: number;
        commentsCount: number;
        tagsCount: number;
        votesCount: number;
    };

    trends: {
        questions: { date: string; count: number }[];
        users: { date: string; count: number }[];
        answers: { date: string; count: number }[];
    };

    latest: {
        questions: any[];
        users: any[];
        tags: any[];
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Admin Dashboard', href: '/admin' }];
</script>

<template>
    <Head title="Admin - Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <h3 class="space-x-2 font-bold">
                            <Link :href="route('admin.users.index')" class="underline">{{ $t('Users') }}:</Link>
                            <span class="font-normal">{{ formatNumber(stats.usersCount) }}</span>
                        </h3>

                        <Chart
                            title="New Users"
                            :labels="trends.users.map((u) => formatDate(u.date, { year: 'numeric', month: 'numeric', day: 'numeric' }))"
                            :data="trends.users.map((u) => u.count)"
                            class="p-5"
                        />
                    </div>

                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <h3 class="space-x-2 font-bold">
                            <Link :href="route('admin.questions.index')" class="underline">{{ $t('Questions') }}:</Link>
                            <span class="font-normal">{{ formatNumber(stats.questionsCount, {}) }}</span>
                        </h3>

                        <Chart
                            title="Daily Questions"
                            :labels="trends.questions.map((q) => formatDate(q.date, { year: 'numeric', month: 'numeric', day: 'numeric' }))"
                            :data="trends.questions.map((q) => q.count)"
                            class="p-5"
                        />
                    </div>

                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <h3 class="space-x-2 font-bold">
                            <Link :href="route('admin.answers.index')" class="underline">{{ $t('Answers') }}:</Link>
                            <span class="font-normal">{{ formatNumber(stats.answersCount, {}) }}</span>
                        </h3>

                        <Chart
                            title="Daily Answers"
                            :labels="trends.answers.map((a) => formatDate(a.date, { year: 'numeric', month: 'numeric', day: 'numeric' }))"
                            :data="trends.answers.map((a) => a.count)"
                            class="p-5"
                        />
                    </div>
                </div>

                <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <h3 class="space-x-2 font-bold">
                            <Link :href="route('admin.tags.index')" class="underline">{{ $t('Tags') }}:</Link>
                            <span class="font-normal">{{ formatNumber(stats.tagsCount, {}) }}</span>
                        </h3>
                    </div>

                    <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <h3 class="space-x-2 font-bold">
                            <Link :href="route('admin.comments.index')" class="underline">{{ $t('Comments') }}:</Link>
                            <span class="font-normal">{{ formatNumber(stats.commentsCount, {}) }}</span>
                        </h3>
                    </div>

                    <div class="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <h3 class="font-bold">
                            {{ $t('Votes') }}: <span class="font-normal">{{ formatNumber(stats.votesCount, {}) }}</span>
                        </h3>
                    </div>
                </div>

                <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
                    <DashboardTable
                        :title="$t('Latest Questions')"
                        :columns="['ID', 'Title', 'user', 'Created at']"
                        :rows="
                            latest.questions.map((q) => [
                                { text: q.id },
                                { text: q.title, href: route('questions.show', q.id), clamp: 1 },
                                {
                                    text: q.user?.username ?? $t('[Deleted User]'),
                                    href: q.user?.username ? route('profile.activities', q.user.username) : '',
                                },
                                { text: formatDate(q.created_at) },
                            ])
                        "
                    />
                </div>

                <div class="flex gap-4">
                    <div class="flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <DashboardTable
                            :title="$t('Latest Users')"
                            :columns="['ID', 'Username', 'Email', 'Full Name', 'Created at']"
                            :rows="
                                latest.users.map((u) => [
                                    { text: u.id },
                                    { text: `@${u.username}`, href: route('profile.activities', u.username) },
                                    { text: u.email },
                                    { text: u.name },
                                    { text: formatDate(u.created_at) },
                                ])
                            "
                        />
                    </div>

                    <div class="overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <DashboardTable
                            :title="$t('Latest Tags')"
                            :columns="['ID', 'Name', 'Description', 'Created at']"
                            :rows="
                                latest.tags.map((t) => [
                                    { text: t.id },
                                    { text: '#' + t.name },
                                    { text: t.description, clamp: 1 },
                                    { text: formatDate(t.created_at) },
                                ])
                            "
                        />
                    </div>
                </div>
            </div>
        </ContainerAdmin>
    </AdminLayout>
</template>
