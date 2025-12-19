<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import type { User } from '@/types';
import { Link, router } from '@inertiajs/vue3';
import { LayoutGrid, LogOut, Settings } from 'lucide-vue-next';

interface Props {
    user: User;
}

const handleLogout = () => {
    router.flushAll();
};

defineProps<Props>();
</script>

<template>
    <DropdownMenuLabel dir="auto" class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-sm rtl:flex-row-reverse">
            <UserInfo :user="user" show-email />
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup dir="auto">
        <DropdownMenuItem as-child>
            <Link class="block w-full" :href="route('profile.activities', user.username)" prefetch as="button">
                <LayoutGrid class="me-2 size-4" />
                {{ $t('Profile') }}
            </Link>
        </DropdownMenuItem>
        <DropdownMenuItem as-child>
            <Link class="block w-full" :href="route('account.edit')" prefetch as="button">
                <Settings class="me-2 size-4" />
                {{ $t('Settings') }}
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem dir="auto" as-child>
        <Link class="block w-full" method="post" :href="route('logout')" @click="handleLogout" as="button">
            <LogOut class="me-2 size-4" />
            {{ $t('Log out') }}
        </Link>
    </DropdownMenuItem>
</template>
