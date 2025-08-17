<script setup lang="ts">
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight';
import { Placeholder } from '@tiptap/extensions';
import StarterKit from '@tiptap/starter-kit';
import { EditorContent, useEditor } from '@tiptap/vue-3';
import 'highlight.js/styles/atom-one-dark.min.css';
import { common, createLowlight } from 'lowlight';
import 'remixicon/fonts/remixicon.css';
import { onBeforeUnmount, onMounted, watch } from 'vue';

const props = defineProps(['editorClass', 'placeholder']);
const model = defineModel();

const lowlight = createLowlight(common);

const editor = useEditor({
    extensions: [
        StarterKit.configure({
            heading: {
                levels: [2, 3, 4],
            },
            codeBlock: false,
        }),

        CodeBlockLowlight.configure({
            lowlight,
        }),

        Placeholder.configure({
            placeholder: props.placeholder,
        }),
    ],

    editorProps: {
        attributes: {
            class: `min-h-[512px] prose prose-sm prose-slate dark:prose-invert max-w-none px-3 py-2 ${props.editorClass}`,
        },
    },

    onUpdate: () => (model.value = editor.value?.getHTML()),
});

// defineExpose({ focus: () => editor.value?.commands.focus() });

onMounted(() => {
    watch(
        model,
        (value) => {
            if (editor.value?.getHTML() === value) {
                return;
            }

            editor.value?.commands.setContent(value);
        },
        { immediate: true },
    );
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});

const promptUserForHref = () => {
    if (editor.value?.isActive('link')) {
        return editor.value?.chain().unsetLink().run();
    }

    const href = prompt('Where do you want to link to?');

    if (!href) {
        return editor.value?.chain().focus().run();
    }

    return editor.value?.chain().focus().setLink({ href }).run();
};
</script>

<template>
    <div
        v-if="editor"
        class="rounded-md border border-input bg-transparent shadow-xs transition-[color,box-shadow] outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-input/30"
    >
        <menu class="flex divide-x border-b">
            <li>
                <button
                    @click="editor.chain().focus().toggleBold().run()"
                    type="button"
                    class="rounded-tl-md px-3 py-2"
                    :class="[editor.isActive('bold') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Bold"
                >
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleItalic().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('italic') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Italic"
                >
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleUnderline().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('underline') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Underline"
                >
                    <i class="ri-underline"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleStrike().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('strike') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Strikethrough"
                >
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleBlockquote().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('blockquote') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Blockquote"
                >
                    <i class="ri-double-quotes-r"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleBulletList().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('bulletList') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Bullet list"
                >
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleOrderedList().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('orderedList') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Numeric list"
                >
                    <i class="ri-list-ordered"></i>
                </button>
            </li>
            <li>
                <button
                    @click="promptUserForHref"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('link') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Add link"
                >
                    <i class="ri-link"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('heading', { level: 2 }) ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Heading 1"
                >
                    <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('heading', { level: 3 }) ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Heading 2"
                >
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleHeading({ level: 4 }).run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('heading', { level: 4 }) ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Heading 2"
                >
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleCode().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('code') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Inline code"
                >
                    <i class="ri-code-s-slash-fill"></i>
                </button>
            </li>
            <li>
                <button
                    @click="editor.chain().focus().toggleCodeBlock().run()"
                    type="button"
                    class="px-3 py-2"
                    :class="[editor.isActive('codeBlock') ? 'bg-slate-700 text-white' : 'hover:bg-gray-200']"
                    title="Code block"
                >
                    <i class="ri-code-block"></i>
                </button>
            </li>
        </menu>

        <EditorContent :editor="editor" />
    </div>
</template>

<style lang="scss" scoped>
:deep(.tiptap) {
    :first-child {
        margin-top: 0;
    }

    pre {
        border-radius: 0.5rem;
        font-family: 'JetBrainsMono', monospace;
        margin: 1.5rem 0;
        padding: 0.75rem 1rem;

        code {
            background: none;
            color: inherit;
            font-size: 0.8rem;
            padding: 0;
        }
    }
}

:deep(.tiptap p.is-editor-empty:first-child::before) {
    color: #adb5bd;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}
</style>
