<script setup lang="ts">
// import Breadcrumbs from '@/components/Breadcrumbs.vue';
// import type { BreadcrumbItemType } from '@/types';
import { Input } from '@/components/ui/input';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useForm, usePage } from '@inertiajs/vue3';
import { Search, X } from 'lucide-vue-next';

// withDefaults(
//     defineProps<{
//         breadcrumbs?: BreadcrumbItemType[];
//     }>(),
//     {
//         breadcrumbs: () => [],
//     },
// );

const page = usePage();

const searchForm = useForm({
    q: page.props.query ?? '',
    page: 1,
});

const search = () => searchForm.get(route('search'));

const clearSearch = () => {
    searchForm.q = '';
    search();
};
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <!--            <template v-if="breadcrumbs && breadcrumbs.length > 0">-->
            <!--                <Breadcrumbs :breadcrumbs="breadcrumbs" />-->
            <!--            </template>-->
        </div>

        <template v-if="true">
            <form @submit.prevent="search">
                <div class="relative w-full max-w-sm items-center">
                    <Input id="search" type="text" placeholder="Search..." class="pr-5 pl-10" v-model="searchForm.q" />
                    <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                        <button type="submit" title="search">
                            <Search class="size-5 cursor-pointer text-muted-foreground hover:text-slate-400" />
                        </button>
                    </span>
                    <span v-if="searchForm.q" class="absolute inset-y-0 end-0 flex items-center justify-center px-1">
                        <button type="button" title="clear" @click="clearSearch">
                            <X class="size-4 cursor-pointer text-muted-foreground hover:text-red-700" />
                        </button>
                    </span>
                </div>
            </form>
        </template>
    </header>
</template>
