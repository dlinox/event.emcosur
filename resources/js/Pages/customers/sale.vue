<template>
    <CustomerLayout title="Eventos">
        <Header />
        <v-img
            src="/banner.png"
            gradient="to top right, rgba(0,0,0,.2), rgba(0,0,0,.1)"
        ></v-img>

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
                        <div class="d-flex flex-no-wrap justify-space-between">
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
        <v-toolbar :title="item?.name" rounded="0" extended color="primary">
            <template #extension>
                <v-tabs v-model="tab" background-color="primary" dark>
                    <v-tab
                        v-for="(tab_, i) in item?.grandstands"
                        :key="i"
                        @click="mapRows(i)"
                        color="yellow"
                    >
                        {{ tab_.name }}
                    </v-tab>
                </v-tabs>
            </template>
        </v-toolbar>

        <v-window v-model="tab" direction="vertical">
            <v-window-item
                v-for="(tab_, w) in item?.grandstands"
                :key="w"
                :value="w"
                vertical
            >
                <v-container fluid>
                    <v-card>
                        <v-toolbar
                            title="Selecciona tus asientos"
                            density="compact"
                            extension
                        >
                            <template #extension>
                                <v-chip class="" color="primary" label>
                                    <v-icon
                                        start
                                        color="red"
                                        icon="mdi-square-rounded"
                                    ></v-icon>
                                    <small>No disponible</small>
                                </v-chip>

                                <v-chip class="ms-1" color="primary" label>
                                    <v-icon
                                        start
                                        color="warning"
                                        icon="mdi-square-rounded"
                                    ></v-icon>
                                    <small>Reservado</small>
                                </v-chip>

                                <v-chip class="ms-1" color="primary" label>
                                    <v-icon
                                        start
                                        color="primary"
                                        icon="mdi-square-rounded"
                                    ></v-icon>
                                    <small>Seleccionado</small>
                                </v-chip>

                                <v-chip class="ms-1" color="primary" label>
                                    <v-icon
                                        start
                                        color="primary"
                                        icon="mdi-square-rounded-outline"
                                    ></v-icon>
                                    <small>Disponible</small>
                                </v-chip>
                            </template>
                        </v-toolbar>
                        <v-table>
                            <tbody>
                                <tr
                                    v-for="(item, indexRow) in rows"
                                    :key="item.name"
                                    class="pa-1"
                                    :class="
                                        'bg-' + rowsColor[`${item.name}`] + '-4'
                                    "
                                >
                                    <td class="pa-1">
                                        <v-avatar
                                            :color="
                                                rowsColor[`${item.name}`] + '-1'
                                            "
                                        >
                                            {{ item.name }}
                                        </v-avatar>
                                    </td>
                                    <td
                                        class="pa-1"
                                        v-for="(seat, indexSeat) in item.seats"
                                    >
                                        <v-btn
                                            icon
                                            density="comfortable"
                                            :variant="
                                                statusSeat[seat.status].variant
                                            "
                                            :color="
                                                statusSeat[seat.status].color
                                            "
                                            @click="
                                                seat.status === 'sold' ||
                                                seat.status === 'reserved' ||
                                                seat.status === 'displacement'
                                                    ? null
                                                    : onSeatSelected(tab_, seat)
                                            "
                                        >
                                            <v-tooltip
                                                activator="parent"
                                                location="top"
                                            >
                                                <p>
                                                    <b>Precio:</b> S/.
                                                    {{ seat.price }}
                                                </p>
                                                <p>
                                                    <b>Estado</b>
                                                    {{
                                                        seat.status === "sold"
                                                            ? "Vendido"
                                                            : seat.status ===
                                                              "reserved"
                                                            ? "Reservado"
                                                            : seat.status ===
                                                              "selected"
                                                            ? "Seleccionado"
                                                            : seat.status ===
                                                              "displacement"
                                                            ? "Desplazamiento"
                                                            : "Disponible"
                                                    }}
                                                </p>
                                            </v-tooltip>

                                            <small>{{ seat.name }}</small>
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-card>
                </v-container>
            </v-window-item>
        </v-window>

        <div class="calle">
            <span> Av. Simon Bolivar </span>
        </div>

        <v-container fluid>
            <v-row>
                <v-col cols="12" md="4">
                    <v-card>
                        <v-toolbar
                            title="Asientos seleccionados"
                            density="compact"
                        />

                        <v-alert
                            v-if="!seatsSelected.length"
                            type="info"
                            class="ma-3"
                            variant="tonal"
                        >
                            No hay asientos seleccionados
                        </v-alert>
                        <v-card-text>
                            <v-list-item
                                class="mb-3"
                                v-for="item_ in seatsSelected"
                                :key="item_.id"
                                :title="`S/. ${item_.seat.price}`"
                                :subtitle="item_.grandstand.name"
                            >
                                <template #prepend>
                                    <v-avatar
                                        :color="
                                            rowsColor[`${item_.seat.row}`] +
                                            '-1'
                                        "
                                    >
                                        {{ item_.seat.name }}
                                    </v-avatar>
                                </template>
                                <template #append>
                                    <v-btn
                                        variant="tonal"
                                        icon
                                        density="comfortable"
                                        @click="removeSeat(item_)"
                                        color="error"
                                    >
                                        <v-icon>mdi-minus</v-icon>
                                    </v-btn>
                                </template>
                            </v-list-item>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="8">
                    <v-card>
                        <v-toolbar title="Datos personales" density="compact" />

                        <v-container>
                            <v-form ref="formPersonal">
                                <v-row>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.customer.name"
                                            label="Nombre"
                                            :rules="rulesName"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.customer.last_name"
                                            label="Apellidos"
                                            :rules="rulesName"
                                        />
                                    </v-col>
                                    <v-col cols="4">
                                        <v-select
                                            v-model="
                                                form.customer.document_type
                                            "
                                            :items="type_documets"
                                            label="Tipo de documento"
                                            :clearable="false"
                                            :rules="ruleRequired"
                                        />
                                    </v-col>
                                    <v-col cols="8">
                                        <v-text-field
                                            v-model="
                                                form.customer.document_number
                                            "
                                            label="Numero de documento"
                                            :rules="rulesDocumentNumber"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.customer.email"
                                            label="Correo"
                                            :rules="ruleEmail"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
                                        <v-text-field
                                            v-model="form.customer.phone"
                                            label="Telefono"
                                            :rules="rulePhone"
                                        />
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-container>

                        <v-toolbar
                            title="Datos del pago"
                            density="compact"
                            extension
                        >
                            <template #extension>
                                <v-tabs
                                    v-model="tabPayment"
                                    background-color="primary"
                                    dark
                                >
                                    <v-tab value="card"> Transferencia</v-tab>
                                    <!-- <v-tab value="yape"> Yape</v-tab> -->
                                </v-tabs>
                            </template>
                        </v-toolbar>
                        <v-container>
                            <v-window v-model="tabPayment">
                                <v-window-item value="yape">
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Número de celular"
                                            />
                                        </v-col>
                                        <v-col cols="12">
                                            <v-btn block variant="tonal"
                                                >Buscar Pago</v-btn
                                            >
                                        </v-col>
                                    </v-row>
                                </v-window-item>
                                <v-window-item value="card">
                                    <v-form ref="formPaymentTransaction">
                                        <v-row class="mt-3">
                                            <v-col cols="12" md="6">
                                                <v-select
                                                    v-model="form.payment_bank"
                                                    :items="bankItems"
                                                    label="Banco"
                                                    :rules="ruleRequired"
                                                ></v-select>
                                            </v-col>

                                            <v-col cols="12" md="6">
                                                <v-text-field
                                                    v-model="
                                                        form.payment_transaction_id
                                                    "
                                                    label="Serie / Número de transaccion"
                                                    :rules="rulesTransactionId"
                                                />
                                            </v-col>
                                            <v-col cols="12" md="4">
                                                <v-text-field
                                                    v-model="
                                                        form.payment_amount
                                                    "
                                                    label="Monto"
                                                    :rules="rulesAmount"
                                                    disabled
                                                />
                                            </v-col>
                                            <v-col cols="12" md="8">
                                                <v-text-field
                                                    v-model="form.payment_date"
                                                    label="Feha de pago"
                                                    type="date"
                                                    :rules="ruleRequired"
                                                />
                                            </v-col>

                                            <v-col cols="12" md="12">
                                                <v-card variant="tonal">
                                                    <CropCompressImage
                                                        :aspect-ratio="1 / 1"
                                                        @onCropper="
                                                            (previewImg =
                                                                $event.blob),
                                                                (form.payment_image =
                                                                    $event.file)
                                                        "
                                                    />

                                                    <v-img
                                                        v-if="previewImg"
                                                        class="mx-auto"
                                                        :width="300"
                                                        aspect-ratio="16/9"
                                                        cover
                                                        :src="previewImg"
                                                    ></v-img>

                                                    <v-img
                                                        v-if="
                                                            form.payment_image &&
                                                            !previewImg
                                                        "
                                                        class="mx-auto"
                                                        :width="300"
                                                        aspect-ratio="16/9"
                                                        cover
                                                        :src="form.image_url"
                                                    ></v-img>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-form>
                                </v-window-item>
                            </v-window>
                        </v-container>

                        <v-card-text>
                            <v-list-item
                                class="text-end"
                                :title="`Total: S/. ${seatsSelected.reduce(
                                    (acc, ee) =>
                                        acc + parseFloat(ee.seat.price),
                                    0
                                )}`"
                            >
                                <template #prepend>
                                    <v-avatar color="primary">
                                        <v-icon>mdi-cash</v-icon>
                                    </v-avatar>
                                </template>
                            </v-list-item>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn
                                color="primary"
                                block
                                @click="registerSale()"
                            >
                                realizar compra
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog v-model="saleConfirmModal" max-width="600" persistent>
            <v-card :loading="loadingSubmit">
                <v-card-title class="headline"> Confirmar compra</v-card-title>
                <v-card-text>
                    <v-alert variant="tonal" type="info" class="mb-2">
                        Al realizar la compra no podra deshacer la accion, no se
                        podra devolver el dinero.
                    </v-alert>
                </v-card-text>
                <v-list-item
                    v-for="item_ in seatsSelected"
                    :key="item_.id"
                    :title="`S/. ${item_.seat.price}`"
                    :subtitle="item_.grandstand.name"
                >
                    <template #prepend>
                        <v-avatar
                            :color="rowsColor[`${item_.seat.row}`] + '-1'"
                        >
                            {{ item_.seat.name }}
                        </v-avatar>
                    </template>
                </v-list-item>
                <v-list-item
                    class="bg-grey-lighten-2 text-end"
                    :title="`Total: S/. ${seatsSelected.reduce(
                        (acc, ee) => acc + parseFloat(ee.seat.price),
                        0
                    )}`"
                >
                </v-list-item>

                <v-card-actions class="">
                    <v-btn
                        color="primary"
                        block
                        @click="submit"
                        :loading="loadingSubmit"
                    >
                        GUARDAR
                    </v-btn>
                </v-card-actions>

                <v-card-actions class="pt-0">
                    <v-btn
                        color="error"
                        block
                        variant="tonal"
                        :disabled="loadingSubmit"
                        @click="saleConfirmModal = false"
                    >
                        CANCELAR
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="finishBuy" max-width="400">
            <v-card>
                <v-alert type="info">
                    ¡Gracias por tu compra! la estamos procesando, en breve le
                    lleagará un correo con los detalles de su compra, no olvides
                    revisar tu bandeja de spam.
                </v-alert>
            </v-card>
        </v-dialog>
    </CustomerLayout>
</template>
<script setup>
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import CustomerLayout from "@/layouts/CustomerLayout.vue";
import CropCompressImage from "@/components/CropCompressImage.vue";
import Header from "@/Pages/customers/components/Header.vue";
const props = defineProps({
    item: Object,
    grandstandId: [String, Number],
});

const finishBuy = ref(false);

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

//payment_amount =  al total

const rulesName = [
    (v) => !!v || "El campo es requerido",
    (v) => (v && v.length <= 100) || "Maximo 100 caracteres",
];
const rulesDocumentNumber = [
    (v) => !!v || "El campo es requerido",
    (v) => (v && v.length <= 15) || "Maximo 15 caracteres",
    (v) => (v && v.length >= 8) || "Minimo 8 caracteres",
    (v) => /^[0-9]+$/.test(v) || "Solo numeros",
];
const ruleEmail = [
    (v) => !!v || "El campo es requerido",
    (v) => /.+@.+\..+/.test(v) || "Correo invalido",
];

const rulePhone = [
    (v) => !!v || "El campo es requerido",
    (v) => (v && v.length <= 15) || "Maximo 15 caracteres",
    (v) => (v && v.length >= 9) || "Minimo 9 caracteres",
    (v) => /^[0-9]+$/.test(v) || "Solo numeros",
];

const rulesTransactionId = [
    (v) => !!v || "El campo es requerido",
    (v) => (v && v.length <= 15) || "Maximo 20 caracteres",
    (v) => (v && v.length >= 4) || "Minimo 4 caracteres",
    (v) => /^[0-9]+$/.test(v) || "Solo numeros",
];

const rulesAmount = [
    (v) => !!v || "El campo es requerido",
    (v) => /^[0-9]+(\.[0-9]{1,2})?$/.test(v) || "Solo numeros",
];

const ruleRequired = [(v) => !!v || "El campo es requerido"];

const tempRows = ref([]);

const statusSeat = {
    selected: { title: "Seleccionado", color: "primary", variant: "flat" },
    available: { title: "Disponible", color: "black", variant: "outlined" },
    reserved: { title: "Reservado", color: "warning", variant: "flat" },
    sold: { title: "Vendido", color: "error", variant: "flat" },
    displacement: { title: "Desplazamiento", color: "grey", variant: "flat" },
};

const saleConfirmModal = ref(false);

const rows = ref([]);
const tab = ref(0);
const tabPayment = ref("card");
const bankItems = ["Interbank", "BBVA", "BCP", "YAPE"];

const type_documets = [
    { title: "DNI", value: "DNI" },
    { title: "RUC", value: "RUC" },
    { title: "CARNET DE EXTRANJERIA", value: "CE" },
    { title: "PASAPORTE", value: "PASSPORT" },
];

const previewImg = ref(null);

const formPersonal = ref(null);
const formPaymentTransaction = ref(null);
const form = ref({
    payment_type: "online",
    payment_method: null,
    payment_date: "",
    payment_transaction_id: "",
    payment_amount: 0,
    payment_image: "",
    payment_bank: "",

    customer: {
        name: null,
        last_name: null,
        document_type: "DNI",
        document_number: null,
        email: null,
        phone: null,
    },
    event: props.item.id,
    seats: [],
});

const limit = ref(100);

const seatsSelected = ref([]);

const rowsColor = {
    A: "blue-lighten",
    B: "blue-lighten",
    C: "green-lighten",
    D: "green-lighten",
    E: "yellow-lighten",
    F: "green-lighten",
    G: "lime-lighten",
    H: "grey-lighten",
};

const registerSale = async () => {
    //validar si hay asientos seleccionados
    if (!seatsSelected.value.length) {
        alert("Debe seleccionar al menos un asiento");
        return;
    }

    const { valid } = await formPersonal.value.validate();
    if (!valid) return;

    const { valid: validPayment } =
        await formPaymentTransaction.value.validate();
    if (!validPayment) return;

    if (!form.value.payment_image) {
        alert("Debe subir una imagen de la transaccion");
        return;
    }

    form.value.seats = seatsSelected.value.map((s) => s.id);
    form.value.payment_method = tabPayment.value;
    form.value.total = seatsSelected.value.reduce(
        (acc, ee) => acc + parseFloat(ee.seat.price),
        0
    );
    saleConfirmModal.value = true;
};

const removeSeat = (seat) => {
    seatsSelected.value = seatsSelected.value.filter((s) => s.id !== seat.id);
    form.value.payment_amount = seatsSelected.value.reduce(
        (acc, ee) => acc + parseFloat(ee.seat.price),
        0
    );

    seat.status = "available";
};

const onSeatSelected = (grandstand, seat) => {
    const seatSelected = seatsSelected.value.find((s) => s.id === seat.id);

    if (seatSelected) {
        seatsSelected.value = seatsSelected.value.filter(
            (s) => s.id !== seat.id
        );

        form.value.payment_amount = seatsSelected.value.reduce(
            (acc, ee) => acc + parseFloat(ee.seat.price),
            0
        );

        seat.status = "available";
    } else {
        if (seatsSelected.value.length === limit.value) {
            alert("Limite de asientos seleccionados");
            return;
        }

        seatsSelected.value.push({
            id: seat.id,
            grandstand: {
                id: grandstand.id,
                name: grandstand.name,
                // status: grandstand.status,
            },
            seat: seat,
        });

        //sumar a form.payment_amount
        form.value.payment_amount = seatsSelected.value.reduce(
            (acc, ee) => acc + parseFloat(ee.seat.price),
            0
        );

        seat.status = "selected";
    }
};

const loadingSubmit = ref(false);

const submit = async () => {
    router.post("/sales", form.value, {
        onStart: () => {
            loadingSubmit.value = true;
        },
        onSuccess: () => {
            saleConfirmModal.value = false;
            form.value = {
                customer: {
                    name: null,
                    last_name: null,
                    document_type: "DNI",
                    document_number: null,
                    email: null,
                    phone: null,
                },
                event: props.item.id,
                seats: [],
            };

            previewImg.value = null;

            mapRows(tab.value);
            seatsSelected.value = [];
            finishBuy.value = true;
        },
        onError: (error) => {
            alert("Ocurrio un error al realizar la compra");
        },
        onFinish: () => {
            loadingSubmit.value = false;
        },
        preserveScroll: false,
    });
};

const mapRows = (tab_) => {
    rows.value = [];

    rows.value = props.item.grandstands[tab_].seats.reduce((acc, seat) => {
        const row = acc.find((r) => r.name === seat.row);
        if (row) {
            row.seats.push(seat);
        } else {
            acc.push({
                id: props.item.grandstands[tab_].id,
                name: seat.row,

                seats: [seat],
            });
        }
        return acc;
    }, []);

    tempRows.value = [...rows.value];

    rows.value.reverse();
};

const init = () => {
    mapRows(tab.value);
};

init();
</script>

<style lang="scss">
.calle {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;

    color: yellow;

    background-image: url("https://static.vecteezy.com/system/resources/thumbnails/018/878/331/small/empty-straight-road-with-marking-horizontal-highway-overhead-view-seamless-roadway-template-carriageway-element-of-city-map-vector.jpg");
    background-repeat: repeat-x;
    background-position: center center;
    padding: 1rem;
    span {
        background-color: rgba(0, 0, 0, 0.5);
        padding: 0.5rem;
        border-radius: 0.5rem;
    }
}
</style>
