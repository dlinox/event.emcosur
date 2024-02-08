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
                <pre>
                    {{ details }}
                </pre>
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

const onDetect = (decoded) => {
    console.log(decoded);
};

const details = ref(null);
const loading = ref(false);

const getDetails = async (id) => {
    loading.value = true;
    let res = await axios.get(`/co/show-sale-details/${id}`);
    details.value = res.data;
    console.log(details.value);
    loading.value = false;
};

const init = async () => {
    await getDetails(25);
};

init();
</script>
