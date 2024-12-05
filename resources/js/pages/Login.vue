<script setup lang="ts">
import { useForm } from "@inertiajs/inertia-vue3";

const form = useForm({
    email: "",
    password: "",
    rememberMe: false,
});

const handleSubmit = () => {
    form.post('/login', {
        onSuccess: () => {
            console.log("Login eseguito con successo");
        },
        onError: () => {
            console.error("Errore durante il login:", form.errors);
        },
    });
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 w-full">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-700 text-center mb-6">Accedi al tuo account</h2>

            <form @submit.prevent="handleSubmit">
                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        placeholder="Inserisci la tua email"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none border-gray-300"
                        required
                    />
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        id="password"
                        placeholder="Inserisci la tua password"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none border-gray-300"
                        required
                    />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300"
                >
                    Accedi
                </button>
            </form>
        </div>
    </div>
</template>
