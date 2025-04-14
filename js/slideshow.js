let slideIndex = 0;
const slides = document.querySelectorAll('.slideshow .slide');
const prevButton = document.getElementById('prevSlideButton');
const nextButton = document.getElementById('nextSlideButton');

function showSlide(index) {
slides.forEach((slide, i) => {
    if (i === index) {
    slide.style.display = 'block';
    slide.style.opacity = 0;
    setTimeout(() => {
        slide.style.transition = 'opacity 0.5s ease-in-out';
        slide.style.opacity = 1;
    }, 10);
    } else {
    slide.style.display = 'none';
    slide.style.opacity = 0;
    }
});
}

function nextSlide() {
slideIndex = (slideIndex + 1) % slides.length;
showSlide(slideIndex);
}

prevButton.addEventListener('click', () => {
slideIndex = (slideIndex - 1 + slides.length) % slides.length;
showSlide(slideIndex);
});

nextButton.addEventListener('click', () => {
nextSlide();
});

// Auto slide show
setInterval(nextSlide, 4000); // Change slide every 5 seconds

showSlide(slideIndex); // Initialize first slide