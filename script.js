document.addEventListener('DOMContentLoaded', function () {
    let slideIndex = 1;
    let previousIndex = 1;
    showSlides(slideIndex);
    autoSlide();

    function plusSlides(n) {
        previousIndex = slideIndex;
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        previousIndex = slideIndex;
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("mySlides");
        let dots = document.getElementsByClassName("dot");

        if (n > slides.length) { slideIndex = 1 }
        if (n < 1) { slideIndex = slides.length }

        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
            slides[i].classList.remove("slide-left", "slide-right");
        }

        if (slideIndex > previousIndex) {
            slides[slideIndex - 1].classList.add("slide-left");
        } else {
            slides[slideIndex - 1].classList.add("slide-right");
        }

        slides[slideIndex - 1].style.display = "block";

        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        dots[slideIndex - 1].className += " active";
    }

    function autoSlide() {
        setInterval(() => {
            plusSlides(1);
        }, 3000);
    }
});



function orderSuccess() {
    // Lấy phần tử toast
    const toast = document.getElementById("toast");
    
    // Thêm lớp "show" để hiển thị toast
    toast.classList.add("show");
    
    // Sau 3 giây sẽ ẩn toast
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
/*
const drinkCards = document.querySelectorAll('.product-card');  
const tooltip = document.getElementById('tooltip');  

drinkCards.forEach(card => {  
    card.addEventListener('mouseover', (event) => {  
        tooltip.textContent = card.getAttribute('data-name');  
        const rect = card.getBoundingClientRect();  
        tooltip.style.top = `${rect.top + window.scrollY - tooltip.offsetHeight}px`;  
        tooltip.style.left = `${rect.left + window.scrollX + (card.offsetWidth / 2) - (tooltip.offsetWidth / 2)}px`;  
        tooltip.style.display = 'block';  
    });  

    card.addEventListener('mouseout', () => {  
        tooltip.style.display = 'none';  
    });  
});
*/
const drinkCards = document.querySelectorAll('.product-card');  
const tooltip = document.getElementById('tooltip');  

drinkCards.forEach(card => {  
    card.addEventListener('mouseover', (event) => {  
        tooltip.textContent = card.getAttribute('data-name');  
        const rect = card.getBoundingClientRect();  
        tooltip.style.top = `${rect.top + window.scrollY - tooltip.offsetHeight}px`;  
        tooltip.style.left = `${rect.left + window.scrollX + (card.offsetWidth / 2) - (tooltip.offsetWidth / 2)}px`;  
        tooltip.style.display = 'block';  
    });  

    card.addEventListener('mouseout', () => {  
        // Ẩn tooltip sau 2 giây (2000 mili giây)  
        setTimeout(() => {  
            tooltip.style.display = 'none';  
        }, 2000);  
    });  
});