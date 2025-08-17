<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps({
    meta: {
        type: Object,
        required: true,
    },
    only: {
        type: Array,
        default: () => [],
    },
    details: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div v-if="meta.links.length > 3" class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <component
                :is="meta.links[0].url ? Link : 'span'"
                :href="meta.links[0].url"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                :only="only"
            >
                Previous
            </component>
            <component
                :is="meta.links[meta.links.length - 1].url ? Link : 'span'"
                :href="meta.links[meta.links.length - 1].url"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                :only="only"
            >
                Next
            </component>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p v-if="details" class="text-sm text-gray-700">
                    Showing
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="font-medium">{{ meta.total }}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                    <component
                        v-for="link in meta.links"
                        :is="link.url ? Link : 'span'"
                        :key="link.label"
                        :href="link.url"
                        :only="only"
                        class="relative inline-flex items-center px-4 py-2 bg-white text-gray-400"
                        :class="{
                            'z-10 !bg-slate-600 text-white focus-visible:outline-2': link.active,
                            'text-gray-900 inset-ring inset-ring-gray-300 hover:bg-gray-50 focus:outline-offset-0': !link.active,
                        }"
                    >
                        <span v-html="link.label"></span>
                    </component>
                </nav>
            </div>
        </div>
    </div>
</template>
