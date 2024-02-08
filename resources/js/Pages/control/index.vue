<template>
    <AdminLayout>
        <v-container fluid>
            .sss
            <v-card>
                <v-card-text>

                    <qrcode-stream @detect="onDetect"></qrcode-stream>
                </v-card-text>
            </v-card>
        </v-container>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";
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

const onDetect = (decoded) => {
    console.log(decoded);
};
</script>
