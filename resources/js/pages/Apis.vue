<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import ShowApi from "../components/table/ShowApi.vue";
import ModifyApi from "../components/modal/ModifyApi.vue";
import Api from "../interfaces/Api";

const props = defineProps<{ apis: Api[]; flash: { error?: string ,success?: string } }>();
const apis = ref(props.apis);
const showModal = ref(false);
const selectedApi = ref<Api | null>(null);

// Mostra messaggi flash con Swal
onMounted(() => {
    console.log(props);
    if (props.flash?.success) {
        Swal.fire("Successo!", props.flash.success, "success");
    } else if (props.flash?.error) {
        Swal.fire("Errore!", props.flash.error, "error");
    }
});

const openAddModal = () => {
    selectedApi.value = null;
    showModal.value = true;
};

const openEditModal = (api: Api) => {
    selectedApi.value = { ...api };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedApi.value = null;
};

const addOrEditApi = (newApi: Api) => {
    const payload: Record<string, any> = {
        url: newApi.url,
        description: newApi.description,
        tags: newApi.tags
    };
    if (selectedApi.value) {
        Inertia.put(`/apis/${selectedApi.value.id}`, payload);
    } else {
        Inertia.post("/apis", payload);
    }
};

const executeApi = (api: Api) => {
    Inertia.post(`/apis/${api.id}/execute`);
};

const deleteApi = (api: Api) => {
    Swal.fire({
        title: "Sei sicuro?",
        text: "Questa azione eliminerà l'API definitivamente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sì, elimina!",
        cancelButtonText: "Annulla",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(`/apis/${api.id}`);
        }
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8 w-full">
        <div class="flex justify-between items-center mb-6 w-100">
            <h1 class="text-2xl font-bold">Gestione API</h1>
            <button
                class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
                @click="openAddModal"
            >
                Aggiungi API
            </button>
        </div>

        <ShowApi
            :apis="apis"
            :on-edit="openEditModal"
            :on-delete="deleteApi"
            :on-execute="executeApi"
        />

        <ModifyApi
            v-if="showModal"
            :on-add="addOrEditApi"
            :on-close="closeModal"
            :api="selectedApi"
        />
    </div>
</template>
