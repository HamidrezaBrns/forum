<script setup lang="ts">
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/ui/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import { useFilter } from 'reka-ui';
import { computed, ref } from 'vue';

const props = defineProps<{
    availableTags: { id: number; name: string }[];
}>();

const tags = defineModel<string[]>({ default: [] });

const open = ref(false);
const searchTerm = ref('');

const { contains } = useFilter({ sensitivity: 'base' });
const filteredTags = computed(() => {
    const options = props.availableTags.filter((tag) => !tags.value.includes(tag.name));
    return searchTerm.value ? options.filter((tag) => contains(tag.name, searchTerm.value)) : options;
});

const addTag = (tag: string) => {
    if (!tags.value.includes(tag)) {
        tags.value.push(tag);
    }
    searchTerm.value = '';
};
</script>

<template>
    <Combobox v-model="tags" v-model:open="open" :ignore-filter="true">
        <ComboboxAnchor as-child>
            <TagsInput id="tags" v-model="tags" class="w-full gap-2 px-2">
                <div class="flex flex-wrap items-center gap-2">
                    <TagsInputItem v-for="tag in tags" :key="tag" :value="tag">
                        <TagsInputItemText />
                        <TagsInputItemDelete />
                    </TagsInputItem>
                </div>

                <ComboboxInput v-model="searchTerm" as-child>
                    <TagsInputInput
                        @keydown.enter.prevent
                        class="h-auto w-full border-none p-0 focus-visible:ring-0"
                        placeholder="Up to 5 tags - e.g. php, laravel"
                    />
                </ComboboxInput>
            </TagsInput>

            <ComboboxList>
                <ComboboxEmpty>No tags found</ComboboxEmpty>
                <ComboboxGroup>
                    <ComboboxItem v-for="tag in filteredTags" :key="tag.id" :value="tag.name" @select.prevent="addTag(tag.name)">
                        {{ tag.name }}
                    </ComboboxItem>
                </ComboboxGroup>
            </ComboboxList>
        </ComboboxAnchor>
    </Combobox>
</template>
