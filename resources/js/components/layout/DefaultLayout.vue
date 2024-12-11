<template>
    <div>
        <main class="flex justify-center">
            <!-- Mostra il menu solo se non sei nella pagina di login -->
            <SimpleDarkMenu v-if="!isLoginPage" :menuData="menuItems" />
            <slot />
        </main>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import SimpleDarkMenu from '../nav-bar/SimpleDarkMenu.vue';

// Ottieni l'URL corrente usando window.location.href
const currentUrl = window.location.href;
console.log('Current URL:', currentUrl);

// Determina se sei nella pagina di login (puoi personalizzare il controllo)
const isLoginPage = computed(() => currentUrl.includes('/login'));

// Dati del menu
const menuItems = [
    {
        label: 'Modifica Api',
        icon: ['fas', 'fa-home'],
        link: '/',
    },
    {
        label: 'Modifica Tag',
        icon: ['fas', 'fa-th'],
        link: '/tags',
    },
    {
        label: 'Lista Asin',
        icon: ['fas', 'list'],
        link: '/asins',
    }
];
</script>
