$(document).ready(function ($) {
    $('.owl-carousel').owlCarousel({
        //loop:true,
        //lazyLoad:true,
        margin: 3,
        nav: true,
        rtl: true,
        autoplay: true,
        dots: false,
        autoplayTimeout: 12000,
        autoplayHoverPause: true,
        navText: ['<i class="owl-prev fas fa-angle-left"></i>', '<i class="owl-next fas fa-angle-right"></i>'],
        responsive: {
            0: {items: 2},
            600: {items: 4},
            1000: {items: 4}
        }
    })
});