document.querySelectorAll('.product-card button').forEach(button => {
    button.addEventListener('click', () => {
        alert('Đã thêm vào giỏ hàng!');
    });
});
//Data
const drinkCards = document.querySelectorAll('.product-card');  
const tooltip = document.getElementById('tooltip');  

