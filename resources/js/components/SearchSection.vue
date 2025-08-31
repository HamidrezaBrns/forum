<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { useForm, usePage } from '@inertiajs/vue3';
import { Search, X } from 'lucide-vue-next';
import { ref } from 'vue';

const page = usePage();

const searchForm = useForm({
    q: page.props.tag ? `[${page.props.tag.name}]` : (page.props.query ?? ''),
    page: 1,
});

const showHelper = ref(false);

const search = () => searchForm.get(route('search'));

const clearSearch = () => {
    searchForm.q = '';
    search();
};

const show = page.component === 'Questions/Index' || page.component === 'SearchResults';
</script>

<template>
    <div v-if="show">
        <form @submit.prevent="search">
            <div class="relative items-center">
                <Input
                    id="search"
                    type="text"
                    autocomplete="off"
                    placeholder="Search..."
                    class="pr-5 pl-10"
                    v-model="searchForm.q"
                    @focus="showHelper = true"
                    @focusout.="showHelper = false"
                />
                <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <button type="submit" title="search" aria-label="Search">
                        <Search class="size-6 cursor-pointer text-muted-foreground hover:text-slate-400" />
                    </button>
                </span>
                <span v-if="searchForm.q" class="absolute inset-y-0 end-0 flex items-center justify-center px-1">
                    <button type="button" title="clear" @click="clearSearch">
                        <X class="size-4 cursor-pointer text-muted-foreground hover:text-red-700" />
                    </button>
                </span>
            </div>
            <!-- helper bar -->
            <div
                v-if="showHelper"
                class="absolute top-full mt-1 w-full rounded-lg border bg-white p-2 text-sm shadow-lg dark:bg-slate-900 dark:text-slate-200"
            >
                <p class="mb-1 font-semibold">Search tips:</p>
                <ul class="list-disc space-y-1 pl-4">
                    <li>Type any <span class="font-mono">keyword</span> to search in questions</li>
                    <li>Use <span class="font-mono">[tag]</span> to search with a tag</li>
                </ul>
            </div>
        </form>
    </div>
</template>

<style scoped lang="scss"></style>
