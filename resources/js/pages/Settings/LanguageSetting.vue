<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { useDirection } from '@/composables/useDirection';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Head, router } from '@inertiajs/vue3';
import { loadLanguageAsync } from 'laravel-vue-i18n';
import { ref } from 'vue';

const languages = [
    { title: 'English', code: 'en' },
    { title: 'Persian', code: 'fa' },
];

const currentLang = ref(document.documentElement.lang || 'en');

const { dir } = useDirection();

const changeLanguage = async (langCode: string) => {
    router.post(
        route('language.switch'),
        { locale: langCode },
        {
            onSuccess: async () => {
                await loadLanguageAsync(langCode);

                document.documentElement.lang = langCode;
                document.documentElement.dir = langCode === 'fa' ? 'rtl' : 'ltr';

                currentLang.value = langCode;
            },
        },
    );
};
</script>

<template>
    <AppLayout>
        <Head :title="$t('Language settings')" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall :title="$t('Language settings')" :description="$t('You can change the language of the app to your locale language.')" />

                <RadioGroup :dir="dir" v-model="currentLang" @update:model-value="changeLanguage">
                    <div v-for="lang in languages" :key="lang.code" class="flex items-center gap-2">
                        <RadioGroupItem :id="lang.code" :value="lang.code" />
                        <Label :for="lang.code">{{ lang.title }}</Label>
                    </div>
                </RadioGroup>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
