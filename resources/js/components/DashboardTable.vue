<script setup lang="ts">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Link } from '@inertiajs/vue3';

type Cell = {
    text?: string | number;
    html?: string;
    href?: string;
    clamp?: number; // (line-clamp)
};

const props = defineProps<{
    title: string;
    count?: number;
    columns: string[];
    rows: Cell[][];
}>();
</script>

<template>
    <div>
        <div class="mb-2 flex items-center gap-2">
            <h2 class="text-lg font-semibold">{{ title }}</h2>
            <span class="text-sm text-gray-500" v-if="count">│Total: {{ count }}</span>
        </div>

        <div class="overflow-x-auto">
            <Table class="w-full text-left text-sm">
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="col in columns" :key="col">
                            {{ col }}
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="(row, i) in rows" :key="i">
                        <TableCell v-for="(cell, j) in row" :key="j" class="max-w-sm whitespace-break-spaces">
                            <div
                                class="overflow-hidden break-words whitespace-normal"
                                :class="{
                                    'line-clamp-1': cell.clamp === 1,
                                    'line-clamp-2': cell.clamp === 2,
                                }"
                            >
                                <Link v-if="cell.href" :href="cell.href" class="inline text-blue-600 hover:underline">
                                    <span v-if="cell.html" v-html="cell.text"></span>
                                    <span v-else>{{ cell.text }}</span>
                                </Link>

                                <template v-else>
                                    <span v-if="cell.html" v-html="cell.html"></span>
                                    <span v-else>{{ cell.text }}</span>
                                </template>
                            </div>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
