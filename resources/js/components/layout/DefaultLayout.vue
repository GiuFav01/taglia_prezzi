<template>
    <div>
        <main class="flex">
            <SimpleDarkMenu :menuData="menuItems" />
            <slot />
        </main>
    </div>
</template>

<script setup lang="ts">
import SimpleDarkMenu from '../nav-bar/SimpleDarkMenu.vue';

const menuItems = [
    {
        label: 'Modifica Api',
        icon: ['fas','fa-home'],
        link: '/',
    },
    {
        label: 'Modifica Tag',
        icon: ['fas','fa-th'],
        link: '/tags',
    },
];
</script>

<style scoped>
/* Stili personalizzati per il layout */
</style>
