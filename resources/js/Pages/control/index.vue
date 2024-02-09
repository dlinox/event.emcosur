<template>
    <AdminLayout>
        <v-container fluid>
            <v-card title="Lector QR">
                <v-card-text>
                    <qrcode-stream @detect="onDetect"></qrcode-stream>
             
                </v-card-text>
            </v-card>
        </v-container>
        <V-dialog v-model="dialogDatails" width="600">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Detalles de la venta</span>
                </v-card-title>
                <v-card-text>
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
                <v-list density="compact" nav>
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
                    <v-spacer></v-spacer>
                    <v-btn color="primary" text @click="markAsAllUsed">
                        Marcar todos como usados
                    </v-btn>
                </v-card-actions>
            </v-card>
        </V-dialog>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
import { ref } from "vue";
import axios from "axios";

import { QrcodeStream, QrcodeDropZone, QrcodeCapture } from "vue-qrcode-reader";

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

const dialogDatails = ref(false);
// const details = ref(null);
const qrResult = ref(null);

const onDetect = async (decoded) => {
    console.log(decoded);
    qrResult.value = decoded;

    details.value = null;
    customer.value = null;

    await getDetails(decoded[0].rawValue);
    dialogDatails.value = true;
};

const details = ref(null);
const customer = ref(null);
const loading = ref(false);

const getDetails = async (id) => {
    loading.value = true;
    let res = await axios.get(`/co/show-sale-details/${id}`);
    details.value = res.data.data.saleDetail;
    customer.value = res.data.data.customer;
    console.log(details.value);
    loading.value = false;
};

//Route::put('/sales/{id}/mark-as-used', [SaleController::class, 'markAsUsed'])->name('mark-as-used');

const markAsUsed = async (id, index) => {
    let res = await axios.post(`/co/sales/mark-as-used`, {
        ids: [id],
    });

    if (res.data.success === "ok") {
        details.value[index].seatIsUsed = true;
    }

    console.log(res);
};

const markAsAllUsed = async () => {
    let ids = details.value.map((item) => item.seatId);

    console.log(ids);
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

const init = async () => {
    const hash =
        "eyJpdiI6Ik4vRjlMSW56cHRuY2ZaV1hZTlBBTGc9PSIsInZhbHVlIjoiOUVLUjRISXprOFVEQ0FqZ1Z2SDEyZz09IiwibWFjIjoiZDM3MTEzMzA1OWZjODJhYWJlMmJiMmYyMjJkMDI5MDNiODkzOTBiY2MwOTE2MDMxODkzNmVkMzNiOGY5MGZjNSIsInRhZyI6IiJ9";
    // await getDetails(hash);
    // dialogDatails.value = true;
};

init();
</script>
