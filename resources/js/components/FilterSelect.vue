<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    label: string;
    keyName: string;
    options: { value: string; label: string }[];
    route: string;
    only?: string[];
    modelValue?: string;
    placeholder?: string;
}>();

const selected = ref(props.modelValue ?? '');

watch(selected, () => {
    router.get(
        props.route,
        { [props.keyName]: selected.value },
        {
            preserveState: true,
            replace: true,
            only: props.only ?? [],
        },
    );
});
</script>

<template>
    <div class="flex items-center gap-2">
        <Label class="text-sm text-gray-600 dark:text-gray-300">{{ label }}</Label>
        <Select v-model="selected">
            <SelectTrigger class="w-[140px]">
                <SelectValue :placeholder="placeholder ?? 'All'" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem :value="null">All</SelectItem>
                <SelectItem v-for="opt in options" :key="opt.value" :value="opt.value">
                    {{ opt.label }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>
</template>
