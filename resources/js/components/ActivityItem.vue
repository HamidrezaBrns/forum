<script setup lang="ts">
import { formatFull } from '@/utilities/date';
import { Link } from '@inertiajs/vue3';
import { CircleAlert, MailQuestion, MessageSquareMore, MessagesSquare, ThumbsDown, ThumbsUp } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    activity: {
        type: Object,
        required: true,
    },
});

const relatedQuestion = computed(() => {
    const subject = props.activity.subject;

    return (
        subject?.commentable?.question ?? // comment -> answer -> question
        subject?.commentable ?? // comment -> question
        subject?.votable?.question ?? // vote -> answer -> question
        subject?.votable ?? // vote -> question
        subject?.question ?? // answer -> question
        subject ?? // question
        null
    );
});

// activity type configuration
const typeConfig = {
    created_question: {
        icon: () => MailQuestion,
        color: 'text-indigo-600',
        label: 'Started Conversation',
    },
    posted_answer: {
        icon: () => MessageSquareMore,
        color: 'text-green-600',
        label: 'Answered to',
    },
    posted_comment: {
        icon: () => MessagesSquare,
        color: 'text-amber-600',
        label: (activity) => (activity.subject?.commentable_type === 'answer' ? 'Commented on an Answer to' : 'Commented on'),
    },
    cast_vote: {
        icon: (activity) => (activity.subject?.is_upvote ? ThumbsUp : ThumbsDown),
        color: 'text-slate-600',
        label: (activity) => {
            const vote = activity.subject?.is_upvote ? 'Upvoted' : 'Downvoted';
            return activity.subject?.votable_type === 'answer' ? `${vote} an Answer to` : `${vote} Question`;
        },
    },
};

// resolved config for current activity
const activityConfig = computed(() => {
    const config = typeConfig[props.activity.type];

    if (!config) {
        return {
            icon: CircleAlert,
            color: 'text-gray-400',
            label: () => 'did something',
        };
    }

    return {
        icon: config.icon(props.activity),
        color: config.color,
        label: typeof config.label === 'function' ? config.label(props.activity) : config.label,
    };
});
</script>

<template>
    <div class="relative">
        <!-- Icon -->
        <div
            class="absolute top-4 flex size-8 items-center justify-center rounded-full border bg-background ltr:-left-4 rtl:-right-4"
            :class="activityConfig.color"
        >
            <component :is="activityConfig.icon" class="size-5" />
        </div>

        <!-- Content -->
        <div class="ms-6 min-w-0 rounded-md bg-gray-100 px-4 py-2 dark:bg-gray-800">
            <div class="mt-1 text-sm text-gray-500">{{ formatFull(activity.created_at) }}</div>

            <div class="line-clamp-1 min-w-0 text-lg font-medium">
                {{ $t(activityConfig.label) }}
                <template v-if="relatedQuestion">
                    <Link
                        v-if="relatedQuestion.title"
                        :href="route('questions.show', relatedQuestion.id)"
                        class="break-words text-blue-500 hover:underline dark:text-blue-400"
                    >
                        {{ relatedQuestion.title }}
                    </Link>
                </template>

                <span v-else class="text-base text-gray-500 italic">{{ $t('[Deleted Question]') }}</span>
            </div>

            <div class="prose text-sm text-gray-600 dark:text-gray-300 dark:prose-invert">
                <p v-if="activity.subject?.body" class="break-words" v-html="activity.subject?.body"></p>
                <p v-else-if="activity.subject_type === 'vote'" class="break-words" v-html="activity.subject?.votable?.body"></p>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
:deep([style*='text-align: right']) {
    direction: rtl;
}
</style>
