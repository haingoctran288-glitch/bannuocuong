<?php
require_once('../../model/config.php');
session_start();

$user_id = $_SESSION['user_id'] ?? null; // an toàn nếu chưa login

// Lấy giỏ hàng của người dùng
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

$cart_items = [];
$total_amount = 0;

// Chuẩn hóa giá: nếu giá < 1000 -> coi là đơn vị 'nghìn đồng' => nhân 1000
while ($row = $result->fetch_assoc()) {
    // đảm bảo giá là số
    $raw_price = floatval($row['price']);

    if ($raw_price < 1000) {
        // nhiều DB lưu 30 (ý nghĩa 30.000) -> nhân 1000 để có VND
        $price_vnd = $raw_price * 1000;
    } else {
        $price_vnd = $raw_price;
    }

    // gán lại giá đã chuẩn vào mảng (để dùng hiển thị)
    $row['price_vnd'] = $price_vnd; // giá đúng theo VND
    $cart_items[] = $row;

    $total_amount += $price_vnd * $row['quantity'];
}
$stmt->close();

// ================== ÁP DỤNG VOUCHER ==================
$discount_percent = 0;
$discount_messages = [];

// 1️⃣ Tài khoản mới (chưa có đơn hàng)
$check_order = $conn->prepare("SELECT COUNT(*) AS total_orders FROM invoice WHERE user_id = ?");
$check_order->bind_param("i", $user_id);
$check_order->execute();
$res = $check_order->get_result();
$row = $res->fetch_assoc();
$check_order->close();

if ($row['total_orders'] == 0) {
    $discount_percent += 20;
    $discount_messages[] = "🎉 Tài khoản mới - được giảm 20% cho đơn hàng đầu tiên!";
}

// 2️⃣ Đơn hàng ≥ 149.000₫
if ($total_amount >= 149000) {
    $discount_percent += 10;
    $discount_messages[] = "💰 Đơn hàng trên 149.000₫ - giảm thêm 10%!";
}

// 3️⃣ Việt Quất Đá Xay từ 2 ly trở lên
foreach ($cart_items as $item) {
    if (stripos($item['name_product'], 'Việt Quất Đá Xay') !== false && $item['quantity'] >= 2) {
        $discount_percent += 10;
        $discount_messages[] = "🫐 Mua từ 2 ly Việt Quất Đá Xay - giảm thêm 10%!";
        break;
    }
}

// 4️⃣ Trà Ruby Cam Đào từ 2 ly trở lên
foreach ($cart_items as $item) {
    if (stripos($item['name_product'], 'Trà RuBy Cam Đào') !== false && $item['quantity'] >= 2) {
        $discount_percent += 10;
        $discount_messages[] = "🍑 Mua từ 2 ly Trà RuBy Cam Đào - giảm thêm 10%!";
        break;
    }
}

// Tính giảm giá
$discount_amount = $total_amount * ($discount_percent / 100);
$final_total = $total_amount - $discount_amount;






$_SESSION['cart'] = $cart_items;
$_SESSION['total'] = $final_total;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #fafafa; }
        .container { width: 80%; margin: 30px auto; background: #fff; border-radius: 10px; padding: 25px; box-shadow: 0 0 10px rgba(0,0,0,0.08); }
        h2, h3 { color: #333; }
        .form-group { margin-bottom: 1em; }
        .form-group label { display: block; margin-bottom: 0.5em; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.6em; border: 1px solid #ccc; border-radius: 5px; }
        textarea { resize: none; height: 80px; }
        .order-summary { border: 1px solid #eee; padding: 1em; border-radius: 8px; background: #fff; margin-top: 15px; }
        .order-summary table { width: 100%; border-collapse: collapse; }
        .order-summary th, .order-summary td { border: 1px solid #f0f0f0; padding: 0.7em; text-align: left; }
        .order-summary th { background: #fafafa; }
        .btn { background-color: #28a745; color: white; padding: 10px 18px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background-color: #218838; }
        .discount-box { background: #fff3cd; border-left: 5px solid #ffc107; padding: 12px 15px; margin-top: 15px; border-radius: 5px; }
        .action-buttons { display: flex; justify-content: space-between; margin-top: 20px; }
        .btn-back { background-color: #007bff; color: #fff; padding: 10px 18px; border-radius: 5px; text-decoration: none; display: inline-flex; align-items:center; }
        .btn-back:hover { background-color: #0056b3; }
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
            <textarea id="notes" name="notes" placeholder="Nhập ghi chú cho đơn hàng..."></textarea>
        </div>

        <?php if (!empty($discount_messages)): ?>
            <div class="discount-box">
                <strong>🎁 Ưu đãi áp dụng:</strong><br>
                <?php foreach ($discount_messages as $msg): ?>
                    • <?= htmlspecialchars($msg) ?><br>
                <?php endforeach; ?>
                <br><strong>Tổng giảm:</strong> <?= $discount_percent ?>%
            </div>
        <?php else: ?>
            <div class="discount-box">Không có ưu đãi nào được áp dụng.</div>
        <?php endif; ?>

        <h3>Đơn hàng của bạn</h3>
        <div class="order-summary">
            <table>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>

                <?php foreach ($cart_items as $item): 
                    // lấy giá đã chuẩn hóa (price_vnd)
                    $price_vnd = floatval($item['price_vnd']);
                    $line_total = $price_vnd * $item['quantity'];
                ?>
                    <tr>
                        <td style="display:flex; align-items:center; gap:10px;">
                            <img src="../../control/<?php echo htmlspecialchars($item['address']); ?>" alt="<?php echo htmlspecialchars($item['name_product']); ?>" style="width:80px; border-radius:8px;">
                            <?php echo htmlspecialchars($item['name_product']); ?>
                        </td>
                        <td><?php echo intval($item['quantity']); ?></td>
                        <td><?php echo number_format($price_vnd, 0, ',', '.'); ?>đ</td>
                        <td><?php echo number_format($line_total, 0, ',', '.'); ?>đ</td>
                    </tr>
                <?php endforeach; ?>

                <tr>
                    <td colspan="3" style="text-align:right"><b>Giảm giá:</b></td>
                    <td>-<?= number_format($discount_amount, 0, ',', '.') ?>đ</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align:right"><b>Tổng sau giảm:</b></td>
                    <td><b><?= number_format($final_total, 0, ',', '.') ?>đ</b></td>
                </tr>
            </table>
        </div>

        <div class="action-buttons">
            <a href="../../index.php" class="btn-back">← Trở về trang chủ</a>
            <button type="submit" class="btn">Hoàn tất đặt hàng</button>
        </div>
    </form>
</div>
</body>
</html>
