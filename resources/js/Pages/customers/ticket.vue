<template>
    <AuthLayout title="Login">
        <div class="w-100 h-screen d-flex justify-center align-center">
            <v-card width="400" title="Entrada virtual" class="mx-auto">
                <v-card-text class="d-flex justify-center">
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
                        download="qr-code.png"
                        download-button="true"
                        
                        :download-options="{
                            name: 'qr-code',
                            extension: 'png',
                        }"
                    />
                </v-card-text>
            </v-card>
        </div>
    </AuthLayout>
</template>
<script setup>
import AuthLayout from "@/layouts/AuthLayout.vue";
import { router, useForm } from "@inertiajs/vue3";
import QRCodeVue3 from "qrcode-vue3";
const props = defineProps({
    hash: [String],
});
</script>
<style lang="scss">
.true {
    width: 100%;
    padding: 20px;
    background-color: #AAA;
    color: white;
}
</style>
