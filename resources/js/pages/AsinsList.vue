<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import ShowAsins from "../components/table/ShowAsins.vue";
import { Asin } from "../interfaces/Asins";

const props = defineProps<{ asins: Asin[]; flash: { success?: string; error?: string } }>();
const asins = ref<Asin[]>(props.asins);
console.log(asins);

onMounted(() => {
    if (props.flash) {
        if (props.flash.error) {
            Swal.fire("Errore!", props.flash.error, "error");
        } else if (props.flash.success) {
            Swal.fire("Successo!", props.flash.success, "success");
        }
    }
});

const syncProduct = (asin: string) => {
    Swal.fire({
        title: "Sei sicuro?",
        text: "Questa azione chiamerà l'api product di Amazon ADV ottenendo i dati dell'asin selezionato e aggionrerà il prodotto di shopify, per l'asin scelto",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sì, esegui!",
        cancelButtonText: "Annulla",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.post(`/asins/${asin}/sync`);
        }
    });
}

const getProductAsin = (asin: string) => {
    Swal.fire({
        title: "Sei sicuro?",
        text: "Questa azione chiamerà l'api product di keepa ottenendo i dati dell'asin selezionato",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sì, esegui!",
        cancelButtonText: "Annulla",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.post(`/asins/${asin}/execute`);
        }
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8 w-full">
        <div class="flex justify-between items-center mb-6 w-100">
            <h1 class="text-2xl font-bold">Gestione ASIN</h1>
        </div>

        <ShowAsins
            :asins="asins"
            :on-execute="getProductAsin"
            :on-sync="syncProduct"
        />
    </div>
</template>
