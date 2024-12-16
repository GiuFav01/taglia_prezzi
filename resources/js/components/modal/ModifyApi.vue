<script setup lang="ts">
import { ref, watch } from "vue";
import GenericSearch from "@/components/input/GenericSearch.vue";
import Api from "../../interfaces/Api";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps<{
    onAdd: (newApi: Api) => void;
    onClose: () => void;
    api?: Api | null;
}>();

let actualTags:Tag[];
const form = ref<Api>({
    url: "",
    description: "",
    tags: [],
    id: "",
    lastExecution: ""
});

watch(
    () => props.api,
    (api) => {
        form.value = api
            ? { ...api }
            : {
                  url: "",
                  description: "",
                  tags: [],
                  id: "",
                  lastExecution: ""
              };
        actualTags = api ? api.tags : [];
    },
    { immediate: true }
);

const updateSelectedTags = (tags: Tag[]) => {
    let newTags = tags.map((tag) => ({
        id: tag.id,
        name: tag.name,
        description: tag.description || '',
    }));
    form.value.tags = newTags;
    actualTags.push(...newTags);
};

const removeTag = (tag: Tag) => {
    console.log('Rimuovi il tag:', tag);
    Inertia.delete(`/apis/${form.value.id}/tags/${tag.id}`);
};

const submitForm = () => {
    props.onAdd({ ...form.value });
};
</script>

<template>
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <!-- Intestazione del Modale -->
            <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-xl font-semibold">{{ api ? "Modifica API" : "Aggiungi API" }}</h2>
            </div>

            <!-- Contenuto del Modale -->
            <form @submit.prevent="submitForm" class="p-6">
                <div class="mb-4">
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input
                        v-model="form.url"
                        id="url"
                        type="text"
                        placeholder="Inserisci l'URL"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                        required
                    />
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                    <input
                        v-model="form.description"
                        id="description"
                        type="text"
                        placeholder="Inserisci la descrizione"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                        required
                    />
                </div>

                <div class="mb-4">
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
                    <div class="mb-4">
                        <GenericSearch
                            :on-change="updateSelectedTags"
                            link-api="/tags/search"
                            :id-elements="form.id"
                        />
                    </div>

                    <div v-if="api && form.tags.length" class="mt-2">
                        <h3 class="text-sm font-medium text-gray-700">Tag associati:</h3>
                        <ul class="mt-1 flex gap-2">
                            <li
                                v-for="tag in actualTags"
                                :key="tag.id"
                                class="text-sm text-gray-800 flex items-center justify-between"
                            >
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                    {{ tag.name }}
                                    <button type="button"  @click="removeTag(tag)" class="ml-2 text-red-500">x</button>
                                </span>

                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Pulsanti di Azione -->
                <div class="flex justify-end gap-4">
                    <button
                        type="button"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition"
                        @click="onClose"
                    >
                        Annulla
                    </button>
                    <button
                        type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition"
                    >
                        {{ api ? "Salva Modifiche" : "Aggiungi" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
