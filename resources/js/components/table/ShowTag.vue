<template>
    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full border-collapse">
            <!-- Intestazione della tabella -->
            <thead class="table-header">
                <tr>
                    <th class="table-header-cell">Nome</th>
                    <th class="table-header-cell">Descrizione</th>
                    <th class="table-header-cell">Azioni</th>
                </tr>
            </thead>

            <!-- Corpo della tabella -->
            <tbody>
                <tr v-for="tag in tags" :key="tag.id" class="table-row">
                    <td class="table-cell">{{ tag.name }}</td>
                    <td class="table-cell">{{ tag.description }}</td>
                    <td class="table-cell">
                        <div class="actions">
                            <!-- Pulsante Modifica -->
                            <button @click="onEdit(tag)" class="btn btn-edit" title="Modifica">
                                <FontAwesomeIcon :icon="['fas', 'edit']" />
                            </button>

                            <!-- Pulsante Elimina -->
                            <button @click="onDelete(tag)" class="btn btn-delete" title="Elimina">
                                <FontAwesomeIcon :icon="['fas', 'trash']" />
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

defineProps<{
    tags: Tag[];
    onEdit: (tag: Tag) => void;
    onDelete: (tag: Tag) => void;
}>();
</script>

<style lang="css">
@import "../../../css/table.css";
</style>
