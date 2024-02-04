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

        <v-window v-model="tab" direction="vertical">
            <v-window-item
                v-for="(tab_, w) in item?.grandstands"
                :key="w"
                :value="w"
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
                                        'bg-' + rowsColor[indexRow].color + '-4'
                                    "
                                >
                                    <td class="pa-1">
                                        <v-avatar
                                            :color="
                                                rowsColor[indexRow].color + '-1'
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
                                                seatsSelected.find(
                                                    (s) => s.id == seat.id
                                                ) || seat.status == 'sold'
                                                    ? 'flat'
                                                    : 'outlined'
                                            "
                                            :color="
                                                seat.status === 'sold'
                                                    ? 'error'
                                                    : seatsSelected.find(
                                                          (s) => s.id == seat.id
                                                      )
                                                    ? 'primary'
                                                    : 'black'
                                            "
                                            :disabled="seat.status === 'sold'"
                                            @click="onSeatSelected(tab_, seat)"
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

        <v-container fluid>
            <v-row>
                <v-col cols="12" md="8">
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
                                            rowsColor.find(
                                                (r) => r.row === item_.seat.row
                                            ).color + '-1'
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
                <v-col cols="12" md="4">
                    <v-card variant="tonal">
                        <v-toolbar
                            title="Detalles de la venta"
                            density="compact"
                        />

                        <v-container>
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.customer.name"
                                        label="Nombre"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.customer.last_name"
                                        label="Apellidos"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-select
                                        v-model="form.customer.document_type"
                                        :items="type_documets"
                                        label="Tipo de documento"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.customer.document_number"
                                        label="Numero de documento"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.customer.email"
                                        label="Correo"
                                        outlined
                                    />
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="form.customer.phone"
                                        label="Telefono"
                                        outlined
                                    />
                                </v-col>
                            </v-row>
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
                            :color="
                                rowsColor.find((r) => r.row === item_.seat.row)
                                    .color + '-1'
                            "
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

const form = ref({
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

const seatsSelected = ref([]);

const rowsColor = [
    {
        row: "A",
        color: "red-lighten",
    },
    {
        row: "B",
        color: "indigo-lighten",
    },
    {
        row: "C",
        color: "blue-lighten",
    },
    {
        row: "D",
        color: "cyan-lighten",
    },
    {
        row: "E",
        color: "teal-lighten",
    },
    {
        row: "F",
        color: "green-lighten",
    },
    {
        row: "G",
        color: "lime-lighten",
    },
    {
        row: "H",
        color: "grey-lighten",
    },
    {
        row: "I",
        color: "bg-blue-grey-lighten-5",
    },
    {
        row: "J",
        color: "bg-grey-lighten-4",
    },
    {
        row: "K",
        color: "bg-blue-grey-lighten-5",
    },
    {
        row: "L",
        color: "bg-grey-lighten-4",
    },
    {
        row: "M",
        color: "bg-blue-grey-lighten-5",
    },
    {
        row: "N",
        color: "bg-grey-lighten-4",
    },
    {
        row: "O",
        color: "bg-blue-grey-lighten-5",
    },
    {
        row: "P",
        color: "bg-grey-lighten-4",
    },
    {
        row: "Q",
        color: "bg-blue-grey-lighten-5",
    },
    {
        row: "R",
        color: "bg-grey-lighten-4",
    },
    {
        row: "S",
        color: "bg-blue-grey-lighten-5",
    },
    {
        row: "T",
        color: "bg-grey-lighten-4",
    },
    {
        row: "U",
        color: "bg-blue-grey-lighten-5",
    },
    {
        row: "V",
        color: "bg-grey-lighten-4",
    },
    {
        row: "W",
        color: "bg-blue-grey-lighten-5",
    },
];

const registerSale = () => {
    form.value.seats = seatsSelected.value.map((s) => s.id);
    saleConfirmModal.value = true;
};

const removeSeat = (seat) => {
    seatsSelected.value = seatsSelected.value.filter((s) => s.id !== seat.id);
};

const onSeatSelected = (grandstand, seat) => {
    const seatSelected = seatsSelected.value.find((s) => s.id === seat.id);

    if (seatSelected) {
        seatsSelected.value = seatsSelected.value.filter(
            (s) => s.id !== seat.id
        );
    } else {
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

const submit = () => {
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
};

const init = () => {
    mapRows(tab.value);
};

onMounted(() => {
    init();
});
</script>
