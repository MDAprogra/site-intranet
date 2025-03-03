import './bootstrap';
import Alpine from 'alpinejs';
import { createApp, defineAsyncComponent } from 'vue';

window.Alpine = Alpine;
Alpine.start();

// Création de l'instance Vue
const app = createApp({});

// Fonction pour charger dynamiquement un composant
const loadComponent = (name) => defineAsyncComponent(() => import(`./components/${name}.vue`));

// Composant dynamique
app.component('DynamicComponent', {
    props: ['componentName'],
    computed: {
        component() {
            return loadComponent(this.componentName);
        }
    },
    template: `<component :is="component"></component>`
});

app.mount("#app");
