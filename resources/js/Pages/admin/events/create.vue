<template>
    <AdminLayout title="Eventos">
        <v-toolbar
            title="Crear nuevo evento"
            density="compact"
            rounded="0"
            extended
        >
            <template v-slot:extension>
                <v-tabs v-model="tab">
                    <v-tab value="1">Evento</v-tab>
                    <v-tab value="2">Tribunas</v-tab>
                </v-tabs>
            </template>
        </v-toolbar>

        <v-window v-model="tab">
            <v-window-item value="1">
                <v-container fluid v-if="tab === '1'">
                    <v-row>
                        <v-col md="8">
                            <v-card>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="form.name"
                                                label="Titulo"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-textarea
                                                v-model="form.description"
                                                label="Descripción"
                                            ></v-textarea>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="
                                                    form.location_reference
                                                "
                                                label="Referencia de ubicación"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-select
                                                v-model="form.ubication"
                                                :items="['Puno', 'Juliaca']"
                                                label="Ubicación"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-col>

                        <v-col md="4">
                            <v-card>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="form.date"
                                                label="Fecha"
                                                type="date"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn
                                                color="primary"
                                                @click="tab = '2'"
                                                block
                                            >
                                                Siguiente
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-window-item>
            <v-window-item value="2">
                <v-container fluid v-if="tab === '2'">
                    <v-row>
                        <v-col md="8">
                            <v-card
                                v-for="(grandstand, index) in form.grandstands"
                                :key="index"
                            >
                                <v-toolbar
                                    density="compact"
                                    :title="'Tribuna N°' + (index + 1)"
                                >
                                    <v-spacer></v-spacer>

                                    <v-btn icon @click="duplicate(index)">
                                        <v-icon>mdi-content-copy</v-icon>
                                    </v-btn>
                                </v-toolbar>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                v-model="grandstand.name"
                                                label="Nombre"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="6">
                                            <v-text-field
                                                v-model="grandstand.capacity"
                                                label="Capacidad"
                                                type="number"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="6">
                                            <v-select
                                                v-model="
                                                    grandstand.rows.capacity
                                                "
                                                :items="rows"
                                                label="Filas"
                                                @update:model-value="
                                                    createSeats(
                                                        grandstand.rows
                                                            .capacity,
                                                        index
                                                    )
                                                "
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                                <v-toolbar density="compact" title="Asientos" />
                                <v-container>
                                    <v-row
                                        v-for="row in grandstand.rows.capacity"
                                        :key="row"
                                    >
                                        <v-col cols="2">
                                            <v-avatar color="primary">
                                                {{ nameRows[row - 1].name }}
                                            </v-avatar>
                                        </v-col>
                                        <v-col cols="5">
                                            <v-text-field
                                                v-model="
                                                    form.grandstands[index].rows
                                                        .seats[row - 1].capacity
                                                "
                                                label="Capacidad"
                                                type="number"
                                            />
                                        </v-col>
                                        <v-col cols="5">
                                            <v-text-field
                                                class=""
                                                v-model="
                                                    form.grandstands[index].rows
                                                        .seats[row - 1].price
                                                "
                                                label="Precio"
                                            />
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-col>
                        <v-col md="4">
                            <v-card>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12">
                                            <v-btn
                                                block
                                                variant="outlined"
                                                @click="addGrandstand()"
                                            >
                                                Añadir Tribuna
                                            </v-btn>
                                        </v-col>

                                        <v-col cols="12">
                                            <v-btn block @click="submit()">
                                                Guardar Evento
                                            </v-btn>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-window-item>
        </v-window>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import CropCompressImage from "@/components/CropCompressImage.vue";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const tab = ref("1");
const previewImg = ref(null);

const rows = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

const nameRows = [
    { name: "A", value: 1 },
    { name: "B", value: 2 },
    { name: "C", value: 3 },
    { name: "D", value: 4 },
    { name: "E", value: 5 },
    { name: "F", value: 6 },
    { name: "G", value: 7 },
    { name: "H", value: 8 },
    { name: "I", value: 9 },
    { name: "J", value: 10 },
];

const form = ref({
    name: "",
    description: "",
    location_reference: "",
    ubication: "Puno",
    image: "",
    date: "",
    grandstands: [
        {
            name: "TRIBUNA ",
            capacity: "2",
            rows: {
                capacity: 5,
                seats: [
                    {
                        row: "A",
                        capacity: 56,
                        price: 120,
                    },
                    {
                        row: "B",
                        capacity: 56,
                        price: 120,
                    },
                    {
                        row: "C",
                        capacity: 56,
                        price: 100,
                    },
                    {
                        row: "D",
                        capacity: 56,
                        price: 100,
                    },
                    {
                        row: "E",
                        capacity: 56,
                        price: 90,
                    },
                ],
            },
        },
    ],
});

const createSeats = (value, indexGrandstand) => {
    form.value.grandstands[indexGrandstand].rows.seats = [];
    for (let i = 0; i < value; i++) {
        form.value.grandstands[indexGrandstand].rows.seats.push({
            row: nameRows[i].name,
            capacity: 0,
            price: 0,
        });
    }
};

const duplicate = (index) => {
    form.value.grandstands.push({ ...form.value.grandstands[index] });
};

const addGrandstand = () => {
    form.value.grandstands.push({
        name: "",
        capacity: 0,
        rows: {
            capacity: 0,
            seats: [],
        },
    });
};

const submit = () => {
    router.post("/a/events", form.value);
};
</script>
