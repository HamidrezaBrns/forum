<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Textarea } from '@/components/ui/textarea';
import UserInfoCommentCard from '@/components/UserInfoCommentCard.vue';
import { useConfirm } from '@/composables/useConfirm';
import { formattedDate } from '@/utilities/date';
import { router, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { nextTick, ref, useTemplateRef } from 'vue';
import { toast } from 'vue-sonner';
import { route } from 'ziggy-js';

const props = defineProps({
    commentableType: {
        type: String,
        required: true,
    },
    commentableId: {
        type: Number,
        required: true,
    },
    commentsCount: {
        type: Number,
        default: 0,
    },
});

const comments = ref<{ id: number; body: string; can: { delete: boolean } }[]>([]);
const loading = ref(false);
const loaded = ref(false);
const showComments = ref(false); // for showing comment list
const showForm = ref(false); // for showing textarea

const textarea = useTemplateRef('commentTextarea');
const show = () => {
    showForm.value = true;
    nextTick(() => {
        textarea.value?.$el.focus();
    });
};

// Lazy GET via axios
const fetchComments = async () => {
    loading.value = true;

    const { data } = await axios.get(`/${props.commentableType}s/${props.commentableId}/comments`);
    comments.value = data; // can get pagination and meta

    loaded.value = true;
    loading.value = false;
};
// Load comments
const loadComments = () => {
    if (!loaded.value) {
        fetchComments();
    }

    showComments.value = !showComments.value;
};

const commentForm = useForm({
    body: '',
    commentable_type: '',
    commentable_id: 0,
});
const addComment = () => {
    commentForm.commentable_type = props.commentableType;
    commentForm.commentable_id = props.commentableId;

    commentForm.post(route('comments.store', [commentForm.commentable_type, commentForm.commentable_id]), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
            fetchComments(); // reload the list from backend
            showComments.value = true;
            toast.success('Comment successfully created.', {
                description: formattedDate,
            });
        },
    });
};

const cancelPostComment = () => {
    commentForm.clearErrors();
    commentForm.reset();
    showForm.value = false;
};

const { confirmation } = useConfirm();
const deleteComment = async (commentId: number) => {
    if (!(await confirmation('Delete Comment', 'Are you sure you want to delete this comment?'))) {
        return;
    }

    const previousComments = [...comments.value];
    comments.value = comments.value.filter((comment) => comment.id !== commentId); // update comments value

    router.delete(route('comments.destroy', commentId), {
        preserveScroll: true,
        onError: () => {
            comments.value = previousComments; // rollback
        },
        onSuccess: () => {
            toast.info('Your comment has been deleted.');
        },
    });
};
</script>

<template>
    <div class="mt-3 text-sm text-gray-600">
        <!-- Button to load -->
        <button
            @click="loadComments"
            :disabled="commentsCount < 1"
            class="inline-flex cursor-pointer items-center gap-1 text-lg font-medium text-black disabled:cursor-default"
        >
            <h3 v-text="`${commentsCount} Comments`" />
            <div v-if="commentsCount > 0" class="text-lg">
                <i v-if="!showComments" class="ri-arrow-down-s-line"></i>
                <i v-else class="ri-arrow-up-s-line"></i>
            </div>
        </button>

        <!-- create -->
        <div class="mt-2">
            <Button v-if="!showForm" variant="outline" class="w-full" @click="show">Add a comment</Button>

            <form v-if="showForm" @submit.prevent="addComment">
                <div class="mb-2">
                    <Textarea
                        ref="commentTextarea"
                        v-model="commentForm.body"
                        class="flex-1 rounded border px-2 py-1 break-all whitespace-break-spaces"
                        placeholder="Add a comment…"
                        required
                    />
                    <InputError :message="commentForm.errors.body" />
                </div>
                <Button size="sm" :disabled="commentForm.processing" class="bg-blue-500 hover:bg-blue-400">Post</Button>
                <Button size="sm" type="button" @click="cancelPostComment" variant="outline" class="ml-2" :disabled="commentForm.processing"
                    >Cancel
                </Button>
            </form>
        </div>

        <div v-if="showComments">
            <!-- Spinner -->
            <div v-if="loading">Loading comments...</div>
            <!-- Comment List -->
            <ul v-else class="divide-y">
                <li v-for="comment in comments" :key="comment.id" class="py-2">
                    <div class="flex items-center justify-between">
                        <UserInfoCommentCard :comment="comment" />

                        <div class="flex space-x-2 text-xs">
                            <!--                    <form v-if="post.can?.update" @submit.prevent="$emit('edit', post.id)">-->
                            <!--                        <button class="cursor-pointer font-mono text-gray-400 hover:font-bold">EDIT</button>-->
                            <!--                    </form>-->

                            <!--                    Delete Comment-->
                            <form v-if="comment.can?.delete" @submit.prevent="deleteComment(comment.id)">
                                <button class="cursor-pointer font-mono text-red-700 hover:font-bold">DELETE</button>
                            </form>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="w-10"></div>
                        <div class="text-sm leading-4.5 wrap-anywhere">
                            {{ comment.body }}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
