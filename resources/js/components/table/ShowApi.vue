<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import Api from "../../interfaces/Api";

defineProps<{
    apis: Api[];
    onEdit: (api: Api) => void;
    onDelete: (api: Api) => void;
    onExecute: (api: Api) => void;
}>();
</script>
<template>
    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full border-collapse">
            <!-- Intestazione della tabella -->
            <thead class="table-header">
                <tr>
                    <th class="table-header-cell">URL</th>
                    <th class="table-header-cell">Descrizione</th>
                    <th class="table-header-cell">Tag</th>
                    <th class="table-header-cell">Ultima Esecuzione</th>
                    <th class="table-header-cell">Azioni</th>
                </tr>
            </thead>

            <!-- Corpo della tabella -->
            <tbody>
                <tr v-for="api in apis" :key="api.id" class="table-row">
                    <td class="table-cell">{{ api.url.slice(0, 10) }}...</td>
                    <td class="table-cell">{{ api.description }}</td>
                    <td class="table-cell">
                        <div class="flex flex-wrap gap-2">
                            <span v-for="tag in api.tags" :key="tag.name" class="tag">
                                {{ tag.name }}
                            </span>
                        </div>
                    </td>
                    <td class="table-cell">{{ api.lastExecution }}</td>
                    <td class="table-cell">
                        <div class="actions">
                            <!-- Pulsante Modifica -->
                            <button @click="onEdit(api)" class="btn btn-edit" title="Modifica">
                                <FontAwesomeIcon :icon="['fas', 'edit']" />
                            </button>

                            <!-- Pulsante Elimina -->
                            <button @click="onDelete(api)" class="btn btn-delete" title="Elimina">
                                <FontAwesomeIcon :icon="['fas', 'trash']" />
                            </button>

                            <!-- Pulsante Esegui -->
                            <button @click="onExecute(api)" class="btn btn-run" title="Esegui">
                                <FontAwesomeIcon :icon="['fas', 'play']" />
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style lang="css">
@import "../../../css/table.css";
@import "../../../css/box.css";
</style>
