<template>
    <div>
        <swiper :modules="modules" :slides-per-view="1" :autoplay="true">
            <!-- :direction="'vertical'" -->
            <swiper-slide
                v-for="(image, index) in images"
                :key="index"
                class="swiper-slide"
            >
                <v-img :src="image" />
            </swiper-slide>
        </swiper>
    </div>
</template>
<script setup>
// import Swiper core and required modules
import { Autoplay } from "swiper/modules";

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
// Import Swiper styles
import "swiper/css";

// Import Swiper styles
const modules = [Autoplay];

const images = [
    "/images/flayers/f2.png",
    "/images/flayers/f1.png",
    "/images/flayers/f3.jpeg",
];
</script>
