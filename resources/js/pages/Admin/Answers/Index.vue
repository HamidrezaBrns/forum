<script setup lang="ts">
import ContainerAdmin from '@/components/ContainerAdmin.vue';
import SearchRealtime from '@/components/SearchRealtime.vue';
import SimplePagination from '@/components/SimplePagination.vue';
import SortableColumn from '@/components/SortableColumn.vue';
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
import { Ellipsis, Eraser, FileSymlink, PencilLine, RotateCcw, Trash2 } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

defineProps(['answers', 'meta']);

const { confirmation } = useConfirm();

//
const actionHandler = {
    async softDelete(id: number) {
        if (!(await confirmation(`Soft Delete Answer "${id}"`, 'Remove this answer from forum?'))) {
            return;
        }
        router.delete(route('admin.answers.destroy', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`Answer "${id}" soft deleted`),
        });
    },

    async forceDelete(id: number) {
        if (!(await confirmation(`Force Delete Answer "${id}"`, 'Delete this answer permanently?'))) {
            return;
        }
        router.delete(route('admin.answers.forceDelete', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`Answer "${id}" force deleted`),
        });
    },

    restore(id: number) {
        router.patch(
            route('admin.answers.restore', id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => toast.success(`Answer ${id} restored`),
            },
        );
    },
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Answers', href: route('admin.answers.index') },
];
</script>

<template>
    <Head title="Admin - Answers" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <h2 class="mb-2 text-lg font-medium">{{ $t('Answers') }}</h2>

            <div class="mb-2 flex flex-wrap items-center gap-4">
                <SearchRealtime :route="route('admin.answers.index')" :placeholder="$t('Search answers by body or user...')" :only="['answers']" />
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>
                            <SortableColumn sort-key="id" :sort-meta="meta" base-route="admin.answers.index" :only="['answers']"> ID </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="body" :sort-meta="meta" base-route="admin.answers.index" :only="['answers']">
                                Body
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="question_id" :sort-meta="meta" base-route="admin.answers.index" :only="['answers']">
                                Question
                            </SortableColumn>
                        </TableHead>
                        <TableHead>User</TableHead>
                        <TableHead>
                            <SortableColumn sort-key="created_at" :sort-meta="meta" base-route="admin.answers.index" :only="['answers']">
                                Created at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="updated_at" :sort-meta="meta" base-route="admin.answers.index" :only="['answers']">
                                Updated at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="deleted_at" :sort-meta="meta" base-route="admin.answers.index" :only="['answers']">
                                Deleted at
                            </SortableColumn>
                        </TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="a in answers.data" :key="a.id">
                        <TableCell>{{ a.id }}</TableCell>

                        <TableCell class="max-w-sm whitespace-break-spaces">
                            <Link :href="route('admin.answers.show', a.id)" class="line-clamp-2 break-words text-blue-600 hover:underline">
                                {{ a.body }}
                            </Link>
                        </TableCell>

                        <TableCell>
                            <Link :href="route('admin.questions.show', a.question_id)" class="text-blue-600 hover:underline">
                                {{ a.question_id }}
                            </Link>
                        </TableCell>
                        <TableCell class="break-words">
                            <Link :href="route('admin.users.show', a.user_id)" class="text-blue-600 hover:underline">
                                {{ a.user?.username ?? $t('[Deleted User]') }}
                            </Link>
                        </TableCell>

                        <TableCell>{{ formatDate(a.created_at) }}</TableCell>
                        <TableCell>{{ formatDate(a.updated_at) }}</TableCell>
                        <TableCell>{{ formatDate(a.deleted_at) }}</TableCell>

                        <!-- Actions -->
                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger>
                                    <ShadcnButton size="sm" variant="ghost">
                                        <Ellipsis />
                                    </ShadcnButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuLabel class="in-rtl:text-right">{{ $t('Actions') }}</DropdownMenuLabel>
                                    <DropdownMenuSeparator />

                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="route('questions.show', a.question_id)"
                                            as="button"
                                            class="block w-full flex-row-reverse disabled:opacity-50"
                                            :disabled="a.deleted_at || !a.question"
                                            :title="a.deleted_at ? $t('Answer has been deleted') : !a.question ? $t('Question has been deleted') : ''"
                                        >
                                            <FileSymlink class="me-1 size-4" />
                                            {{ $t('Go to the page') }}
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child>
                                        <Link :href="route('admin.answers.edit', a.id)" as="button" class="block w-full flex-row-reverse">
                                            <PencilLine class="me-1 size-4" />
                                            {{ $t('Edit') }}
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child v-if="a.deleted_at">
                                        <button type="button" class="w-full flex-row-reverse" @click="actionHandler.restore(a.id)">
                                            <RotateCcw class="me-1 size-4" />
                                            {{ $t('Restore') }}
                                        </button>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child v-else>
                                        <button type="button" class="w-full flex-row-reverse" @click="actionHandler.softDelete(a.id)">
                                            <Eraser class="me-1 size-4" />
                                            {{ $t('Soft delete') }}
                                        </button>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child>
                                        <button type="button" class="w-full flex-row-reverse" @click="actionHandler.forceDelete(a.id)">
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

            <SimplePagination :entity="answers" />
        </ContainerAdmin>
    </AdminLayout>
</template>
