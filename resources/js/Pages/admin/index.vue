<template>
    <AdminLayout>
        <v-container fluid> </v-container>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/layouts/AdminLayout.vue";

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
</script>
