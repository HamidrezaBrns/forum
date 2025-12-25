<script setup lang="ts">
import DetailedPagination from '@/components/DetailedPagination.vue';
import Tags from '@/components/Tags.vue';
import { Button } from '@/components/ui/button';
import { useConfirm } from '@/composables/useConfirm';
import ProfileLayout from '@/layouts/ProfileLayout.vue';
import { formatFull } from '@/utilities/date';
import { formatNumber } from '@/utilities/number';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import { PencilLine, Trash2 } from 'lucide-vue-next';
import 'remixicon/fonts/remixicon.css';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

const props = defineProps(['drafts']);

const page = usePage();
const { confirmation } = useConfirm();

const deleteDraft = async (questionId: number) => {
    if (!(await confirmation(trans('Delete Draft'), trans('Are you sure you want to delete the draft?')))) {
        return;
    }

    router.delete(route('questions.destroyDraft', { question: questionId, page: props.drafts.meta.current_page }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.info(trans('The draft was deleted.'));
        },
    });
};
</script>

<template>
    <Head :title="$t('Drafts (:total)', { total: formatNumber(drafts.meta.total) })" />

    <ProfileLayout :user="page.props.auth.user">
        <div class="mx-auto max-w-5xl px-4">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-3xl font-medium">
                    {{ $t('Drafts (:total)', { total: formatNumber(drafts.meta.total) }) }}
                </h2>
            </div>

            <!-- Empty state -->
            <div v-if="!drafts.data.length" class="rounded-lg border border-dashed py-16 text-center text-muted-foreground">
                <p class="text-lg">{{ $t('You have no drafts yet.') }}</p>
                <Link :href="route('questions.create')" class="mt-4 inline-block text-primary underline">
                    {{ $t('Ask a question') }}
                </Link>
            </div>

            <!-- Draft list -->
            <ul v-else class="space-y-4">
                <li v-for="question in drafts.data" :key="question.id" class="rounded-lg border p-5 transition hover:bg-muted/40">
                    <!-- Meta -->
                    <div class="mb-2 flex items-center gap-0.5 text-xs text-muted-foreground">
                        <i class="ri-draft-line text-sm"></i>
                        <span> {{ formatFull(question.updated_at) }} </span>
                    </div>

                    <!-- Content -->
                    <div class="space-y-3">
                        <h3 class="text-lg leading-tight font-medium">
                            <Link :href="route('questions.show', question.id)" class="hover:underline">
                                {{ question.title }}
                            </Link>
                        </h3>

                        <p class="line-clamp-2 text-sm text-muted-foreground" v-html="question.body" />

                        <Tags :tags="question.tags" disable />
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex justify-end gap-2">
                        <Button variant="outline" size="sm" @click="deleteDraft(question.id)">
                            <Trash2 class="text-red-500" />
                            {{ $t('Delete') }}
                        </Button>

                        <Link :href="route('questions.edit', question.id)">
                            <Button variant="outline" size="sm">
                                <PencilLine />
                                {{ $t('Edit') }}
                            </Button>
                        </Link>
                    </div>
                </li>
            </ul>

            <!-- Pagination -->
            <DetailedPagination :meta="drafts.meta" class="mt-10" />
        </div>
    </ProfileLayout>
</template>
