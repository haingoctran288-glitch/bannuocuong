let cart = [];
let shipping = 10000;

// Hàm render giỏ hàng
function renderCart() {
    const cartContainer = document.querySelector('.cart-items');
    cartContainer.innerHTML = '<h2>GIỎ HÀNG CỦA TÔI</h2>';
    let subtotal = 0;

    cart.forEach(item => {
        subtotal += item.price * item.quantity;

        cartContainer.innerHTML += `
            <div class="cart-item" data-id="${item.id}">
                <img src="${item.image}" alt="${item.name}">
                <div class="item-details">
                    <p class="item-name">${item.name}</p>
                    <p class="item-quantity">${item.quantity} × ${item.name}</p>
                    <button class="remove-btn">Xóa</button>
                </div>
                <div class="item-controls">
                    <button class="decrease-btn">-</button>
                    <span class="item-amount">${item.quantity}</span>
                    <button class="increase-btn">+</button>
                </div>
                <p class="item-price">${(item.price * item.quantity).toLocaleString()}đ</p>
            </div>
        `;
    });

    updateSummary(subtotal);
}

// Hàm cập nhật bảng tổng tiền
function updateSummary(subtotal) {
    const total = subtotal + shipping;
    document.getElementById('total-items').textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('subtotal').textContent = `${subtotal.toLocaleString()}đ`;
    document.getElementById('total').textContent = `${total.toLocaleString()}đ`;
    document.getElementById('final-total').textContent = `${total.toLocaleString()}đ`;
}

// Xử lý sự kiện thêm combo
document.querySelector('.combo-items').addEventListener('click', e => {
    const comboItem = e.target.closest('.combo-item');
    if (comboItem) {
        const newItem = {
            id: Date.now(),
            name: comboItem.dataset.name,
            price: parseInt(comboItem.dataset.price),
            quantity: 1,
            image: comboItem.querySelector('img').src
        };
        cart.push(newItem);
        renderCart();
    }
});

// Xử lý sự kiện giỏ hàng
document.querySelector('.cart-items').addEventListener('click', e => {
    const cartItem = e.target.closest('.cart-item');
    if (!cartItem) return;

    const itemId = parseInt(cartItem.dataset.id);
    const item = cart.find(i => i.id === itemId);

    if (e.target.classList.contains('decrease-btn')) {
        item.quantity = Math.max(1, item.quantity - 1);
    } else if (e.target.classList.contains('increase-btn')) {
        item.quantity++;
    } else if (e.target.classList.contains('remove-btn')) {
        cart = cart.filter(i => i.id !== itemId);
    }
    renderCart();
});

// Hiển thị giỏ hàng lần đầu
renderCart();
