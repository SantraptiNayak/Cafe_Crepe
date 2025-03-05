document.addEventListener("DOMContentLoaded", () => {
    let slideIndex = 0;
    const slides = document.querySelectorAll(".slide");

    function showSlides() {
        slides.forEach(slide => slide.style.display = "none");
        slideIndex = (slideIndex + 1) % slides.length;
        slides[slideIndex].style.display = "block";
        setTimeout(showSlides, 3000);
    }

    showSlides();
});
