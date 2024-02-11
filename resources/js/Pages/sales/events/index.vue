<template>
    <SalesLayout title="Eventos">
        <v-toolbar title="Lista de evento" density="compact" rounded="0">
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="router.get('/a/events/create')">
                Nuevo
            </v-btn>
        </v-toolbar>
        <v-container fluid>
            <!-- <v-table>
                <thead>
                    <tr>
                        <th class="text-left">Titulo</th>
                        <th class="text-left">Fecha</th>
                        <th class="text-left">Ciudad</th>
                        <th class="text-left">Vender</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.name">
                        <td>{{ item.name }}</td>
                        <td>{{ item.date }}</td>
                        <td>{{ item.ubication }}</td>
                        <td>
                            <v-btn @click="router.get('/sa/events/' + item.id)"
                                >Vender</v-btn
                            >
                        </td>
                    </tr>
                </tbody>
            </v-table> -->
            <v-row>
                <v-col cols="12"  md="6" v-for="item in items" :key="item.name">
                    <v-card  :title="item.name">
                        <div class="mt-2 px-1" v-for="g in item.grandstands" :key="g.id">

                            <v-btn variant="tonal" block @click="router.get('/sa/events/' + item.id + '/grandstand/' + g.id)">
                                {{ g.name }} 
                            
                            </v-btn>
                        </div>

                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </SalesLayout>
</template>
<script setup>
import SalesLayout from "@/layouts/SalesLayout.vue";

import { router } from "@inertiajs/vue3";
const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Array,
    filters: Object,
});

const selectGrandstand = (event) => {
    router.get("/sale/" + event.id);
};
</script>
