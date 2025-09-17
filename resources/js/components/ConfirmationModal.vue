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
                <DialogTitle>{{ $t(state.title) }}</DialogTitle>
                <DialogDescription>{{ $t(state.description) }}</DialogDescription>
            </DialogHeader>

            <DialogFooter>
                <DialogClose as-child>
                    <Button type="button" @click="cancel" variant="outline">{{ $t('Cancel') }}</Button>
                </DialogClose>

                <Button type="button" @click="confirm">{{ $t('Confirm') }}</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
