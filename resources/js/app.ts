import "../css/app.css"; // Assicurati che il percorso sia corretto

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import DefaultLayout from './components/layout/DefaultLayout.vue'; // Assicurati che il percorso sia corretto

// Importazione di tutte le icone solid
import { fas } from "@fortawesome/free-solid-svg-icons";
import { library } from "@fortawesome/fontawesome-svg-core";

// Aggiungi tutte le icone solid alla libreria
library.add(fas);

createInertiaApp({
    // Risolvi il componente della pagina
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`, // Verifica che il percorso corrisponda alla directory effettiva
            import.meta.glob('./pages/**/*.vue') // Assicurati che il percorso corrisponda alla struttura dei file
        ).then((module: { default: any }) => {
            const page = module.default;

            // Imposta il layout di default se non specificato
            page.layout ??= DefaultLayout;
            return page;
        }),
    setup({ el, App, props, plugin }) {
        // Crea e monta l'app Vue
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
