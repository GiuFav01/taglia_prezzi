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
        <div class="mt-2 flex flex-wrap gap-2">
            <span
                v-for="tag in selectedTags"
                :key="tag.id"
                class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm"
            >
                {{ tag.name }}
                <button @click="removeTag(tag)" class="ml-2 text-red-500">x</button>
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import axios from "axios";

const props = defineProps<{
    onChange: (tags: Tag[]) => void; // Funzione per restituire i tag selezionati al genitore
    initialSuggestions?: Tag[]; // Suggerimenti iniziali opzionali
}>();

const query = ref("");
const suggestions = ref<Tag[]>(props.initialSuggestions || []);
const selectedTags = ref<Tag[]>([]);

const fetchSuggestions = async () => {
    if (query.value.length > 1) {
        try {
            const response = await axios.get("/tags/search", {
                params: { search: query.value },
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
        props.onChange(selectedTags.value);
    }
    query.value = ""; // Resetta il campo di input
    suggestions.value = []; // Pulisci i suggerimenti
};

const removeTag = (tag: Tag) => {
    selectedTags.value = selectedTags.value.filter((t) => t.id !== tag.id);
    props.onChange(selectedTags.value);
};
</script>
