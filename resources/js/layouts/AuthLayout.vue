<template>
    <Head :title="title + ' | CANDELARIA  2024'" />
    <v-app app class="w-100 h-screen d-flex justify-center align-center">
        <slot />

        <v-snackbar v-model="snackbar" multi-line color="success" vertical>
            {{ flash.success }}

            <template v-slot:actions>
                <v-btn
                    color="dark"
                    variant="text"
                    @click="snackbar = false"
                    icon="mdi-close"
                ></v-btn>
            </template>
        </v-snackbar>

        <v-snackbar v-model="snackbarError" vertical multi-line color="error">
            <v-container>
                <v-expansion-panels>
                    <v-expansion-panel
                        elevation="0"
                        class="bg-transparent w-100"
                        :text="error.exception"
                    >
                        <v-expansion-panel-title
                            expand-icon="mdi-plus"
                            collapse-icon="mdi-minus"
                        >
                            {{ error.error }}
                        </v-expansion-panel-title>
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-container>
            <template v-slot:actions>
                <v-btn
                    class="px-3"
                    color="white"
                    variant="tonal"
                    @click="snackbarError = false"
                >
                    Cerrar
                </v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>

<script setup>
import { Head, usePage } from "@inertiajs/vue3";

import { ref, computed, watch } from "vue";

defineProps({
    title: String,
});


const flash = computed(() => usePage().props?.flash);
const error = computed(() => usePage().props?.errors);
const snackbar = ref(false);
const snackbarError = ref(false);

watch(
    () => flash.value,
    (newValue) => {
        if (newValue && newValue.success) {
            snackbar.value = true;
        } else {
            snackbar.value = false;
        }
    }
);

watch(
    () => error.value,
    (newValue) => {
        if (newValue.exception || newValue.error) {
            snackbarError.value = true;
        } else {
            snackbarError.value = false;
        }
    }
);
</script>
