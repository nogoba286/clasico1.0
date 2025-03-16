function showToast(message) {
    let toastElement = document.getElementById('errorToast');
    let toastBody = toastElement.querySelector('.toast-body');
    toastBody.innerText = message; // Update the toast message

    let toast = new bootstrap.Toast(toastElement);
    toast.show();
}

$("#btn_top_league").on('click', function(event){    
    $("#btn_top_league").parent().find('.custom-dropdown-menu').toggleClass("show");
    // Toggle icon class between down and up arrow
    let icon = $("#btn_top_league").parent().find(".custom-dropdown-icon");
    if ($("#btn_top_league").parent().find(".custom-dropdown-menu").hasClass("show")) {
        icon.removeClass("bi-chevron-down").addClass("bi-chevron-bar-expand");
    } else {
        icon.removeClass("bi-chevron-bar-expand").addClass("bi-chevron-down");
    }
})


const carousel = document.querySelector('.carousel-inner');
let isDragging = false;
let startX, scrollLeft;

carousel.addEventListener('mousedown', (e) => {
    isDragging = true;
    carousel.style.cursor = 'grabbing';
    startX = e.pageX - carousel.offsetLeft;
    scrollLeft = carousel.scrollLeft;
});

carousel.addEventListener('mouseleave', () => {
    isDragging = false;
    carousel.style.cursor = 'grab';
});

carousel.addEventListener('mouseup', () => {
    isDragging = false;
    carousel.style.cursor = 'grab';
});

carousel.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.pageX - carousel.offsetLeft;
    const walk = (x - startX) * 2; // Adjust speed
    carousel.scrollLeft = scrollLeft - walk;
});

document.addEventListener("DOMContentLoaded", function () {
    var sportsSwiper = new Swiper("#sportsCarousel", {
        slidesPerView: 1, // Show 3 items at a time by default
        spaceBetween: 1, // Space between slides
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        loop: true, // Enable looping
        breakpoints: {
            768: {
                slidesPerView: 5, // Show 4 slides on tablets
            },
            1024: {
                slidesPerView: 10, // Show 5 slides on desktops
            },
            1700: {
                slidesPerView: 15, // Show 5 slides on desktops
            },
        },
    });
});


document.addEventListener("DOMContentLoaded", function () {
    var eventSwiper = new Swiper("#eventCarousel", {
        slidesPerView: 1, // Show 3 items at a time
        spaceBetween: 10,
        pagination: {
            el: "#eventCarousel .swiper-pagination",
            clickable: true,
        },
        loop: true,
        breakpoints: {
            768: { slidesPerView: 2 }, // Tablets
            1024: { slidesPerView: 5.5 } // Desktops
        }
    });
});