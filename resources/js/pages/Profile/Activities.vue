<script setup lang="ts">
import ActivityItem from '@/components/ActivityItem.vue';
import Container from '@/components/Container.vue';
import DetailedPagination from '@/components/DetailedPagination.vue';
import ProfileInfoCard from '@/components/ProfileInfoCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatNumber } from '@/utilities/number';
import { Head } from '@inertiajs/vue3';
import 'remixicon/fonts/remixicon.css';

defineProps(['user', 'activities']);
</script>

<template>
    <Head :title="$t('Profile')" />

    <AppLayout>
        <Container>
            <div class="grid grid-cols-12 gap-y-8 lg:gap-x-10">
                <aside class="col-span-full lg:col-span-4">
                    <ProfileInfoCard :user="user" class="sticky top-25" />
                </aside>

                <div class="col-span-full lg:col-span-8">
                    <div class="mx-auto max-w-[90%]">
                        <h2 class="mb-4 text-3xl font-medium">
                            {{ $t(':total Activities', { total: formatNumber(activities.meta.total) }) }}
                        </h2>

                        <div class="grid grid-cols-1 gap-20 border-s border-gray-300 dark:border-gray-700">
                            <ActivityItem v-for="activity in activities.data" :key="activity.id" :activity="activity" />
                        </div>

                        <!-- Pagination -->
                        <DetailedPagination :meta="activities.meta" class="mt-10" />
                    </div>
                </div>
            </div>
        </Container>
    </AppLayout>
</template>
