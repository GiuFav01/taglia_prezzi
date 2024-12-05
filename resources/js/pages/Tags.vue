<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
import ShowTag from "../components/table/ShowTag.vue";
import ModifyTag from "../components/modal/ModifyTag.vue";

const props = defineProps<{ tags: Tag[]; flash: { success?: string; error?: string } }>();
const tags = ref<Tag[]>(props.tags);

const showModal = ref(false);
const selectedTag = ref<Tag | null>(null);

onMounted(() => {
    // Mostra un messaggio SweetAlert se esiste un messaggio flash
    if (props.flash) {
        console.log(props.flash);
        if (props.flash.error){
            Swal.fire("Errore!", props.flash.error, "error");
        } else if (props.flash.success){
            Swal.fire("Successo!", props.flash.success, "success");
        }
    }
});

const openAddModal = () => {
    selectedTag.value = null;
    showModal.value = true;
};

const openEditModal = (tag: Tag) => {
    selectedTag.value = { ...tag };
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedTag.value = null;
};

const addOrEditTag = (newTag: Tag) => {
    const payload = {
        name: newTag.name,
        description: newTag.description,
    };

    if (selectedTag.value) {
        Inertia.put(`/tags/${selectedTag.value.id}`, payload);
    } else {
        Inertia.post("/tags", payload);
    }
};

const deleteTag = (tag: Tag) => {
    Swal.fire({
        title: "Sei sicuro?",
        text: "Questa azione eliminerà il tag definitivamente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sì, elimina!",
        cancelButtonText: "Annulla",
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(`/tags/${tag.id}`);
        }
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 p-8 w-full">
        <div class="flex justify-between items-center mb-6 w-100">
            <h1 class="text-2xl font-bold">Gestione Tag</h1>
            <button
                class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
                @click="openAddModal"
            >
                Aggiungi Tag
            </button>
        </div>

        <ShowTag :tags="tags" :on-edit="openEditModal" :on-delete="deleteTag" />

        <ModifyTag
            v-if="showModal"
            :on-add="addOrEditTag"
            :on-close="closeModal"
            :tag="selectedTag"
        />
    </div>
</template>
