<template>
    <v-app id="inspire">
        <v-main class="bg-grey-lighten-3">
            <slot />
        </v-main>
        <footer class="footer">
            <div class="container">
                <div class="_logo">
                    <img src="/logo.png" alt="logo" width="100" />
                </div>
                <div class="_enterprise">
                    <h3>EMCOSUR K&F</h3>
                    <ul>
                        <li>Puno -Perú</li>
                        <li>
                            <strong> Teléfonos: </strong>
                            <a href="tel:986868650">986868650</a>
                        </li>
                        <li>
                            <strong> Correo: </strong>
                            <a
                                href="mailto:kf.emcosur@gmail.com"
                                target="_blank"
                            >
                                kf.emcosur@gmail.com</a
                            >
                        </li>
                        <li>
                            <strong> Dirección: </strong>
                            <a
                                href="https://goo.gl/maps/1q2w3e4r5t6y7u8i9o0p"
                                target="_blank"
                            >
                                JR RICARDO PALMA 191
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="copy">
                <span>© 2024 EMCOSUR K&F. Todos los derechos reservados.</span>
            </div>
        </footer>
    </v-app>
</template>

<script setup>
import { router } from "@inertiajs/vue3";

const props = defineProps({
    events: Array,
});
</script>
