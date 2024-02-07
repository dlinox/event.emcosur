<template>
    <AdminLayout>
        <v-container fluid>
            <v-card>
                <v-card-item>
                    <DataTable
                        :headers="headers"
                        :items="items"
                        with-action
                        :url="url"
                    >
                        <template v-slot:header="{ filter }">
                            <v-row class="py-3" justify="end">
                                <v-col cols="6"> </v-col>
                                <v-col cols="6">
                                    <v-text-field
                                        v-model="filter.search"
                                        label="Buscar"
                                    />
                                </v-col>
                            </v-row>
                        </template>

                        <template v-slot:item.event="{ item }">
                            {{ item.event.name }}
                        </template>

                        <template v-slot:item.customer="{ item }">
                            {{ item.customer.name }}
                        </template>

                        <template v-slot:item.user="{ item }">
                            {{ item.user ? item.user.name : "-" }}
                        </template>

                        <template v-slot:action="{ item }">
                            <v-dialog max-width="800" scrollable>
                                <template v-slot:activator="{ props }">
                                    <v-btn
                                        v-bind="props"
                                        icon="mdi-plus"
                                        variant="outlined"
                                        density="comfortable"
                                        class="ml-1"
                                    />
                                </template>

                                <template v-slot:default="{ isActive }">
                                    <v-card title="Revisar venta">
                                        <v-card-text>
                                            <v-row>
                                                <v-col cols="12" md="6">
                                                    <div class="img_wrap">
                                                        <img
                                                            :src="
                                                                item.payment_image_url
                                                            "
                                                            width="100%"
                                                        />
                                                    </div>
                                                </v-col>

                                                <v-col cols="12" md="6">
                                                    <v-list-item
                                                        title="Banco"
                                                        :subtitle="
                                                            item.payment_bank
                                                        "
                                                    ></v-list-item>
                                                    <v-list-item
                                                        title="Serie / Número de transaccion"
                                                        :subtitle="
                                                            item.payment_transaction_id
                                                        "
                                                    ></v-list-item>
                                                    <v-list-item
                                                        title="Fecha de pago"
                                                        :subtitle="
                                                            item.payment_date
                                                        "
                                                    ></v-list-item>
                                                    <v-list-item
                                                        title="Monto"
                                                        :subtitle="
                                                            item.payment_amount
                                                        "
                                                    ></v-list-item>

                                                    <v-list-item
                                                        title="Nombres"
                                                        :subtitle="
                                                            item.customer.name +
                                                            ' ' +
                                                            item.customer
                                                                .last_name
                                                        "
                                                    ></v-list-item>

                                                    <v-list-item
                                                        title="Celular"
                                                        :subtitle="
                                                            item.customer.phone
                                                        "
                                                    ></v-list-item>

                                                    <v-divider
                                                        class="my-3"
                                                    ></v-divider>
                                                    <h6></h6>

                                                    <v-chip
                                                        v-for="detail in item.sale_details"
                                                        :key="detail.id"
                                                        color="primary"

                                                        class="ma-1"
                                                    >
                                                        {{
                                                            detail.seat
                                                                .grandstand_name +
                                                            " - " +
                                                            detail.seat.name
                                                        }}
                                                    </v-chip>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>

                                        <!-- <v-card-actions class="border">
                                            <v-spacer></v-spacer>

                                            <v-btn
                                                color="grey"
                                                @click="reviewDialog = false"
                                            >
                                                Cerrar
                                            </v-btn>

                                            <v-btn color="red">
                                                <DialogConfirm
                                                    text="¿Seguro de rechazar el pago?"
                                                    @onConfirm="
                                                        cancel(itemReview)
                                                    "
                                                />
                                                Rechazar
                                            </v-btn>
                                            <v-btn text>
                                                <DialogConfirm
                                                    text="¿Seguro de confirmar el pago?"
                                                    @onConfirm="
                                                        confirmOnlinePayment(
                                                            itemReview
                                                        )
                                                    "
                                                />
                                                Confirmar
                                            </v-btn>
                                        </v-card-actions> -->
                                    </v-card>
                                </template>
                            </v-dialog>

                            <v-btn
                                icon
                                variant="outlined"
                                density="comfortable"
                                class="ml-1"
                                color="red"
                            >
                                <DialogConfirm
                                    @onConfirm="
                                        () =>
                                            router.delete(
                                                url +
                                                    '/sales/' +
                                                    item[`${primaryKey}`]
                                            )
                                    "
                                />
                                <v-icon
                                    size="x-small"
                                    icon="mdi-delete-empty"
                                ></v-icon>
                            </v-btn>
                        </template>
                    </DataTable>
                </v-card-item>
            </v-card>
        </v-container>

    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";

import DialogConfirm from "@/components/DialogConfirm.vue";
import DataTable from "@/components/DataTable.vue";

import { router } from "@inertiajs/core";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Array,
    filters: Object,
});

// const dialogDetail = ref(false);

const primaryKey = "id";
const url = "/a";
</script>
