<template>
    <ControlLayout>
        <v-container fluid>
            <!-- <v-card title="Lector QR" max-width="600px" class="mx-auto">
                <v-card-text>
                    <qrcode-stream @detect="onDetect"></qrcode-stream>
                </v-card-text>
            </v-card> -->

            <v-card title="Buscar entrada" max-width="600px" class="mx-auto">
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <v-btn
                                block
                                variant="tonal"
                                @click="dialogQr = true"
                            >
                                <v-icon>mdi-qrcode-scan</v-icon>
                                Escanear QR
                            </v-btn>
                        </v-col>

                        <v-col cols="9">
                            <v-text-field
                                v-model="searchCustomers"
                                label="Ingrese DNI, nombres, correo o celular"
                                :disabled="searchLoading"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="3">
                            <v-btn
                                color="primary"
                                block
                                @click="searchCustomer"
                                :loading="searchLoading"
                            >
                                buscar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <v-card class="mt-4">
                <v-card-title>
                    <small class="text-sm">Resultados</small>
                </v-card-title>

                <v-list nav>
                    <v-list-item
                        v-for="(item, index) in searchResults"
                        :key="index"
                        class="border-b"
                        @click="getDetails(item.hash)"
                    >
                        <v-list-item-subtitle>
                            <b> {{ item.event_name }} </b>
                        </v-list-item-subtitle>
                        <v-list-item-title>
                            [{{ item.document_number }}] {{ item.name }}
                            {{ item.last_name }}
                        </v-list-item-title>
                        <v-list-item-subtitle>
                            <span class="me-1">
                                <v-icon>mdi-email</v-icon>
                                {{ item.email }}
                            </span>
                            -
                            <span>
                                <v-icon>mdi-phone</v-icon>
                                {{ item.phone }}
                            </span>
                        </v-list-item-subtitle>
                        <template v-slot:append>
                            <v-btn icon variant="tonal" density="comfortable">
                                {{ item.total_sales }}
                            </v-btn>
                        </template>
                    </v-list-item>
                </v-list>
            </v-card>
        </v-container>
        <V-dialog v-model="dialogQr">
            <v-card title="Lector QR" mx-width="600px" class="mx-auto">
                <v-card-text>
                    <qrcode-stream @detect="onDetect"></qrcode-stream>
                </v-card-text>
                <v-card-actions>
                    <v-btn
                        block
                        color="error"
                        text
                        @click="dialogQr = false"
                        variant="tonal"
                    >
                        Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </V-dialog>
        <V-dialog v-model="dialogDatails" :scrim="false" fullscreen>
            <v-card>
                <v-card-title>
                    <span class="text-h5">Detalles de la venta</span>
                </v-card-title>
                <v-divider></v-divider>

                <v-card-text>
                    <v-alert
                        variant="tonal"
                        :type="statusSale[customer?.saleStatus].color"
                        :color="statusSale[customer?.saleStatus].color"
                    >
                        {{ statusSale[customer?.saleStatus].text }}
                    </v-alert>

                    <v-list-item subtitle="Cliente">
                        <v-list-item-title>
                            {{ customer?.name }} {{ customer?.last_name }}
                        </v-list-item-title>
                    </v-list-item>

                    <v-list-item subtitle="Celuar">
                        <v-list-item-title>
                            {{ customer?.phone }}
                        </v-list-item-title>
                    </v-list-item>

                    <v-list-item subtitle="Celuar">
                        <v-list-item-title>
                            {{ customer?.email }}
                        </v-list-item-title>
                    </v-list-item>
                </v-card-text>
                <v-list
                    density="compact"
                    nav
                    v-if="customer?.saleStatus === 'completed'"
                >
                    <v-list-item
                        v-for="(item, index) in details"
                        class="border-b py-1"
                        :key="index"
                        :subtitle="item?.eventName"
                    >
                        <v-list-item-title>
                            <v-chip color="blue" label>
                                <strong> {{ item?.seatName }} </strong>
                            </v-chip>
                            - {{ item?.grandstandName }}
                        </v-list-item-title>
                        <template v-slot:append>
                            <v-btn
                                :color="
                                    item?.seatIsUsed ? 'success' : 'primary'
                                "
                                variant="tonal"
                                density="comfortable"
                                @click="
                                    item?.seatIsUsed
                                        ? null
                                        : markAsUsed(item?.seatId, index)
                                "
                            >
                                {{ item.seatIsUsed ? "Usado" : "Entro" }}
                            </v-btn>
                        </template>
                    </v-list-item>
                </v-list>

                <v-card-actions>
                    <v-btn
                        color="error"
                        variant="tonal"
                        @click="dialogDatails = false"
                    >
                        Cerrar
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" text @click="markAsAllUsed">
                        Marcar todos 
                    </v-btn>
                </v-card-actions>
            </v-card>
        </V-dialog>
    </ControlLayout>
</template>
<script setup>
import ControlLayout from "@/layouts/ControlLayout.vue";
import { ref } from "vue";
import axios from "axios";
import { QrcodeStream } from "vue-qrcode-reader";

const props = defineProps({
    title: String,
    subtitle: String,
    items: Object,
    headers: Array,
    filters: Object,

    users: [String, Number],
    doctors: [String, Number],
    posts: [String, Number],
});

const statusSale = {
    completed: {
        color: "success",
        text: "Completado",
    },
    pending: {
        color: "warning",
        text: "Pendiente",
    },
    canceled: {
        color: "error",
        text: "Cancelado",
    },
};

const dialogQr = ref(false);
const dialogDatails = ref(false);
// const details = ref(null);
const qrResult = ref(null);

const onDetect = async (decoded) => {
    qrResult.value = decoded;
    await getDetails(decoded[0].rawValue);
};

const details = ref(null);
const customer = ref(null);
const loading = ref(false);

const getDetails = async (id) => {
    details.value = null;
    customer.value = null;
    loading.value = true;
    let res = await axios.get(`/co/show-sale-details/${id}`);
    details.value = res.data.data.saleDetail;
    customer.value = res.data.data.customer;
    
    loading.value = false;

    dialogDatails.value = true;
};

//Route::put('/sales/{id}/mark-as-used', [SaleController::class, 'markAsUsed'])->name('mark-as-used');

const markAsUsed = async (id, index) => {
    let res = await axios.post(`/co/sales/mark-as-used`, {
        ids: [id],
    });

    if (res.data.success === "ok") {
        details.value[index].seatIsUsed = true;
    }
};

const markAsAllUsed = async () => {
    let ids = details.value.map((item) => item.seatId);

    let res = await axios.post(`/co/sales/mark-as-used`, {
        ids: ids,
    });

    if (res.data.success === "ok") {
        details.value.forEach((item) => {
            item.seatIsUsed = true;
        });
    }

    console.log(res);
};

//Route::get('/search-customer', [CustomerController::class, 'searchCustomer'])->name('search-customer')->middleware('auth');

const searchCustomers = ref("");
const searchResults = ref([]);
const searchLoading = ref(false);
const searchCustomer = async () => {
    searchLoading.value = true;
    let res = await axios.get(`/search-customer`, {
        params: {
            search: searchCustomers.value,
        },
    });

    searchResults.value = res.data;
    if (res.data.length === 0) {
        alert("No se encontraron resultados");
    }

    searchLoading.value = false;
};

const init = async () => {
    const hash =
        "eyJpdiI6Ik4vRjlMSW56cHRuY2ZaV1hZTlBBTGc9PSIsInZhbHVlIjoiOUVLUjRISXprOFVEQ0FqZ1Z2SDEyZz09IiwibWFjIjoiZDM3MTEzMzA1OWZjODJhYWJlMmJiMmYyMjJkMDI5MDNiODkzOTBiY2MwOTE2MDMxODkzNmVkMzNiOGY5MGZjNSIsInRhZyI6IiJ9";
    // await getDetails(hash);
    // dialogDatails.value = true;
};

init();
</script>
