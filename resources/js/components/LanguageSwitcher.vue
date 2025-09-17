<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuRadioGroup, DropdownMenuRadioItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { router } from '@inertiajs/vue3';
import { loadLanguageAsync } from 'laravel-vue-i18n';
import { ref } from 'vue';

const languages = [
    { title: 'English', code: 'en' },
    { title: 'فارسی', code: 'fa' },
];

const currentLang = ref(document.documentElement.lang || 'en');

const changeLanguage = (lang) => {
    // if (lang.code === currentLang.value) return;

    router.post(
        route('language.switch'),
        { locale: lang.code },
        {
            preserveScroll: true,
            onSuccess: async () => {
                await loadLanguageAsync(lang.code);
            },
        },
    );

    document.documentElement.lang = lang.code;
    document.documentElement.dir = lang.code === 'fa' ? 'rtl' : 'ltr';

    currentLang.value = lang.code;
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="outline">
                {{ languages.find((l) => l.code === currentLang)?.title }}
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent>
            <DropdownMenuRadioGroup v-model="currentLang">
                <DropdownMenuRadioItem v-for="lang in languages" :key="lang.code" :value="lang.code" @click="changeLanguage(lang)">
                    {{ lang.title }}
                </DropdownMenuRadioItem>
            </DropdownMenuRadioGroup>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
