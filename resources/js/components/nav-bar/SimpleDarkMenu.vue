<script setup lang="ts">
import { ref, PropType } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Inertia } from '@inertiajs/inertia';

interface MenuItem {
    label: string;
    icon: string[];
    children?: string[];
    link?: string;
}

defineProps({
    menuData: {
        type: Array as PropType<MenuItem[]>,
        required: true,
    },
});

const activeIndex = ref<number | null>(null);

const toggleSubMenu = (index: number) => {
    activeIndex.value = activeIndex.value === index ? null : index;
};

const handleLogout = () => {
    Inertia.post('/logout');
};
</script>

<template>
    <nav class="menu bg-gray-900 text-white w-72 shadow-lg">
        <div class="menu-header p-6 text-2xl font-bold flex items-center">
            <span class="ml-4">Taglia Prezzi</span>
        </div>

        <ul class="menu-list mt-6">
            <li v-for="(item, index) in menuData" :key="index" class="menu-item">
                <div
                    class="relative flex items-center px-5 py-3 cursor-pointer group"
                    @click="toggleSubMenu(index)">
                    <div class="absolute inset-0 bg-transparent group-hover:bg-gray-800 rounded-lg transition-all duration-300"></div>

                    <FontAwesomeIcon
                        :icon="item.icon"
                        class="relative z-10 text-lg mr-4 text-gray-400 group-hover:text-green-400 transition-colors duration-300"
                    />

                    <a :href="item.link" class="relative z-10 flex-1 text-gray-300 group-hover:text-white transition-colors duration-300">
                        {{ item.label }}
                    </a>

                    <FontAwesomeIcon
                        v-if="item.children && item.children.length > 0"
                        :icon="activeIndex === index ? ['fas', 'chevron-down'] : ['fas', 'chevron-right']"
                        class="relative z-10 text-sm text-gray-400 group-hover:text-green-400 transition-transform duration-300"
                    />
                </div>

                <ul
                    v-if="item.children && activeIndex === index"
                    class="submenu bg-gray-800 pl-10 py-2 rounded-lg space-y-2">
                    <li
                        v-for="(subItem, subIndex) in item.children"
                        :key="subIndex"
                        class="submenu-item">
                        <span
                            class="block py-2 text-gray-400 hover:text-green-400 transition-colors duration-300 cursor-pointer"
                        >
                            {{ subItem }}
                        </span>
                    </li>
                </ul>
            </li>

            <!-- Pulsante Logout -->
            <li class="menu-item mt-6">
                <button
                    @click="handleLogout"
                    class="relative flex items-center px-5 py-3 w-full text-left cursor-pointer bg-red-600 hover:bg-red-700 text-white rounded-lg transition-all duration-300"
                >
                    <FontAwesomeIcon icon="fas fa-sign-out-alt" class="text-lg mr-4" />
                    Logout
                </button>
            </li>
        </ul>
    </nav>
</template>

<style scoped>
.menu {
    font-family: 'Roboto', sans-serif;
}

.menu-header {
    border-bottom: 1px solid #2d3748;
}

.menu-list {
    list-style: none;
}

.menu-item {
    margin-bottom: 0.5rem;
    padding-left: 10px;
    padding-right: 10px;
    font-weight: bold;
}

.submenu {
    margin-top: 0.5rem;
}
</style>
