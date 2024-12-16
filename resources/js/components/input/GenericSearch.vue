<script setup lang="ts">
import { ref } from "vue";
import axios from "axios";

const { onChange, linkApi, idElements } = defineProps<{
    onChange: (tags: Tag[]) => void;
    linkApi: string;
    idElements: string;
}>();

const query = ref("");
const suggestions = ref<Tag[]>([]);
const selectedTags = ref<Tag[]>([]);

const fetchSuggestions = async () => {
    if (query.value.length > 1) {
        try {
            const response = await axios.get(linkApi, {
                params: {
                    search: query.value,
                    id: idElements
                },
            });
            suggestions.value = response.data || [];
        } catch (error) {
            console.error("Errore durante il caricamento dei suggerimenti:", error);
            suggestions.value = [];
        }
    } else {
        suggestions.value = [];
    }
};

const selectTag = (tag: Tag) => {
    if (!selectedTags.value.find((t) => t.id === tag.id)) {
        selectedTags.value.push(tag);
        onChange(selectedTags.value);
    }
    query.value = "";
    suggestions.value = [];
};
</script>

<template>
    <div>
        <input
            v-model="query"
            id="tags"
            type="text"
            placeholder="Cerca e seleziona un tag"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300"
            @input="fetchSuggestions"
        />
        <ul v-if="suggestions.length" class="border border-gray-300 rounded-lg mt-2 bg-white max-h-40 overflow-y-auto">
            <li
                v-for="tag in suggestions"
                :key="tag.id"
                @click="selectTag(tag)"
                class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
            >
                {{ tag.name }}
            </li>
        </ul>
    </div>
</template>


