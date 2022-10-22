$(".category-slider").slick ({
    autoplay: true,
    autoplaySpeed: 4000,
    speed: 500,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
        {
        breakpoint: 950,
        settings: {
            arrows: true,
            slidesToShow: 3
        }
        },
        {
        breakpoint: 700,
        settings: {
            arrows: true,
            slidesToShow: 2
        }
        }
    ]
}) 