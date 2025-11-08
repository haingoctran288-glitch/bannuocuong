<?php
// ==========================================
// 🔹 Trang hiển thị kết quả thanh toán MoMo
// ==========================================
require_once('../../model/config.php'); // kết nối DB
if (session_status() === PHP_SESSION_NONE) session_start();

// Lấy user_id từ session
$user_id = $_SESSION['user_id'] ?? null;

// ✅ Nếu MoMo báo thành công
if (isset($_GET['resultCode']) && $_GET['resultCode'] == '0' && isset($_GET['orderId']) && $user_id) {
    if (preg_match('/HD(\d+)_/', $_GET['orderId'], $matches)) {
        $invoice_id = intval($matches[1]);

        // 🔹 Cập nhật trạng thái hóa đơn
        $update = $conn->prepare("UPDATE Invoice SET payment_status = 'Đã thanh toán' WHERE invoice_id = ? AND user_id = ?");
        $update->bind_param("ii", $invoice_id, $user_id);
        $update->execute();
        $update->close();

        // 🔹 Xóa giỏ hàng
        $deleteCart = $conn->prepare("DELETE FROM Cart WHERE user_id = ?");
        $deleteCart->bind_param("i", $user_id);
        $deleteCart->execute();
        $deleteCart->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kết quả thanh toán</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #fbe5ef, #f5f5f5);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        padding: 40px 60px;
        text-align: center;
        max-width: 500px;
        width: 90%;
        animation: fadeIn 0.6s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    h2 {
        margin-bottom: 10px;
        font-size: 26px;
    }
    p {
        font-size: 18px;
        color: #333;
        margin: 10px 0;
    }
    .success {
        color: #28a745;
    }
    .fail {
        color: #dc3545;
    }
    .order-id {
        background: #f7f7f7;
        border-radius: 8px;
        padding: 10px;
        display: inline-block;
        margin-top: 10px;
        font-weight: 600;
        color: #555;
    }
    .btn-home {
        display: inline-block;
        margin-top: 25px;
        padding: 12px 25px;
        background-color: #a50064; 
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: 0.3s;
        font-size: 16px;
    }
    .btn-home:hover {
        background-color: #870050;
        transform: scale(1.05);
    }
    footer {
        margin-top: 25px;
        font-size: 14px;
        color: #777;
    }
</style>
</head>
<body>
<div class="container">
    <?php
    // ==============================
    // ✅ Hiển thị kết quả thanh toán
    // ==============================
    if (isset($_GET['resultCode']) && $_GET['resultCode'] == '0') {
        echo "<h2 class='success'>✅ Thanh toán thành công!</h2>";
        echo "<p>Cảm ơn bạn đã thanh toán qua <b>MoMo</b>.</p>";
        if (isset($_GET['orderId'])) {
            echo "<div class='order-id'>Mã đơn hàng: " . htmlspecialchars($_GET['orderId']) . "</div>";
        }
    } else {
        echo "<h2 class='fail'>❌ Thanh toán thất bại hoặc bị hủy.</h2>";
        if (isset($_GET['message']) && $_GET['message'] != '') {
            echo "<p><b>Lý do:</b> " . htmlspecialchars($_GET['message']) . "</p>";
        } else {
            echo "<p>Vui lòng thử lại hoặc chọn phương thức thanh toán khác.</p>";
        }
    }
    ?>
    <a href="/ChuyenDeThucTap/index.php" class="btn-home">🏠 Quay về trang chủ</a>

    <footer>© <?php echo date('Y'); ?> - Hệ thống Thanh toán MoMo</footer>
</div>
</body>
</html>
