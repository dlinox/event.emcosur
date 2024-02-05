<template>
    <CustomerLayout title="Eventos">
        <Header />
        <v-img
            src="/banner.png"
            gradient="to top right, rgba(0,0,0,.2), rgba(0,0,0,.1)"
        ></v-img>
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
                                    <small>Ocupado</small>
                                </v-chip>

                                <v-chip class="ma-1" color="primary" label>
                                    <v-icon
                                        start
                                        color="warning"
                                        icon="mdi-square-rounded"
                                    ></v-icon>
                                    <small>Reservado</small>
                                </v-chip>

                                <v-chip class="ma-1" color="primary" label>
                                    <v-icon
                                        start
                                        color="primary"
                                        icon="mdi-square-rounded"
                                    ></v-icon>
                                    <small>Seleccionado</small>
                                </v-chip>

                                <v-chip class="ma-1" color="primary" label>
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
                                                seat.status === 'reserved'
                                                    ? null
                                                    : onSeatSelected(tab_, seat)
                                            "
                                        >
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
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.customer.name"
                                        label="Nombre"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.customer.last_name"
                                        label="Apellidos"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="4">
                                    <v-select
                                        v-model="form.customer.document_type"
                                        :items="type_documets"
                                        label="Tipo de documento"
                                        :clearable="false"
                                    />
                                </v-col>
                                <v-col cols="8">
                                    <v-text-field
                                        v-model="form.customer.document_number"
                                        label="Numero de documento"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.customer.email"
                                        label="Correo"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field
                                        v-model="form.customer.phone"
                                        label="Telefono"
                                        outlined
                                    />
                                </v-col>
                            </v-row>
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
                                    <v-tab value="card"> Tranferencia</v-tab>
                                    <v-tab value="yape"> Yape</v-tab>
                                </v-tabs>
                            </template>
                        </v-toolbar>
                        <v-container>
                            <v-window v-model="tabPayment">
                                <v-window-item value="yape">
                                    <v-row>
                                        <v-col cols="12">
                                            <v-text-field
                                                label="Numero de celular"
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
                                    <v-row class="mt-3">
                                        <v-col cols="12" md="6">
                                            <v-select
                                                v-model="form.payment_bank"
                                                :items="bankItems"
                                                label="Banco"
                                                variant="outlined"
                                                density="compact"
                                            ></v-select>
                                        </v-col>

                                        <v-col cols="12" md="6">
                                            <v-text-field
                                                v-model="
                                                    form.payment_transaction_id
                                                "
                                                variant="outlined"
                                                density="compact"
                                                label="Serie / Número de transaccion"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="4">
                                            <v-text-field
                                                v-model="form.payment_amount"
                                                variant="outlined"
                                                density="compact"
                                                label="Monto"
                                            />
                                        </v-col>
                                        <v-col cols="12" md="8">
                                            <v-text-field
                                                v-model="form.payment_date"
                                                variant="outlined"
                                                density="compact"
                                                label="Feha de pago"
                                                type="date"
                                            />
                                        </v-col>

                                        <v-col cols="12" md="12">
                                            <v-card variant="tonal">
                                                <CropCompressImage
                                                    :aspect-ratio="21 / 9"
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
        <v-dialog v-model="saleConfirmModal" max-width="400" persistent>
            <v-card>
                <v-card-title class="headline"> Confirmar compra</v-card-title>
                <v-card-text>
                    <v-alert variant="tonal" type="info" class="mb-2">
                        Al realizar la compra no podra deshacer la accion, no se
                        podra devolver el dinero
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
                    <v-btn color="primary" block @click="submit">
                        GUARDAR
                    </v-btn>
                </v-card-actions>

                <v-card-actions class="pt-0">
                    <v-btn
                        color="error"
                        block
                        variant="tonal"
                        @click="saleConfirmModal = false"
                    >
                        CANCELAR
                    </v-btn>
                </v-card-actions>
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

const tempRows = ref([]);

const statusSeat = {
    selected: { title: "Seleccionado", color: "primary", variant: "flat" },
    available: { title: "Disponible", color: "black", variant: "outlined" },
    reserved: { title: "Reservado", color: "warning", variant: "flat" },
    sold: { title: "Vendido", color: "error", variant: "flat" },
};

const saleConfirmModal = ref(false);

const rows = ref([]);
const tab = ref(0);
const tabPayment = ref("card");
const bankItems = ["Interbank", "BBVA", "BCP"];

const type_documets = [
    { title: "DNI", value: "DNI" },
    { title: "RUC", value: "RUC" },
    { title: "CARNET DE EXTRANJERIA", value: "CE" },
    { title: "PASAPORTE", value: "PASSPORT" },
];

const previewImg = ref(null);
const form = ref({
    payment_type: "online",
    payment_method: null,
    payment_date: "",
    payment_transaction_id: "",
    payment_amount: "",
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

const limit = ref(10);

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
    I: "bg-blue-grey-lighten-5",
    J: "bg-grey-lighten-4",
    K: "bg-blue-grey-lighten-5",
    L: "bg-grey-lighten-4",
    M: "bg-blue-grey-lighten-5",
    N: "bg-grey-lighten-4",
    O: "bg-blue-grey-lighten-5",
    P: "bg-grey-lighten-4",
    Q: "bg-blue-grey-lighten-5",
    R: "bg-grey-lighten-4",
    S: "bg-blue-grey-lighten-5",
    T: "bg-grey-lighten-4",
    U: "bg-blue-grey-lighten-5",
    V: "bg-grey-lighten-4",
    W: "bg-blue-grey-lighten-5",
};

const registerSale = () => {
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
};

const onSeatSelected = (grandstand, seat) => {
    if (seatsSelected.value.length === limit.value) {
        alert("Limite de asientos seleccionados");
        return;
    }

    const seatSelected = seatsSelected.value.find((s) => s.id === seat.id);

    if (seatSelected) {
        seatsSelected.value = seatsSelected.value.filter(
            (s) => s.id !== seat.id
        );

        seat.status = "available";
    } else {
        seatsSelected.value.push({
            id: seat.id,
            grandstand: {
                id: grandstand.id,
                name: grandstand.name,
                // status: grandstand.status,
            },
            seat: seat,
        });

        seat.status = "selected";
    }
};

const submit = () => {
    router.post("/sales", form.value, {
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

            mapRows(tab.value);
            seatsSelected.value = [];
        },
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

    console.log("des", rows.value.reverse());
    console.log("reverse", tempRows.value.reverse());
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
