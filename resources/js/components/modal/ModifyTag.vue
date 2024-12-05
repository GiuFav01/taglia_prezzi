<template>
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
            <!-- Intestazione del Modale -->
            <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                <h2 class="text-xl font-semibold">{{ tag ? "Modifica Tag" : "Aggiungi Tag" }}</h2>
            </div>

            <!-- Contenuto del Modale -->
            <form @submit.prevent="submitForm" class="p-6">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input
                        v-model="form.name"
                        id="name"
                        type="text"
                        placeholder="Inserisci il nome del tag"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                        required
                    />
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                    <textarea
                        v-model="form.description"
                        id="description"
                        placeholder="Inserisci una descrizione"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                        required
                    ></textarea>
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
                        {{ tag ? "Salva Modifiche" : "Aggiungi" }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";

const props = defineProps<{
    onAdd: (newTag: Tag) => void;
    onClose: () => void;
    tag?: Tag | null;
}>();

const form = ref<Tag>({
    name: "",
    description: ""
});

watch(
    () => props.tag,
    (tag) => {
        form.value = tag
            ? { ...tag }
            : {
                  name: "",
                  description: ""
              };
    },
    { immediate: true }
);

const submitForm = () => {
    props.onAdd(form.value);
    form.value = {
        name: "",
        description: ""
    };
};
</script>
