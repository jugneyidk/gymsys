let slideIndex = 0;
const slides = document.querySelectorAll('.carousel-slide img');
const totalSlides = slides.length;

document.addEventListener('DOMContentLoaded', function () {
    showSlides(slideIndex);
    setInterval(function () { moveSlide(1) }, 5000); // Cambia cada 5 segundos
});

function showSlides(n) {
    slides.forEach(slide => {
        slide.style.display = "none"; 
        slide.classList.remove('active');
    });

    slides[n].style.display = "block"; 
    slides[n].classList.add('active');
}

function moveSlide(n) {
    slideIndex += n;
    if (slideIndex >= totalSlides) { slideIndex = 0; }
    if (slideIndex < 0) { slideIndex = totalSlides - 1; }
    showSlides(slideIndex);
}
