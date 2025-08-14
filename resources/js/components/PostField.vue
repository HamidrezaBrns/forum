<script setup lang="ts">
import Tags from '@/components/Tags.vue';
import UserInfoCard from '@/components/UserInfoCard.vue';
import Vote from '@/components/Vote.vue';

// question/answer
defineProps(['post']);

defineEmits(['edit', 'delete']);
</script>

<template>
    <div class="flex">
        <Vote :post="post" class="pr-4" />

        <div class="w-full">
            <article class="mb-4 break-all">
                {{ post.body }}
            </article>

            <Tags :question="post" />

            <div class="mt-4 flex justify-between">
                <div class="flex space-x-2 text-xs">
                    <form v-if="post.can?.update" @submit.prevent="$emit('edit', post.id)">
                        <button class="cursor-pointer font-mono text-gray-400 hover:font-bold">EDIT</button>
                    </form>

                    <form v-if="post.can?.delete" @submit.prevent="$emit('delete', post.id)">
                        <button class="cursor-pointer font-mono text-red-700 hover:font-bold">DELETE</button>
                    </form>
                </div>

                <UserInfoCard :post="post" />
            </div>
        </div>
    </div>
</template>
