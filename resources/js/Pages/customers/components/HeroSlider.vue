<template>
    <div>
        <swiper
            :modules="modules"
            :slides-per-view="1"
            :space-between="0"
            :loop="true"
            :autoplay="true"
            @swiper="onSwiper"
            @slideChange="onSlideChange"
        >
            <swiper-slide
                v-for="(image, index) in images"
                :key="index"
                class="swiper-slide"
            >
                <img :src="image" width="100%" />
            </swiper-slide>
        </swiper>
    </div>
</template>
<script setup>
// import Swiper core and required modules
import {

    Autoplay,
} from "swiper/modules";

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// Import Swiper styles
import "swiper/css";


// Import Swiper styles
const modules = [ Autoplay];

const onSwiper = (swiper) => {
    console.log(swiper);
};
const onSlideChange = () => {
    console.log("slide change");
};

const images = [
    "/images/candelaria/Prog_general_2024_001.png",
    "/images/candelaria/Prog_general_2024_002.png",
    "/images/candelaria/Prog_general_2024_003.png",
    "/images/candelaria/Prog_general_2024_004.png",
    "/images/candelaria/Prog_general_2024_005.png",
    "/images/candelaria/Prog_general_2024_006.png",
    "/images/candelaria/Prog_general_2024_007.png",
    "/images/candelaria/Prog_general_2024_008.png",
    "/images/candelaria/Prog_general_2024_009.png",
    "/images/candelaria/Prog_general_2024_010.png",
    "/images/candelaria/Prog_general_2024_011.png",
];
</script>
