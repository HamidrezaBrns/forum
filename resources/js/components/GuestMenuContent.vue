<script setup lang="ts">
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { Link } from '@inertiajs/vue3';
import { LogIn, Settings, UserRound } from 'lucide-vue-next';
</script>

<template>
    <DropdownMenuLabel dir="auto">
        <div class="flex py-1.5 font-medium">
            <UserRound class="me-2 size-5" />
            {{ $t('Guest User') }}
        </div>
    </DropdownMenuLabel>
    <DropdownMenuSeparator />
    <DropdownMenuGroup dir="auto">
        <DropdownMenuItem as-child>
            <Link class="block w-full" :href="route('appearance')" prefetch as="button">
                <Settings class="me-2 size-4" />
                {{ $t('Settings') }}
            </Link>
        </DropdownMenuItem>
    </DropdownMenuGroup>
    <DropdownMenuSeparator />
    <DropdownMenuItem dir="auto" as-child>
        <Link class="block w-full" :href="route('login')" as="button">
            <LogIn class="me-2 size-4" />
            {{ $t('Log In') }}
        </Link>
    </DropdownMenuItem>
</template>
