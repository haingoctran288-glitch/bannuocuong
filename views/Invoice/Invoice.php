<?php
require_once('../../model/config.php');
session_start(); // Đảm bảo gọi session_start() ở đầu tệp

$user_id = $_SESSION['user_id']; // Giả sử user_id đã được lưu trong session

// Truy vấn dữ liệu giỏ hàng của người dùng
$sql = "SELECT 
            c.cart_id,
            p.product_id,
            p.name_product,
            p.price,
            p.address,
            ci.quantity
        FROM 
            Cart c
        JOIN 
            Cart_Item ci ON c.cart_id = ci.cart_id
        JOIN 
            Product p ON ci.product_id = p.product_id
        WHERE 
            c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Lấy dữ liệu và lưu vào session hoặc xử lý theo nhu cầu
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

// Lưu dữ liệu vào session để hiển thị trên trang thanh toán
$_SESSION['cart'] = $cart_items;

$stmt->close();
$total_amount = $_SESSION['total'];

?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thanh toán</title>
        <style>
            /* CSS cơ bản để làm đẹp giao diện */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            .container {
                width: 80%;
                margin: 0 auto;
            }

            .form-group {
                margin-bottom: 1em;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5em;
            }

            .form-group input, .form-group textarea {
                width: 100%;
                padding: 0.5em;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .order-summary {
                border: 1px solid #ccc;
                padding: 1em;
                margin-top: 1em;
            }

            .order-summary table {
                width: 100%;
                border-collapse: collapse;
            }

            .order-summary th, .order-summary td {
                border: 1px solid #ccc;
                padding: 0.5em;
                text-align: left;
            }

            .btn {
                background-color: #28a745;
                color: white;
                padding: 0.5em 1em;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .btn:hover {
                background-color: #218838;
            }
            
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Thông tin đặt hàng</h2>
        <form action="../../control/index.php?chucnang=process_payment" method="post">
                <div class="form-group">
                    <label for="name">Tên người nhận</label>
                    <input type="text" id="name" name="recipient_name" required>
                </div>
                <div class="form-group">
                    <label for="billing_address">Địa chỉ</label>
                    <input type="text" id="billing_address" name="billing_address" required>
                </div>
                <div class="form-group">
                    <label for="phone">Số điện thoại</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="notes">Ghi chú</label>
                    <textarea id="notes" name="notes"></textarea>
                </div>

            <h3>Đơn hàng của bạn</h3>
            <div class="order-summary">
                <table>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
       
                            <td style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="flex: 1;"><?php echo $item['name_product']; ?></span>
                            <img src="../../control/<?php echo $item['address']; ?>" 
                                alt="Image of <?php echo $item['name_product']; ?>" 
                                style="width: 100px; height: auto; border-radius: 8px; border: 1px solid #ccc;">
                            </td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $item['price'],"đ"; ?></td>
                            <td><?php echo number_format($item['quantity'] * $item['price'], 3, '.', '.') . "đ"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <p><strong>Tổng cộng:</strong> <?php echo number_format($total_amount, 3, '.', '.') . "đ"; ?></p>
            </div>
            <br>
            <button type="submit" class="btn">Hoàn tất đặt hàng</button>
        </form>
    </div>
    </body>
    </html>
