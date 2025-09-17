<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useDirection } from '@/composables/useDirection';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, MailQuestion, MessageSquareMore, MessagesSquare, Tags, UsersRound } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/admin',
        icon: LayoutGrid,
    },
    {
        title: 'Users',
        href: '/admin/users',
        icon: UsersRound,
    },
    {
        title: 'Tags',
        href: '/admin/tags',
        icon: Tags,
    },
    {
        title: 'Questions',
        href: '/admin/questions',
        icon: MailQuestion,
    },
    {
        title: 'Answers',
        href: '/admin/answers',
        icon: MessageSquareMore,
    },
    {
        title: 'Comments',
        href: '/admin/comments',
        icon: MessagesSquare,
    },
];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Github Repo',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];

const { isRTL } = useDirection();
const page = usePage();
</script>

<template>
    <Sidebar collapsible="icon" variant="sidebar" :side="isRTL ? 'right' : 'left'">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('home')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser v-if="page.props.auth.user" />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
