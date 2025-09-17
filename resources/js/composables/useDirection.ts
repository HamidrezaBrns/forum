import { computed, onMounted, ref } from 'vue';

export function useDirection() {
    const dir = ref<'rtl' | 'ltr'>('ltr');

    const updateDir = () => {
        dir.value = (document.documentElement.getAttribute('dir') as 'rtl' | 'ltr') || 'ltr';
    };

    onMounted(() => {
        updateDir();

        const observer = new MutationObserver(() => updateDir());
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['dir'] });
    });

    const isRTL = computed(() => dir.value === 'rtl');
    const isLTR = computed(() => dir.value === 'ltr');

    return {
        dir,
        isRTL,
        isLTR,
    };
}
