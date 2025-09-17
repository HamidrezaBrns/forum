<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { formatNumber } from '@/utilities/number';

const props = defineProps(['entity']);

const previousLink = computed(() => props.entity.prev_page_url);
const nextLink = computed(() => props.entity.next_page_url);
</script>

<template>
    <div class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">
        <div class="text-sm text-gray-700">
            {{
                $t('Showing :from to :to of :total results', {
                    from: formatNumber(entity.from),
                    to: formatNumber(entity.to),
                    total: formatNumber(entity.total),
                })
            }}
        </div>
        <div class="flex flex-wrap gap-2">
            <component :is="previousLink ? Link : 'span'" v-bind="previousLink ? { href: previousLink } : {}">
                <Button variant="outline" :disabled="!previousLink">{{ $t('Previous') }}</Button>
            </component>

            <component :is="nextLink ? Link : 'span'" v-bind="nextLink ? { href: nextLink } : {}">
                <Button variant="outline" :disabled="!nextLink">{{ $t('Next') }}</Button>
            </component>
        </div>
    </div>
</template>
