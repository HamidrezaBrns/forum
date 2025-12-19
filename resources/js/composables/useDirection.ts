import { computed, onMounted, onUnmounted, ref } from 'vue';

export function useDirection() {
    const dir = ref<'rtl' | 'ltr'>('ltr');

    const updateDir = () => {
        if (typeof document !== 'undefined') {
            dir.value = (document.documentElement.getAttribute('dir') as 'rtl' | 'ltr') || 'ltr';
        }
    };

    updateDir();

    let observer: MutationObserver | null = null;

    onMounted(() => {
        observer = new MutationObserver(() => updateDir());
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['dir'],
        });
    });

    onUnmounted(() => {
        observer?.disconnect();
    });

    const isRTL = computed(() => dir.value === 'rtl');
    const isLTR = computed(() => dir.value === 'ltr');

    return { dir, isRTL, isLTR };
}
