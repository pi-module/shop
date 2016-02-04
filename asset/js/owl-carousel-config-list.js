$(document).ready(function($) {
    $('.owl-carousel-list').owlCarousel({
        loop:false,
        rtl:true,
        margin:3,
        nav:true,
        autoplay:true,
        dots:false,
        autoplayTimeout:6000,
        autoplayHoverPause:true,
        navText: ['<i class="owl-prev fa fa-angle-left"></i>', '<i class="owl-next fa fa-angle-right"></i>'],
        responsive:{
            0:{items:2},
            600:{items:3},
            1000:{items:4}
        }
    })
});