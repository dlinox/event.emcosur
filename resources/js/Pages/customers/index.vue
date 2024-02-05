<template>
    <v-app id="inspire">
        <Header></Header>

        <v-row no-gutters>
            <v-col cols="12">
                <HeroSlider></HeroSlider>
            </v-col>
            <v-col cols="3">
                <!-- <FlayerSlider></FlayerSlider> -->
            </v-col>
        </v-row>

        <v-main id="tickets" class="bg-grey-lighten-3">
            <v-card rounded="0">
                <v-row class="row">
                    <v-col
                        cols="12"
                        sm="6"
                        md="3"
                        v-for="bank in bankAccounts"
                        :key="bank.name"
                    >
                        <v-card
                            color="transparent"
                            elevation="0"
                            density="compact"
                            class="border-e"
                            rounded="0"
                        >
                            <div
                                class="d-flex flex-no-wrap justify-space-between"
                            >
                                <div>
                                    <v-card-subtitle class="mt-3">
                                        {{ bank.name }}
                                    </v-card-subtitle>
                                    <v-card-title class="">
                                        <small>
                                            {{ bank.account }}
                                        </small>
                                    </v-card-title>
                                </div>
                                <v-avatar class="ma-3" size="50" rounded="1">
                                    <v-img :src="bank.img"></v-img>
                                </v-avatar>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-card>

            <v-container class="my-5">
                <v-row>
                    <v-col md="8">
                        <v-card
                            v-for="event in events"
                            :key="event.id"
                            class="mx-auto mb-5"
                            :title="event.name"
                            subtitle="Venta de butacas"
                        >
                            <template v-slot:prepend>
                                <v-icon
                                    icon="mdi-ticket"
                                    color="primary"
                                ></v-icon>
                            </template>
                            <template v-slot:append>
                                <v-chip label color="primary">
                                    {{ event.date }}
                                </v-chip>
                            </template>
                            <v-card-text>
                                {{ event.description }}
                            </v-card-text>

                            <img src="/images/combos.png" width="100%" />

                            <v-card-actions>
                                <v-btn
                                    link
                                    block
                                    color="primary"
                                    append-icon="mdi-arrow-right"
                                    @click="selectGrandstand(event)"
                                >
                                    <!-- @click="router.get('/sale/' + event.id)" -->
                                    Comprar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col md="4">
                        <FlayerSlider></FlayerSlider>
                    </v-col>
                </v-row>
            </v-container>
            <v-row no-gutters>
                <v-col md="6">
                    <v-card
                        variant="tonal"
                        theme="dark"
                        rounded="0"
                        title="¿Como comprar 100%?"
                    >
                        <iframe
                            width="100%"
                            height="400"
                            src="https://www.youtube.com/embed/Z7SkA8NALrE?si=gT1YPZhSA1RDEj10"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </v-card>
                </v-col>

                <v-col md="6">
                    <v-card
                        variant="tonal"
                        theme="dark"
                        rounded="0"
                        title="¿Como comprar 100%?"
                    >
                        <iframe
                            width="100%"
                            height="400"
                            src="https://www.youtube.com/embed/1ow3EC56PAM?si=wecZ1yPpJVK8D2NP"
                            title="YouTube video player"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                        ></iframe>
                    </v-card>
                </v-col>
            </v-row>
            <!-- <QRCodeVue3
                :width="200"
                :height="200"
                value="https://scholtz.sk"
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
                :cornersSquareOptions="{ type: 'dot', color: '#000000' }"
                :cornersDotOptions="{ type: undefined, color: '#000000' }"
                fileExt="png"
                :download="true"
                myclass="my-qur"
                imgclass="img-qr"
                downloadButton="my-button"
                :downloadOptions="{ name: 'vqr', extension: 'png' }"
            /> -->
        </v-main>

        <v-dialog width="700" v-model="dialog">
            <v-card title="Seleccione una tribuna ">
                <img src="/images/tribunas.png" width="100%" />
                <v-card-text>
                    <v-list-item
                        v-for="grandstand in eventSelected.grandstands"
                        :key="grandstand.id"
                        :title="grandstand.name"
                    >
                        <template v-slot:prepend>
                            <v-avatar color="grey-lighten-1">
                                <v-icon color="white">mdi-seat</v-icon>
                            </v-avatar>
                        </template>

                        <template v-slot:append>
                            <v-btn
                                @click="router.get('/sale/' + grandstand.id)"
                            >
                                Seleccionar
                            </v-btn>
                        </template>
                    </v-list-item>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        text="Cancelar"
                        variant="tonal"
                        color="error"
                        @click="dialog = !dialog"
                    ></v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Header from "./components/Header.vue";
import HeroSlider from "./components/HeroSlider.vue";
import FlayerSlider from "@/Pages/customers/components/FlayerSlider.vue";
import QRCodeVue3 from "qrcode-vue3";
const props = defineProps({
    events: Array,
});

const dialog = ref(false);
const eventSelected = ref(null);

const bankAccounts = [
    {
        name: "Interbank",
        account: "3203005826685",
        img: "/images/banks/interbank.png",
    },
    {
        name: "BCP",
        account: "4952208589077",
        img: "/images/banks/bcp.png",
    },
    {
        name: "BBVA",
        account: "001102290100126813",
        img: "/images/banks/bbva.png",
    },
    {
        name: "YAPE",
        account: "986868650",
        img: "/images/banks/yape.jpg",
    },
];

const selectGrandstand = (event) => {
    dialog.value = true;
    eventSelected.value = event;

    console.log(event);
};
//v-carousel auto play
</script>

<style lang="scss">
$primary: #114d76;
$secondary: #00afef;
$third: #faab33;
.footer {
    background-color: $secondary;
    .container {
        padding: 3rem 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        ._logo {
            img {
                width: 100px;
            }
        }
        ._enterprise {
            h3 {
                font-size: 1rem;
                color: #444444;
            }
            ul {
                list-style: none;
                padding: 0;
                li {
                    font-size: 0.8rem;
                    margin-bottom: 0.5rem;
                    a {
                        color: $primary;
                        text-decoration: none;
                        &:hover {
                            text-decoration: underline;
                        }
                    }
                }
            }
        }
    }
    .copy {
        background-color: $primary;
        padding: 0.8rem;
        text-align: center;

        span {
            color: white;
            text-align: center;
            font-size: 0.8rem;
        }
    }
}
</style>
