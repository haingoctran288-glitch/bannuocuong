// Giá gốc của sản phẩm
const basePrice = 55000;

// Khởi tạo giá trị ban đầu
let quantity = 1;
let currentPriceAdjustment = 0; // Giá điều chỉnh dựa trên kích cỡ

// Các phần tử DOM
const quantityValue = document.getElementById('quantity-value');
const decreaseButton = document.getElementById('decrease');
const increaseButton = document.getElementById('increase');
const priceElement = document.getElementById('price');
const addToCartButton = document.getElementById('add-to-cart-button');
const sizeButtons = document.querySelectorAll('.size-option');

// Cập nhật tổng giá hiển thị
function updateTotalPrice() {
    const totalPrice = (basePrice + currentPriceAdjustment) * quantity;
    priceElement.textContent = `${totalPrice.toLocaleString()} đ`;
    addToCartButton.textContent = `🛒 Thêm vào giỏ hàng: ${totalPrice.toLocaleString()} đ`;
}

// Xử lý sự kiện nút giảm số lượng
decreaseButton.addEventListener('click', () => {
    if (quantity > 1) {
        quantity--;
        quantityValue.textContent = quantity;
        updateTotalPrice();
    }
});

// Xử lý sự kiện nút tăng số lượng
increaseButton.addEventListener('click', () => {
    quantity++;
    quantityValue.textContent = quantity;
    updateTotalPrice();
});

// Xử lý chọn kích cỡ
sizeButtons.forEach(button => {
    button.addEventListener('click', () => {
        // Xóa trạng thái "active" khỏi tất cả các nút kích cỡ
        sizeButtons.forEach(btn => btn.classList.remove('active'));
        // Thêm trạng thái "active" cho nút đã chọn
        button.classList.add('active');

        // Lấy giá trị điều chỉnh giá từ thuộc tính "data-price"
        currentPriceAdjustment = parseInt(button.getAttribute('data-price'));
        updateTotalPrice();
    });
});

// Xử lý chọn ngọt, trà, đá
document.querySelectorAll('.option').forEach(option => {
    option.addEventListener('click', () => {
        // Xóa trạng thái "active" khỏi các nút cùng nhóm
        const parent = option.parentElement;
        parent.querySelectorAll('.option').forEach(opt => opt.classList.remove('active'));
        // Thêm trạng thái "active" cho nút đã chọn
        option.classList.add('active');
    });
});

// Khởi tạo giá ban đầu
updateTotalPrice();


function addToCart() {
    alert("Đã Thêm Vào Giỏ Hàng!");
}

