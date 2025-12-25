<script setup lang="ts">
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import Container from '@/components/Container.vue';
import ProfileInfoCard from '@/components/ProfileInfoCard.vue';
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppHeaderLayout.vue';
import { User } from '@/types';
import 'vue-sonner/style.css';

defineProps<{
    user: User;
}>();
</script>

<template>
    <Toaster />

    <AppLayout>
        <Container>
            <div class="grid grid-cols-12 gap-y-8 lg:gap-x-10">
                <aside class="col-span-full lg:col-span-4">
                    <ProfileInfoCard :user="user" class="sticky top-25" />
                </aside>

                <div class="col-span-full lg:col-span-8">
                    <slot />
                </div>
            </div>
        </Container>
    </AppLayout>

    <ConfirmationModal />
</template>
