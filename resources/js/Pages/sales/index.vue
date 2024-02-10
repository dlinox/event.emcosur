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
                                <th class="text-left">Origen</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Pago</th>
                                <th class="text-left">Fecha</th>
                                <th class="text-left">Dia</th>
                                <th class="text-left">Revisar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in salesPending" :key="item.name">
                                <td>
                                    <v-chip
                                        :color="item.payment_type === 'online' ? 'blue' : 'scale'"
                                        text-color="white"
                                        label
                                    >
                                        {{
                                            item.payment_type === "online"
                                                ? "Online"
                                                : "Presencial"
                                        }}
                                    </v-chip>
                                </td>

                                <td>
                                    {{ item.customer.name }}
                                    {{ item.customer.last_name }}
                                </td>
                                <td>
                                    {{ item.payment_transaction_id }} |
                                    {{ item.payment_bank }}
                                </td>
                                <td>{{ item.payment_date }}</td>

                                <td>{{ item.eventName }}</td>

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
        <pre>

            {{ salesPending }}
        </pre>

        <v-dialog v-model="reviewDialog" max-width="800" persistent scrollable>
            <v-card title="Revisar venta">
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="6">
                            <div class="img_wrap">
                                <img
                                    :src="itemReview.payment_image_url"
                                    width="100%"
                                />
                            </div>
                        </v-col>

                        <v-col cols="12" md="6">
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

                            <v-list-item
                                title="Nombres"
                                :subtitle="
                                    itemReview.customer.name +
                                    ' ' +
                                    itemReview.customer.last_name
                                "
                            ></v-list-item>

                            <v-list-item
                                title="Celular"
                                :subtitle="itemReview.customer.phone"
                            ></v-list-item>

                            <v-list-item
                                title="Observaciones"
                                :subtitle="itemReview.observation"
                            ></v-list-item>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="border">
                    <v-spacer></v-spacer>

                    <v-btn color="grey" @click="reviewDialog = false">
                        Cerrar
                    </v-btn>

                    <v-btn color="red">
                        <DialogConfirm
                            text="¿Seguro de rechazar el pago?"
                            @onConfirm="cancel(itemReview)"
                        />
                        Rechazar
                    </v-btn>
                    <v-btn text>
                        <DialogConfirm
                            text="¿Seguro de confirmar el pago?"
                            @onConfirm="confirmOnlinePayment(itemReview)"
                        />
                        Confirmar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </SalesLayout>
</template>
<script setup>
import SalesLayout from "@/layouts/SalesLayout.vue";
import { router } from "@inertiajs/vue3";
import DialogConfirm from "@/components/DialogConfirm.vue";
import { ref } from "vue";
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

const confirmOnlinePayment = (sale) => {
    router.put("/sa/sales/" + sale.id + "/confirm-online-payment", null, {
        onSuccess: () => {
            reviewDialog.value = false;
        },
    });
};

const cancel = (sale) => {
    console.log(sale);

    router.put("/sa/sales/" + sale.id + "/cancel", null, {
        onSuccess: () => {
            reviewDialog.value = false;
        },
    });
};
</script>

<style lang="scss" scoped>
.img_wrap {
    width: 100%;
    height: 400px;
    overflow: hidden;
    position: relative;

    background-color: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center center;
    }
}
</style>
