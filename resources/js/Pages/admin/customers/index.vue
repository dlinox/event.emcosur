<template>
    <AdminLayout>
        <HeadingPage title="Clientes" subtitle="Gestion de clientes">
            <template #actions>
                <!-- <BtnDialog title="Registrar Usuario" width="700px">
                    <template v-slot:activator="{ dialog }">
                        <v-btn
                            @click="dialog"
                            prepend-icon="mdi-plus"
                            variant="flat"
                        >
                            Nuevo
                        </v-btn>
                    </template>
                    <template v-slot:content="{ dialog }">
                        <create
                            :form-structure="formStructure"
                            @on-cancel="dialog"
                            :url="url"
                        />
                    </template>
                </BtnDialog> -->
            </template>
        </HeadingPage>
        <v-container fluid class="pt-0">
            <v-card>
                <DataTable
                    :headers="headers"
                    :items="items"
                    with-action
                    :url="url"
                >
                    <template v-slot:header="{ filter }">
                        <div class="pa-3">
                            <v-row justify="end">
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="filter.search"
                                        label="Buscar"
                                    />
                                </v-col>
                            </v-row>
                        </div>
                    </template>

   

                    <template v-slot:action="{ item }">
                        <BtnDialog title="Editar" width="500px">
                            <template v-slot:activator="{ dialog }">
                                <v-btn
                                    color="info"
                                    icon
                                    variant="tonal"
                                    density="comfortable"
                                    @click="dialog"
                                >
                                    <v-icon
                                        size="18"
                                        icon="mdi-pencil"
                                    ></v-icon>
                                </v-btn>
                            </template>
                            <template v-slot:content="{ dialog }">
                                <create
                                    :form-structure="
                                        formStructure.filter(
                                            (field) => field.key !== 'password'
                                        )
                                    "
                                    @on-cancel="dialog"
                                    :form-data="item"
                                    :edit="true"
                                    :url="url + '/' + item[`${primaryKey}`]"
                                />
                            </template>
                        </BtnDialog>

                
                    </template>
                </DataTable>
            </v-card>
        </v-container>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import HeadingPage from "@/components/HeadingPage.vue";
import BtnDialog from "@/components/BtnDialog.vue";
import DialogConfirm from "@/components/DialogConfirm.vue";
import DataTable from "@/components/DataTable.vue";
import { router } from "@inertiajs/core";
import create from "./create.vue";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Object,
    filters: Object,
    areas: Array,
    permissions: Array,
});

const primaryKey = "id";
const url = "/a/customers";
const formStructure = [
    {
        key: "name",
        label: "Nombre",
        type: "text",
        required: true,
        cols: 12,
        colLg: 6,
        default: "",
    },
    {
        key: "last_name",
        label: "Apellido",
        type: "text",
        required: true,
        cols: 12,
        colLg: 6,
        default: "",
    },

    {
        key: "email",
        label: "Correo Electrónico",
        type: "text",
        required: true,
        cols: 12,
        colLg: 6,
        default: "",
    },
    {
        key: "phone",
        label: "Teléfono",
        type: "text",
        required: true,
        cols: 12,
        colLg: 6,
        default: "",
    },
];
</script>
