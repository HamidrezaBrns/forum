<script setup lang="ts">
import ActivityItem from '@/components/ActivityItem.vue';
import DetailedPagination from '@/components/DetailedPagination.vue';
import ProfileLayout from '@/layouts/ProfileLayout.vue';
import { formatNumber } from '@/utilities/number';
import { Head } from '@inertiajs/vue3';
import 'remixicon/fonts/remixicon.css';

defineProps(['user', 'activities']);
</script>

<template>
    <Head :title="$t('Profile')" />

    <ProfileLayout :user="user">
        <div class="mx-auto max-w-[90%]">
            <h2 class="mb-4 text-3xl font-medium">
                {{ $t('Activities (:total)', { total: formatNumber(activities.meta.total) }) }}
            </h2>

            <div class="grid grid-cols-1 gap-20 border-s border-gray-300 dark:border-gray-700">
                <ActivityItem v-for="activity in activities.data" :key="activity.id" :activity="activity" />
            </div>

            <!-- Pagination -->
            <DetailedPagination :meta="activities.meta" class="mt-10" />
        </div>
    </ProfileLayout>
</template>
