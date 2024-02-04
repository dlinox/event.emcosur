<template>
    <SalesLayout>
        <v-container fluid>
            <v-row>
                <v-col md="6" v-for="event in events" :key="event.id">
                    <v-card
                        class="mx-auto"
                        :title="event.name"
                        subtitle="Venta de butacas"
                    >
                        <template v-slot:prepend>
                            <v-icon icon="mdi-ticket" color="primary"></v-icon>
                        </template>
                        <template v-slot:append>
                            <v-chip label color="primary">
                                {{ event.date }}
                            </v-chip>
                        </template>
                        <v-card-text>
                            {{ event.description }}
                        </v-card-text>

                        <v-card-actions>
                            <v-btn
                                link
                                block
                                color="primary"
                                append-icon="mdi-arrow-right"
                                @click="router.get('/sa/events/' + event.id)"
                            >
                                Vender
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
                <v-col cols="12">
                    <v-table>
                        <thead>
                            <tr>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Pago</th>
                                <th class="text-left">Fecha</th>
                                <th class="text-left">Revisar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in salesPending" :key="item.name">
                                <td>
                                    {{ item.customer.name }}
                                    {{ item.customer.last_name }}
                                </td>
                                <td>
                                    {{ item.payment_transaction_id }} |
                                    {{ item.payment_bank }}
                                </td>
                                <td>{{ item.payment_date }}</td>

                                <td>
                                    <v-btn @click="reviewSale(item)"
                                        >Revisar</v-btn
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </v-table>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog v-model="reviewDialog" max-width="600" persistent scrollable>
            <v-card>
                <v-card-title>
                    <span class="headline">Revisar venta</span>
                </v-card-title>

                <v-img :src="itemReview.payment_image_url"></v-img>

                <v-card-text>
                    <v-list-item
                        title="Banco"
                        :subtitle="itemReview.payment_bank"
                    ></v-list-item>
                    <v-list-item
                        title="Serie / Número de transaccion"
                        :subtitle="itemReview.payment_transaction_id"
                    ></v-list-item>
                    <v-list-item
                        title="Fecha de pago"
                        :subtitle="itemReview.payment_date"
                    ></v-list-item>
                    <v-list-item
                        title="Monto"
                        :subtitle="itemReview.payment_amount"
                    ></v-list-item>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="grey" @click="reviewDialog = false"
                        >Cerrar</v-btn
                    >
                    <v-btn color="red" @click="cancel(itemReview)">
                        Rechazar</v-btn
                    >
                    <v-btn text @click="confirmOnlinePayment(itemReview)"
                        >Confirmar</v-btn
                    >
                </v-card-actions>
            </v-card>
        </v-dialog>
    </SalesLayout>
</template>
<script setup>
import SalesLayout from "@/layouts/SalesLayout.vue";
import { router } from "@inertiajs/vue3";
import { defineProps, ref } from "vue";
const props = defineProps({
    salesPending: Array,
    events: Array,
});

const reviewDialog = ref(false);

const itemReview = ref({});

const reviewSale = (sale) => {
    itemReview.value = sale;
    reviewDialog.value = true;
};

//cancel
//  Route::put('/sales/{id}/cancel', [SaleController::class, 'cancel'])->name('cancel');
//confirmOnlinePayment
// Route::put('/sales/{id}/confirm-online-payment', [SaleController::class, 'confirmOnlinePayment'])->name('confirm-online-payment');

const confirmOnlinePayment = (sale) => {
    router.put("/sa/sales/" + sale.id + "/confirm-online-payment", {
        onSuccess: () => {
            reviewDialog.value = false;
        },
    });
};

const cancel = (sale) => {
    router.put("/sa/sales/" + sale.id + "/cancel", {
        onSuccess: () => {
            reviewDialog.value = false;
        },
    });
};
</script>
