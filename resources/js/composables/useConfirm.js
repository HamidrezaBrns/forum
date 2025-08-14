import { reactive, readonly } from 'vue';

const globalState = reactive({
    show: false,
    title: '',
    description: '',
    resolver: null,
});

export function useConfirm() {
    const resetModal = () => {
        globalState.title = '';
        globalState.description = '';
        globalState.show = false;
        globalState.resolver = null;
    };

    return {
        state: readonly(globalState),

        confirmation: (title = 'Please Confirm', message) => {
            globalState.title = title;
            globalState.description = message;
            globalState.show = true;

            return new Promise((resolver) => {
                globalState.resolver = resolver;
            });
        },

        confirm: () => {
            if (globalState.resolver) {
                globalState.resolver(true);
            }

            resetModal();
        },

        cancel: () => {
            if (globalState.resolver) {
                globalState.resolver(false);
            }

            resetModal();
        },
    };
}
