<template>
    <v-list v-model:opened="menuOpen" nav density="compact" color="primary">
        <v-list-subheader> Menu </v-list-subheader>

        <template v-for="(item, index) in menu" :key="index">
            <template v-if="item.group == null">
                <v-list-item
                    :prepend-icon="item.icon"
                    :title="item.title"
                    :class="
                        router.page.url == item.to
                            ? 'v-list-item--active text-primary rounded-lg'
                            : ''
                    "
                    @click="router.get(`${item.to}`)"
                />
            </template>
            <template v-else>
                <v-list-group :value="item.value">
                    <template v-slot:activator="{ props }">
                        <v-list-item
                            v-bind="props"
                            :prepend-icon="item.icon"
                            :title="item.title"
                            color="black"
                        ></v-list-item>
                    </template>
                    <template v-for="submenu in item.group">
                        <v-list-item
                            :title="submenu.title"
                            :value="submenu.value"
                            :class="
                                router.page.url == submenu.to
                                    ? 'v-list-item--active text-primary rounded-lg'
                                    : ''
                            "
                            @click="
                                menuOpen = submenu.value;
                                router.get(`${submenu.to}`);
                            "
                        >
                            <!-- <template #prepend>
                                    <v-icon class="ms-0 me-2" size="x-large">
                                        mdi-circle-small
                                    </v-icon>
                                </template> -->
                        </v-list-item>
                    </template>
                </v-list-group>
            </template>
        </template>
    </v-list>

</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
const props = defineProps({
    menu: {
        type: Array,
    },
});

const menuOpen = ref([router.page.url.split("/")[2]]);



</script>

<style></style>
