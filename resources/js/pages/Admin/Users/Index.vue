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
import { Ellipsis, Eraser, FileSymlink, RotateCcw, Trash2 } from 'lucide-vue-next';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

defineProps(['users', 'meta']);

const { confirmation } = useConfirm();

//
const actionHandler = {
    async softDelete(id: number) {
        if (!(await confirmation(`Soft Delete User "${id}"`, 'Remove this user from forum?'))) {
            return;
        }
        router.delete(route('admin.users.destroy', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`User "${id}" soft deleted`),
        });
    },

    async forceDelete(id: number) {
        if (!(await confirmation(`Force Delete User "${id}"`, 'Delete this user permanently?'))) {
            return;
        }
        router.delete(route('admin.users.forceDelete', id), {
            preserveScroll: true,
            onSuccess: () => toast.info(`User "${id}" force deleted`),
        });
    },

    restore(id: number) {
        router.patch(
            route('admin.users.restore', id),
            {},
            {
                preserveScroll: true,
                onSuccess: () => toast.success(`User "${id}" restored`),
            },
        );
    },
};

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Users', href: route('admin.users.index') },
];
</script>

<template>
    <Head title="Admin - Users" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <ContainerAdmin>
            <h2 class="mb-2 text-lg font-medium">Users</h2>

            <div class="mb-2 flex flex-wrap items-center gap-4">
                <SearchRealtime
                    :route="route('admin.users.index')"
                    :only="['users']"
                    placeholder="Search users by username, email or full name."
                />
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>
                            <SortableColumn sort-key="id" :sort-meta="meta" base-route="admin.users.index" :only="['users']"> ID </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="username" :sort-meta="meta" base-route="admin.users.index" :only="['users']">
                                Username
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="email" :sort-meta="meta" base-route="admin.users.index" :only="['users']">
                                Email
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="name" :sort-meta="meta" base-route="admin.users.index" :only="['users']">
                                Full Name
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="is_admin" :sort-meta="meta" base-route="admin.users.index" :only="['users']">
                                Is Admin
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="created_at" :sort-meta="meta" base-route="admin.users.index" :only="['users']">
                                Created at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="updated_at" :sort-meta="meta" base-route="admin.users.index" :only="['users']">
                                Updated at
                            </SortableColumn>
                        </TableHead>
                        <TableHead>
                            <SortableColumn sort-key="deleted_at" :sort-meta="meta" base-route="admin.users.index" :only="['users']">
                                Deleted at
                            </SortableColumn>
                        </TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <TableRow v-for="u in users.data" :key="u.id">
                        <TableCell>{{ u.id }}</TableCell>

                        <TableCell>
                            <Link :href="route('admin.questions.show', u.id)" class="text-blue-600 hover:underline">
                                {{ u.username }}
                            </Link>
                        </TableCell>

                        <TableCell>{{ u.email }}</TableCell>
                        <TableCell>{{ u.name }}</TableCell>
                        <TableCell>{{ u.is_admin }}</TableCell>

                        <TableCell>{{ formatDate(u.created_at) }}</TableCell>
                        <TableCell>{{ formatDate(u.updated_at) }}</TableCell>
                        <TableCell>{{ formatDate(u.deleted_at) }}</TableCell>

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

                                    <DropdownMenuItem as-child v-if="!u.deleted_at">
                                        <Link :href="route('profile.activities', u.username)" as="button" class="block w-full">
                                            <FileSymlink class="mr-1 size-4" />
                                            Go to the page
                                        </Link>
                                    </DropdownMenuItem>

                                    <template v-if="!u.is_admin">
                                        <DropdownMenuItem as-child v-if="u.deleted_at">
                                            <button type="button" class="w-full" @click="actionHandler.restore(u.id)">
                                                <RotateCcw class="mr-1 size-4" />
                                                Restore
                                            </button>
                                        </DropdownMenuItem>

                                        <DropdownMenuItem as-child v-else>
                                            <button type="button" class="w-full" @click="actionHandler.softDelete(u.id)">
                                                <Eraser class="mr-1 size-4" />
                                                Soft delete
                                            </button>
                                        </DropdownMenuItem>

                                        <DropdownMenuItem as-child>
                                            <button type="button" class="w-full" @click="actionHandler.forceDelete(u.id)">
                                                <Trash2 class="mr-1 size-4" />
                                                Force delete
                                            </button>
                                        </DropdownMenuItem>
                                    </template>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>

            <SimplePagination :entity="users" />
        </ContainerAdmin>
    </AdminLayout>
</template>
