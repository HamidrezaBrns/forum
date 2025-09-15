<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps(['entity']);

const previousLink = computed(() => props.entity.prev_page_url);
const nextLink = computed(() => props.entity.next_page_url);
</script>

<template>
    <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">
        <div class="text-sm text-gray-700">
            Showing
            {{ ' ' }}
            <span class="font-medium">{{ entity.from }}</span>
            {{ ' ' }}
            to
            {{ ' ' }}
            <span class="font-medium">{{ entity.to }}</span>
            {{ ' ' }}
            of
            {{ ' ' }}
            <span class="font-medium">{{ entity.total }}</span>
            {{ ' ' }}
            results
        </div>
        <div class="flex flex-wrap gap-2">
            <component :is="previousLink ? Link : 'span'" v-bind="previousLink ? { href: previousLink } : {}">
                <Button variant="outline" :disabled="!previousLink">Previous</Button>
            </component>

            <component :is="nextLink ? Link : 'span'" v-bind="nextLink ? { href: nextLink } : {}">
                <Button variant="outline" :disabled="!nextLink">Next</Button>
            </component>
        </div>
    </div>
</template>
