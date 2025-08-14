<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { useConfirm } from '@/composables/useConfirm';

const { state, confirm, cancel } = useConfirm();
</script>

<template>
    <Dialog modal :open="state.show">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ state.title }}</DialogTitle>
                <DialogDescription>{{ state.description }}</DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" @click="cancel" variant="outline">Cancel</Button>
                </DialogClose>

                <Button type="button" @click="confirm">Confirm</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
