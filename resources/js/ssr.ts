import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { i18nVue } from 'laravel-vue-i18n';
import { createSSRApp, DefineComponent, h } from 'vue';
import { renderToString } from 'vue/server-renderer';
import { ZiggyVue } from 'ziggy-js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer(
    (page) =>
        createInertiaApp({
            page,
            render: renderToString,
            title: (title) => (title ? `${title} - ${appName}` : appName),
            resolve: resolvePage,
            setup: ({ App, props, plugin }) =>
                createSSRApp({ render: () => h(App, props) })
                    .use(plugin)
                    .use(ZiggyVue, {
                        ...page.props.ziggy,
                        location: new URL(page.props.ziggy.location),
                    })
                    .use(i18nVue, {
                        lang: 'fa',
                        resolve: (lang: string) => {
                            const langs = import.meta.glob('../../lang/*.json', { eager: true });
                            return langs[`../../lang/${lang}.json`].default;
                        },
                    }),
        }),
    { cluster: true },
);

function resolvePage(name: string) {
    const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue');

    return resolvePageComponent<DefineComponent>(`./pages/${name}.vue`, pages);
}
