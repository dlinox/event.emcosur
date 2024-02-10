
<template>
    <footer class="footer">
        <v-Container >
            <v-row>
                <v-col cols="12" md="3"> </v-col>
                <v-col cols="6" md="6">
                    <div class="cc_">
                        <div class="_logo">
                            <img
                                class="logo"
                                src="/logo.png"
                                alt="logo emcosur"
                                width="120"
                            />
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
                </v-col>
                <v-col cols="6" md="3">
                    <div class="">

                      <h5 class="">CON EL RESPALDO DE :</h5>
                        <img
                            class="logo_layher"
                            src="/logo_layher.png"
                            alt="logo emcosur"
                            width="140"
                        />
                    </div>
                </v-col>
            </v-row>
        </v-Container>
        <div class="copy">
            <span>© 2024 EMCOSUR K&F. Todos los derechos reservados.</span>
        </div>
    </footer>
</template>
<style lang="scss">
$header__top__height: 2.5rem;
$primary: #114d76;
$secondary: #00afef;
$third: #faab33;
.footer {
    background-color: $secondary;
    width: 100%;
    .cc_ {
        padding: 3rem 0;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        gap: 2rem;
        ._logo {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            p {
                font-size: 0.8rem;
                color: white;
                margin: 10px 0;
            }
            img.logo {
                width: 150px;
            }
            img.logo_layher {
                width: 180px;
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

    .logo_layher{
      margin: 10px 0;
      width: 250px;
    }
}
</style>
