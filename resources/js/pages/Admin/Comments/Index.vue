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
import { Ellipsis, Trash2 } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

defineProps(['comments', 'meta']);

const { confirmation } = useConfirm();

const actionHandler = {
    async delete(id: number) {
        if (!(await confirmation(`Delete comment "${id}"`, 'Delete this comment permanently?'))) {
            return;
        }
        router.delete(route('admin.comments.destroy', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`Comment "${id}" deleted`),
        });
    },
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Comments', href: route('admin.comments.index') },
];
</script>

<template>
    <Head title="Admin - Comments" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <h2 class="mb-2 text-lg font-medium">Comments</h2>

            <div class="mb-2 flex flex-wrap items-center gap-4">
                <SearchRealtime :route="route('admin.comments.index')" placeholder="Search comments..." :only="['comments']" />
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>
                            <SortableColumn sort-key="id" :sort-meta="meta" base-route="admin.comments.index" :only="['comments']">
                                ID
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="body" :sort-meta="meta" base-route="admin.comments.index" :only="['comments']">
                                Body
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="commentable_id" :sort-meta="meta" base-route="admin.comments.index" :only="['comments']">
                                Parent
                            </SortableColumn>
                        </TableHead>
                        <TableHead>User</TableHead>
                        <TableHead>
                            <SortableColumn sort-key="created_at" :sort-meta="meta" base-route="admin.comments.index" :only="['comments']">
                                Created at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="updated_at" :sort-meta="meta" base-route="admin.comments.index" :only="['comments']">
                                Updated at
                            </SortableColumn>
                        </TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="c in comments.data" :key="c.id">
                        <TableCell>{{ c.id }}</TableCell>

                        <TableCell class="max-w-md whitespace-break-spaces">
                            <Link :href="route('admin.comments.show', c.id)" class="line-clamp-2 break-words text-blue-600 hover:underline">
                                {{ c.body }}
                            </Link>
                        </TableCell>

                        <TableCell>
                            <span class="capitalize">{{ c.commentable_type }}</span>
                            {{ ' ' }}
                            <Link :href="route(`admin.${c.commentable_type}s.show`, c.commentable_id)" class="text-blue-600 hover:underline">
                                {{ c.commentable_id }}
                            </Link>
                        </TableCell>

                        <TableCell class="break-words">
                            <Link :href="route('admin.users.show', c.user_id)" class="text-blue-600 hover:underline">
                                {{ c.user.username }}
                            </Link>
                        </TableCell>

                        <TableCell>{{ formatDate(c.created_at) }}</TableCell>
                        <TableCell>{{ formatDate(c.updated_at) }}</TableCell>

                        <!-- Actions -->
                        <TableCell>
                            <DropdownMenu>
                                <DropdownMenuTrigger>
                                    <ShadcnButton size="sm" variant="ghost">
                                        <Ellipsis />
                                    </ShadcnButton>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent>
                                    <DropdownMenuLabel>Actions</DropdownMenuLabel>
                                    <DropdownMenuSeparator />

                                    <DropdownMenuItem as-child>
                                        <button type="button" class="w-full" @click="actionHandler.delete(c.id)">
                                            <Trash2 class="mr-1 size-4" />
                                            Delete
                                        </button>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <SimplePagination :entity="comments" />
        </ContainerAdmin>
    </AdminLayout>
</template>
