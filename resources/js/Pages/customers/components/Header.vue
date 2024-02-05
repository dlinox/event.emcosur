<template>
    <header>
        <div class="header__top">
            <v-container class="container">
                <div class="_contact">
                    <a class="_item" href="tel:+56912345678">
                        <v-icon class="text-white">mdi-map-marker</v-icon>
                        <span class=""> JR. RICARDO PALMA 191</span>
                    </a>
                </div>
                <div class="_social">
                    <a
                        class="_item"
                        href="https://www.facebook.com/profile.php?id=61555943546151&sfnsn=wa&mibextid=RUbZ1f"
                        target="_blank"
                    >
                        <v-icon class="text-white">mdi-facebook</v-icon>
                    </a>
                </div>
            </v-container>
        </div>

        <div class="header__nav">
            <v-container class="container">
                <div class="_logo">
                    <a href="/">
                        <img src="/logo.png" alt="logo-emcosur-k&f" />
                    </a>
                </div>
                <nav class="_nav">
                    <ul>
                        <li>
                            <a href="/#tickets">Entradas</a>
                        </li>
                    </ul>
                </nav>
            </v-container>
        </div>
    </header>
</template>
<script setup></script>
<style lang="scss">
$header__top__height: 2.5rem;
$primary: #114d76;
$secondary: #00afef;
$third: #faab33;

.header__top {
    height: $header__top__height;
    width: 100%;
    background-color: #f5f5f5;
    .container {
        display: flex;
        justify-content: space-between;
        height: 100%;

        ._social,
        ._contact {
            display: flex;
            align-items: center;
            height: 100%;

            color: white;
            ._item {
                display: flex;
                align-items: center;
                span {
                    font-size: 0.8rem;
                    margin-left: 0.5rem;
                    color: $primary;
                }

                i {
                    height: $header__top__height;
                    width: $header__top__height;
                    background-color: $primary;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.25rem;
                }
            }
        }
    }
}
.header__nav {
    width: 100%;
    background-color: white;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #f5f5f5;
    height: 7rem;
    .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 100%;
        ._logo {
            img {
                height: 5rem;
            }
        }
        ._nav {
            ul {
                display: flex;
                list-style: none;
                li {
                    margin-left: 1rem;
                    a {
                        text-transform: uppercase;
                        font-size: 1rem;
                        text-decoration: none;
                        color: $primary;
                        font-weight: bold;
                        &:hover {
                            color: darken($primary, 10%);
                        }
                    }
                }
            }
        }
    }
}
</style>
