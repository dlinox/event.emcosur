<template>
    <div class="w-100 d-flex justify-center align-center">
        <v-card
            width="600"
            title="Entrada virtual"
            class="mx-auto print-ticket"
            subtitle="Detalle de la compra"
        >
            <v-card-text class="d-flex justify-center">
                <v-row>
                    <v-col cols="12">
                        <v-list density="compact" nav>
                            <v-list-item
                                v-for="(item, index) in saleDetail"
                                class="border-b py-1"
                                :key="index"
                                :subtitle="item.eventName"
                            >
                                <v-list-item-title>
                                    <v-chip color="blue" label>
                                        <strong> {{ item.seatName }} </strong>
                                    </v-chip>
                                    - {{ item.grandstandName }}
                                </v-list-item-title>
                                <template v-slot:append>
                                    <v-icon size="30" color="blue"
                                        >mdi-ticket</v-icon
                                    >
                                </template>
                            </v-list-item>
                        </v-list>
                    </v-col>
                    <v-col cols="12" class="d-flex justify-center">
                        <QRCodeVue3
                            :width="320"
                            :height="320"
                            :value="hash"
                            :qrOptions="{
                                typeNumber: 0,
                                mode: 'Byte',
                                errorCorrectionLevel: 'H',
                            }"
                            :imageOptions="{
                                hideBackgroundDots: true,
                                imageSize: 0.4,
                                margin: 0,
                            }"
                            :dotsOptions="{
                                type: 'square',
                                color: '#26249a',
                                gradient: {
                                    type: 'linear',
                                    rotation: 0,
                                    colorStops: [
                                        { offset: 0, color: '#000000' },
                                        { offset: 1, color: '#000000' },
                                    ],
                                },
                            }"
                            :backgroundOptions="{ color: '#ffffff' }"
                            :cornersSquareOptions="{
                                type: 'dot',
                                color: '#000000',
                            }"
                            :cornersDotOptions="{
                                type: undefined,
                                color: '#000000',
                            }"
                            fileExt="png"
                            myclass="my-qur"
                            imgclass="img-qr"
                            :download="true"
                            download-button="btn-download"
                            :download-options="{
                                name: 'qr-candelaria-2024',
                                extension: 'png',
                            }"
                        />
                    </v-col>
                    <v-col cols="12" class="">
                        <v-btn
                            @click="print"
                            block
                            class="no-print"
                            color="primary"
                            dark
                        >
                            Imprimir
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </div>
</template>
<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { router, useForm } from "@inertiajs/vue3";
import QRCodeVue3 from "qrcode-vue3";
const props = defineProps({
    hash: [String],
    saleDetail: [Array, Object],
});

const print = () => {
    window.print();
};
</script>
<style lang="scss">
.btn-download {
    width: 100%;
    padding: 20px;
    background-color: #aaa;
    color: white;
    content: "Descargar";
    @media print {
        display: none;
    }
}
//imprieme el card
.print-ticket {
    @media print {
        display: block;
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none;
        border: none;
    }
}
.no-print {
    @media print {
        display: none;
    }
}
</style>
