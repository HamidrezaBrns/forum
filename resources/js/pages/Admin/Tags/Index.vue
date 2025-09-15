<script setup lang="ts">
import ContainerAdmin from '@/components/ContainerAdmin.vue';
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
import { CirclePlus, Ellipsis, PencilLine, Trash2 } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

defineProps(['tags', 'meta']);

const { confirmation } = useConfirm();

//
const actionHandler = {
    async delete(id: number) {
        if (!(await confirmation(`Delete Tag "${id}"`, 'Delete this tag permanently?'))) {
            return;
        }
        router.delete(route('admin.tags.destroy', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`Tag "${id}" deleted`),
        });
    },
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tags', href: route('admin.tags.index') },
];
</script>

<template>
    <Head title="Admin - Tags" />
    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <div class="mb-4 flex flex-wrap items-center justify-between">
                <h2 class="text-lg font-medium">Tags</h2>
            </div>

            <div class="mb-2 flex flex-wrap items-center justify-between gap-4">
                <SearchRealtime :route="route('admin.tags.index')" placeholder="Search tags by name or description" :only="['tags']" />

                <Link :href="route('admin.tags.create')">
                    <ShadcnButton variant="outline">
                        <CirclePlus />
                        Create Tag
                    </ShadcnButton>
                </Link>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>
                            <SortableColumn sort-key="id" :sort-meta="meta" base-route="admin.tags.index" :only="['tags']">ID </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="name" :sort-meta="meta" base-route="admin.tags.index" :only="['tags']">Name </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="description" :sort-meta="meta" base-route="admin.tags.index" :only="['tags']">
                                Description
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="created_at" :sort-meta="meta" base-route="admin.tags.index" :only="['tags']">
                                Created at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="updated_at" :sort-meta="meta" base-route="admin.tags.index" :only="['tags']">
                                Updated at
                            </SortableColumn>
                        </TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="tag in tags.data" :key="tag.id">
                        <TableCell>{{ tag.id }}</TableCell>

                        <TableCell>
                            <Link :href="route('admin.tags.show', tag.id)">
                                <Badge variant="outline" class="font-bold hover:opacity-70">
                                    {{ tag.name }}
                                </Badge>
                            </Link>
                        </TableCell>

                        <TableCell class="max-w-sm whitespace-break-spaces">
                            <div class="line-clamp-2 max-w-lg break-words">
                                {{ tag.description }}
                            </div>
                        </TableCell>

                        <TableCell>{{ formatDate(tag.created_at) }}</TableCell>
                        <TableCell>{{ formatDate(tag.updated_at) }}</TableCell>

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
                                        <Link :href="route('admin.tags.edit', tag.id)" as="button" class="block w-full">
                                            <PencilLine class="mr-1 size-4" />
                                            Edit
                                        </Link>
                                    </DropdownMenuItem>

                                    <DropdownMenuItem as-child>
                                        <button type="button" class="w-full" @click="actionHandler.delete(tag.id)">
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

            <SimplePagination :entity="tags" />
        </ContainerAdmin>
    </AdminLayout>
</template>
