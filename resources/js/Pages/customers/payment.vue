<template>
  <v-app id="inspire">
    <v-app-bar flat>
      <v-container class="mx-auto d-flex align-center justify-center">
        <v-avatar class="me-4" color="grey-darken-1" size="32"></v-avatar>

        <v-spacer></v-spacer>
      </v-container>
    </v-app-bar>

    <v-main class="bg-grey-lighten-3">
      <v-img src="/images/banner_sales.png"></v-img>

      <v-toolbar color="grey">
        <v-toolbar-title>Medios de pago</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon>
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>

        <template v-slot:extension>
          <v-tabs v-model="tab" bg-color="grey">
            <v-tab v-for="item in paymentItems" :value="item">{{ item }}</v-tab>
          </v-tabs>
        </template>
      </v-toolbar>
      <v-window v-model="tab">
        <v-window-item v-for="item in paymentItems" :value="item">
          <v-container>
            <v-card v-if="tab === 'Yape'">
              <v-toolbar density="compact" title="Ingresar datos personales">
              </v-toolbar>

              <v-card-text>
                <v-row>
                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Nombres"
                    />
                  </v-col>

                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Apellidos"
                    />
                  </v-col>

                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="DNI"
                    />
                  </v-col>
                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Correo"
                    />
                  </v-col>

                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Celular"
                    />
                  </v-col>
                </v-row>
              </v-card-text>
              <v-toolbar
                density="compact"
                title="Validar Pago realizado por Yape"
              >
              </v-toolbar>
              <v-card-text>
                <v-alert type="info" variant="tonal">
                  Realice el pago de yape a numero 950 000 000, y posteriormente
                  increse el numero del cual realizo la operacion y el monto
                </v-alert>

                <v-row class="mt-3">
                  <v-col md="4">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Número"
                    />
                  </v-col>
                  <v-col md="4">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Monto"
                    />
                  </v-col>
                  <v-col md="4">
                    <v-btn block variant="flat" color="primary">
                      Validar
                    </v-btn>
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-btn link block variant="flat" color="primary" to="/payment">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>

            <v-card v-else>
              <v-toolbar density="compact" title="Ingresar datos personales">
              </v-toolbar>

              <v-card-text>
                <v-row>
                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Nombres"
                    />
                  </v-col>

                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Apellidos"
                    />
                  </v-col>

                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="DNI"
                    />
                  </v-col>
                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Correo"
                    />
                  </v-col>

                  <v-col md="6">
                    <v-text-field
                      variant="outlined"
                      density="compact"
                      label="Celular"
                    />
                  </v-col>
                </v-row>
              </v-card-text>
              <v-toolbar density="compact" title="Ingresar datos del pago">
              </v-toolbar>
              <v-card-text>
                <v-alert type="info" variant="tonal">
                  El pago sera validado en un plazo de 24 horas, una vez
                  validado se le enviara un correo con la confirmacion de su
                  compra y el codigo de su entrada virtual.
                </v-alert>

                <v-row class="mt-3">
                  <v-col md="6">
                    <v-row>
                      <v-col md="12">
                        <v-select
                          v-model="bank"
                          :items="bankItems"
                          label="Banco"
                          variant="outlined"
                          density="compact"
                        ></v-select>
                      </v-col>

                      <v-col md="12">
                        <v-text-field
                          variant="outlined"
                          density="compact"
                          label="Serie / Número de transaccion"
                        />
                      </v-col>
                      <v-col md="12">
                        <v-text-field
                          variant="outlined"
                          density="compact"
                          label="Monto"
                        />
                      </v-col>
                      <v-col md="12">
                        <v-text-field
                          variant="outlined"
                          density="compact"
                          label="Feha de pago"
                          type="date"
                        />
                      </v-col>
                    </v-row>
                  </v-col>
                  <v-col md="6">
                    <v-btn
                      varinat="flat"
                      color="primary"
                      rounded="0"
                      block
                      elevation="0"
                    >
                      Imagen
                    </v-btn>
                  </v-col>
                </v-row>
              </v-card-text>

              <v-card-actions>
                <v-btn link block variant="flat" color="primary" to="/payment">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-container>
        </v-window-item>
      </v-window>

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
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from "vue";

const tab = ref("Yape");
const paymentItems = ref(["Yape", "Tranferencia / Deposito"]);

const bankItems = ["Interbank", "Scotiabank", "BBVA", "BCP", "OTRO"];
const bank = ref(null);
</script>
