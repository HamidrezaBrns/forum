<script setup lang="ts">
import ContainerAdmin from '@/components/ContainerAdmin.vue';
import FilterSelect from '@/components/FilterSelect.vue';
import SearchRealtime from '@/components/SearchRealtime.vue';
import SimplePagination from '@/components/SimplePagination.vue';
import SortableColumn from '@/components/SortableColumn.vue';
import { Badge } from '@/components/ui/badge';
import { Button as ShadcnButton } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useConfirm } from '@/composables/useConfirm';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { formatDate } from '@/utilities/date';
import { Head, Link, router } from '@inertiajs/vue3';
import { Ellipsis, Eraser, FileSymlink, Pause, PencilLine, Play, RotateCcw, Trash2 } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

defineProps(['questions', 'meta']);

const { confirmation } = useConfirm();

const actionHandler = {
    async softDelete(id: number) {
        if (!(await confirmation(`Soft Delete Question "${id}"`, 'Remove this question from forum?'))) {
            return;
        }
        router.delete(route('admin.questions.destroy', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`Question "${id}" soft deleted`),
        });
    },

    async forceDelete(id: number) {
        if (!(await confirmation(`Force Delete Question "${id}"`, 'Delete this question permanently?'))) {
            return;
        }
        router.delete(route('admin.questions.forceDelete', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`Question "${id}" force deleted`),
        });
    },

    restore(id: number) {
        router.patch(
            route('admin.questions.restore', id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => toast.success(`Question ${id} restored`),
            },
        );
    },
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Questions', href: route('admin.questions.index') },
];
</script>

<template>
    <Head title="Admin - Questions" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <h2 class="mb-2 text-lg font-medium">{{ $t('Questions') }}</h2>

            <div class="mb-2 flex flex-wrap items-center gap-4">
                <SearchRealtime
                    :route="route('admin.questions.index')"
                    :placeholder="$t('Search questions by title, body, user or tags...')"
                    :only="['questions']"
                />

                <FilterSelect
                    :route="route('admin.questions.index')"
                    key-name="filter"
                    :label="$t('Filter')"
                    :options="[
                        { value: 'open', label: 'Open' },
                        { value: 'closed', label: 'Closed' },
                        { value: 'draft', label: 'Draft' },
                    ]"
                    :only="['questions']"
                    placeholder="Status"
                />
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>
                            <SortableColumn sort-key="id" :sort-meta="meta" base-route="admin.questions.index" :only="['questions']">
                                ID
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="title" :sort-meta="meta" base-route="admin.questions.index" :only="['questions']">
                                Title
                            </SortableColumn>
                        </TableHead>
                        <TableHead>Tags</TableHead>
                        <TableHead>User</TableHead>
                        <TableHead>Status</TableHead>
                        <TableHead>
                            <SortableColumn sort-key="closed_at" :sort-meta="meta" base-route="admin.questions.index" :only="['questions']">
                                Closed at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="created_at" :sort-meta="meta" base-route="admin.questions.index" :only="['questions']">
                                Created at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="updated_at" :sort-meta="meta" base-route="admin.questions.index" :only="['questions']">
                                Updated at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="deleted_at" :sort-meta="meta" base-route="admin.questions.index" :only="['questions']">
                                Deleted at
                            </SortableColumn>
                        </TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="q in questions.data" :key="q.id">
                        <TableCell>{{ q.id }}</TableCell>

                        <TableCell class="max-w-md whitespace-break-spaces">
                            <Link
                                :href="route('admin.questions.show', q.id)"
                                :title="q.title"
                                class="line-clamp-1 break-words text-blue-600 hover:underline"
                            >
                                {{ q.title }}
                            </Link>
                        </TableCell>

                        <TableCell class="flex flex-wrap gap-1 text-xs text-gray-600 dark:text-gray-400">
                            <Link v-for="tag in q.tags" :key="tag.id" :href="route('admin.tags.show', tag.id)">
                                <Badge variant="outline" class="font-bold hover:opacity-70">
                                    {{ tag.name }}
                                </Badge>
                            </Link>
                        </TableCell>

                        <TableCell class="break-words">
                            <Link :href="route('admin.users.show', q.user_id)" class="text-blue-600 hover:underline">
                                {{ q.user?.username ?? $t('[Deleted User]') }}
                            </Link>
                        </TableCell>

                        <TableCell class="uppercase">{{ q.status }}</TableCell>

                        <TableCell>{{ formatDate(q.closed_at) }}</TableCell>
                        <TableCell>{{ formatDate(q.created_at) }}</TableCell>
                        <TableCell>{{ formatDate(q.updated_at) }}</TableCell>
                        <TableCell>{{ formatDate(q.deleted_at) }}</TableCell>

                        <!-- Actions -->
                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger>
                                    <ShadcnButton size="sm" variant="ghost">
                                        <Ellipsis />
                                    </ShadcnButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent class="in-rtl:text-right">
                                    <DropdownMenuLabel>{{ $t('Actions') }}</DropdownMenuLabel>
                                    <DropdownMenuSeparator />

                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="route('questions.show', q.id)"
                                            as="button"
                                            class="block w-full flex-row-reverse disabled:opacity-50"
                                            :disabled="q.deleted_at"
                                            :title="q.deleted_at ? $t('Question has been deleted') : ''"
                                        >
                                            <FileSymlink class="me-1 size-4" />
                                            {{ $t('Go to the page') }}
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child>
                                        <Link :href="route('admin.questions.edit', q.id)" as="button" class="block w-full flex-row-reverse">
                                            <PencilLine class="me-1 size-4" />
                                            {{ $t('Edit') }}
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child v-if="q.status === 'open'">
                                        <Link
                                            method="patch"
                                            :href="route('admin.questions.close', q.id)"
                                            :on-success="() => toast.success(`Question ${q.id} closed`)"
                                            as="button"
                                            class="block w-full flex-row-reverse disabled:opacity-50"
                                            :disabled="q.deleted_at"
                                            :title="q.deleted_at ? $t('Question has been deleted') : ''"
                                        >
                                            <Pause class="me-1 size-4" />
                                            {{ $t('Close') }}
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child v-else-if="q.closed_at">
                                        <Link
                                            method="patch"
                                            :href="route('admin.questions.reopen', q.id)"
                                            :on-success="() => toast.success(`Question ${q.id} reopened`)"
                                            as="button"
                                            class="block w-full flex-row-reverse disabled:opacity-50"
                                            :disabled="q.deleted_at"
                                            :title="q.deleted_at ? $t('Question has been deleted') : ''"
                                        >
                                            <Play class="me-1 size-4" />
                                            {{ $t('Reopen') }}
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child v-if="q.deleted_at">
                                        <button type="button" class="w-full flex-row-reverse" @click="actionHandler.restore(q.id)">
                                            <RotateCcw class="me-1 size-4" />
                                            {{ $t('Restore') }}
                                        </button>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child v-else>
                                        <button
                                            type="button"
                                            class="w-full flex-row-reverse disabled:opacity-50"
                                            @click="actionHandler.softDelete(q.id)"
                                            :disabled="q.status === 'draft'"
                                            :title="q.status === 'draft' ? $t('Question is in draft form') : ''"
                                        >
                                            <Eraser class="me-1 size-4" />
                                            {{ $t('Soft delete') }}
                                        </button>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child>
                                        <button type="button" class="w-full flex-row-reverse" @click="actionHandler.forceDelete(q.id)">
                                            <Trash2 class="me-1 size-4" />
                                            {{ $t('Force delete') }}
                                        </button>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <SimplePagination :entity="questions" />
        </ContainerAdmin>
    </AdminLayout>
</template>
