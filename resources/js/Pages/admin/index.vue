<template>
    <AdminLayout>
        <v-container fluid>
            <v-row class="mb-4">
                <v-col cols="6" v-for="event in report" :key="event.id">
                    <v-card :title="event.name">
                        <v-expansion-panels density="compact">
                            <v-expansion-panel
                                density="compact"
                                v-for="grandstand in event.grandstands"
                                :key="grandstand.id"
                                :title="grandstand.name"
                            >
                                <v-expansion-panel-text>
                                    <v-list density="compact">
                                        <v-list-item title="Vendios">
                                            <template v-slot:append>
                                                <v-avatar color="grey">
                                                    {{ grandstand.seatsSold }}
                                                </v-avatar>
                                            </template>
                                        </v-list-item>

                                        <v-list-item title="Reservados">
                                            <template v-slot:append>
                                                <v-avatar color="grey">
                                                    {{
                                                        grandstand.seatsReserved
                                                    }}
                                                </v-avatar>
                                            </template>
                                        </v-list-item>

                                        <v-list-item title="Disponibles">
                                            <template v-slot:append>
                                                <v-avatar color="grey">
                                                    {{
                                                        grandstand.seatsAvailable
                                                    }}
                                                </v-avatar>
                                            </template>
                                        </v-list-item>

                                        <v-list-item title="Total">
                                            <template v-slot:append>
                                                <v-avatar color="grey">
                                                    {{ grandstand.totalSeats }}
                                                </v-avatar>
                                            </template>
                                        </v-list-item>
                                    </v-list>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </v-card>
                </v-col>
            </v-row>
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

                        <template v-slot:item.status="{ item }">
                            <v-chip
                                label
                                density="comfortable"
                                :color="status[item.status].color"
                                text-color="white"
                            >
                                <small>
                                    {{ status[item.status].label }}
                                </small>
                            </v-chip>
                        </template>

                        <template v-slot:item.customer="{ item }">
                            <v-list nav>
                                <v-list-item
                                    min-width="150px"
                                    :title="`${item.customer.name} ${item.customer.last_name}`"
                                    :subtitle="item.customer.email"
                                >
                                    <v-list-item-subtitle>
                                       <v-icon size="sm" >mdi-phone</v-icon>
                                         {{ item.customer.phone }}
                                    </v-list-item-subtitle>
                                </v-list-item>
                            </v-list>
                        </template>

                        <template v-slot:item.payment_type="{ item }">
                            <v-list nav>
                                <v-list-item
                                    min-width="140px"
                                    :title="` S/.${item.payment_amount} -  ${
                                        item.payment_bank
                                            ? item.payment_bank
                                            : 'Caja'
                                    }`"
                                    :subtitle="item.payment_date"
                                >
                                    <v-list-item-subtitle>
                                        <v-chip label density="compact">
                                            <small>
                                                {{ item.payment_type }}
                                            </small>
                                        </v-chip>
                                    </v-list-item-subtitle>
                                </v-list-item>
                            </v-list>
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

                            <v-btn
                                icon
                                variant="outlined"
                                density="comfortable"
                                class="ml-1"
                                color="blue"
                            >
                                <DialogConfirm
                                    text="¿Está seguro de enviar el correo?"
                                    @onConfirm="
                                        () =>
                                            router.post(
                                                url +
                                                    '/sales/' +
                                                    item[`${primaryKey}`] +
                                                    '/send-email'
                                            )
                                    "
                                />
                                <v-icon
                                    size="x-small"
                                    icon="mdi-email-arrow-right-outline"
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
import axios from "axios";
import { ref } from "vue";
import { router } from "@inertiajs/core";

import AdminLayout from "@/layouts/AdminLayout.vue";
import DialogConfirm from "@/components/DialogConfirm.vue";
import DataTable from "@/components/DataTable.vue";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Array,
    filters: Object,
});
///sales/{id}/send-email
// const dialogDetail = ref(false);

const report = ref(null);

const _ReportEvents = async () => {
    let res = await axios.get("/report-event");
    report.value = res.data;
};

const init = () => {
    _ReportEvents();
};

init();

const status = {
    pending: {
        label: "Pendiente",
        color: "warning",
    },
    completed: {
        label: "Aprobado",
        color: "success",
    },
    canceled: {
        label: "Rechazado",
        color: "error",
    },
};

const primaryKey = "id";
const url = "/a";
</script>
