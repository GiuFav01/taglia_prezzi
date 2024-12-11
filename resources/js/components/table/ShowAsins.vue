<template>
    <div class="bg-white shadow rounded overflow-hidden">
        <table class="min-w-full border-collapse">
            <!-- Intestazione della tabella -->
            <thead class="table-header">
                <tr>
                    <th class="table-header-cell">ASIN</th>
                    <th class="table-header-cell">API Associata</th>
                    <th class="table-header-cell">Azioni</th>
                </tr>
            </thead>

            <!-- Corpo della tabella -->
            <tbody>
                <tr v-for="asin in asins" :key="asin.id" class="table-row">
                    <td class="table-cell">{{ asin.asin }}</td>
                    <td class="table-cell">{{ asin.api?.url.slice(0, 10) + '...' || 'N/A' }}</td>
                    <td class="table-cell">
                        <div class="actions">
                            <button @click="onExecute(asin.asin)" class="btn btn-run" title="Esegui">
                                <FontAwesomeIcon :icon="['fas', 'play']" />
                            </button>
                            <button @click="onSync(asin.asin)" class="btn btn-run" title="Sync">
                                <FontAwesomeIcon :icon="['fas', 'sync']" />
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
import { Asin } from "../../interfaces/Asins";

defineProps<{
    onExecute: (asin: string) => void;
    onSync: (asin: string) => void;
    asins: Asin[];
}>();
</script>

<style lang="css">
@import "../../../css/table.css";
</style>
