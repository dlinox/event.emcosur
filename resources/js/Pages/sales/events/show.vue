<template>
    <SalesLayout title="Eventos">
        <v-toolbar title="Vender" density="compact" rounded="0">
            <v-spacer></v-spacer>
        </v-toolbar>

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
        <div>
            <v-chip class="" color="primary" label>
                <v-icon start color="red" icon="mdi-square-rounded"></v-icon>
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
        </div>

        <v-window v-model="tab" direction="vertical">
            <v-window-item
                v-for="(tab_, grandstandIndex) in item?.grandstands"
                :key="grandstandIndex"
                :value="grandstandIndex"
                vertical
            >
                <v-container fluid>
                    <v-card>
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
                                        v-for="(seat, indexCol) in item.seats"
                                    >

                                        <template v-if="!seat.is_active">
                                            <v-btn  density="comfortable" icon disabled color="white">
                                                <small> {{ seat.name }} </small>
                                            </v-btn>
                                        </template>
                                        <v-btn
                                            v-else
                                            icon
                                            density="comfortable"
                                            :variant="statusSeat[seat.status].variant"
                                            :color="statusSeat[seat.status].color
                                            "
                                            @click="
                                                seat.status === 'sold' ||
                                                seat.status === 'reserved' ||
                                                seat.status === 'displacement'
                                                    ? null
                                                    : onSeatSelected(
                                                          tab_,
                                                          seat,
                                                          grandstandIndex,
                                                          indexRow,
                                                          indexCol
                                                      )
                                            "
                                        >
                                            <small> {{ seat.name }} </small>
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
                    <v-card variant="tonal">
                        <v-toolbar
                            title="Asisentos Selecionados"
                            density="compact"
                        />
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
                    <v-card variant="tonal">
                        <v-toolbar
                            title="Detalles de la venta"
                            density="compact"
                        />

                        <v-container>
                            <v-form ref="formRef">
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
                                    <v-col cols="12" md="6">
                                        <v-select
                                            v-model="
                                                form.customer.document_type
                                            "
                                            :items="type_documets"
                                            label="Tipo de documento"
                                            :rules="ruleRequired"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6">
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
                                    <v-col cols="12" >
                                        <v-textarea
                                            v-model="form.observation"
                                            label="Observaciones"
                                            rows="2"
                                        />
                                    </v-col>
                                    <v-col cols="12" md="6"></v-col>
                                    <v-col cols="12" md="6">
                                        <v-select
                                            v-model="form.option"
                                            :items="saleOptions"
                                            label="Tipo de venta"
                                            :rules="ruleRequired"
                                            :clearable="false"
                                        />
                                    </v-col>
                                </v-row>
                            </v-form>
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
                                realizar venta
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-dialog v-model="saleConfirmModal" max-width="400" persistent>
            <v-card>
                <v-card-title class="headline"> Confirmar venta</v-card-title>
                <v-card-text>
                    <v-alert variant="tonal" type="info" class="mb-2">
                        Al realizar la venta no podra deshacer la accion, no se
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
    
    </SalesLayout>
</template>
<script setup>
import SalesLayout from "@/layouts/SalesLayout.vue";
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    item: Object,
});

const saleConfirmModal = ref(false);

const rows = ref([]);
const tab = ref(0);

const type_documets = [
    { title: "DNI", value: "DNI" },
    { title: "RUC", value: "RUC" },
    { title: "CARNET DE EXTRANJERIA", value: "CE" },
    { title: "PASAPORTE", value: "PASSPORT" },
];

const saleOptions = [
    { title: "Vender", value: "sold" },
    { title: "Reservar", value: "reserved" },
];

const form = ref({
    payment_amount: 0,
    observation: null,
    option: "sold",
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

const ruleRequired = [(v) => !!v || "El campo es requerido"];

const seatsSelected = ref([]);



const statusSeat = {
    selected: { title: "Seleccionado", color: "primary", variant: "flat" },
    available: { title: "Disponible", color: "black", variant: "outlined" },
    reserved: { title: "Reservado", color: "warning", variant: "flat" },
    sold: { title: "Vendido", color: "error", variant: "flat" },
    displacement: { title: "Desplazamiento", color: "grey", variant: "flat" },
};


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
    if (seatsSelected.value.length === 0) {
        alert("Debe seleccionar al menos un asiento");
        return;
    }

    const { valid } = await formRef.value.validate();
    if (!valid) return;

    form.value.seats = seatsSelected.value.map((s) => s.id);

    form.value.payment_amount = seatsSelected.value.reduce(
        (acc, ee) => acc + parseFloat(ee.seat.price),
        0
    );

    saleConfirmModal.value = true;
};

const removeSeat = (seat) => {
    seatsSelected.value = seatsSelected.value.filter((s) => s.id !== seat.id);
    let grandstand = props.item.grandstands.find(
        (g) => g.id === seat.grandstand.id
    );
    let indexSeat = grandstand.seats.findIndex((s) => s.id === seat.id);
    grandstand.seats[indexSeat].status = "available";
};

const onSeatSelected = (
    grandstand,
    seat,
    grandstandIndex,
    indexRow,
    indexCol
) => {
    //!Si cambia segun al orientacion de la tabla
    let numCols = grandstand.seats.length / grandstand.rows;
    let indexSeat = (grandstand.rows - 1 - indexRow) * numCols + indexCol;


    let seatSelected = props.item.grandstands[grandstandIndex].seats[indexSeat].status === "selected" ? true : false;

    if (seatSelected) {
        seatsSelected.value = seatsSelected.value.filter(
            (s) => s.id !== seat.id
        );
        props.item.grandstands[grandstandIndex].seats[indexSeat].status = "available";
    } else {

        props.item.grandstands[grandstandIndex].seats[indexSeat].status = "selected";
        seatsSelected.value.push({
            id: seat.id,
            grandstand: {
                id: grandstand.id,
                name: grandstand.name,
            },
            seat: seat,
        });
    }
};

const formRef = ref(null);

const submit = async () => {
    router.post("/sa/sales", form.value, {
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

    rows.value.reverse();
};

const init = () => {
    mapRows(tab.value);
};

onMounted(() => {
    init();
});
</script>
