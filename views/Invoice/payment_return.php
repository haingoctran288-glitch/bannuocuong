<?php
if (isset($_GET['resultCode']) && $_GET['resultCode'] == '0') {
    echo "<h2>✅ Thanh toán thành công!</h2>";
    echo "<p>Mã đơn hàng: " . htmlspecialchars($_GET['orderId']) . "</p>";
} else {
    echo "<h2>❌ Thanh toán thất bại hoặc bị hủy.</h2>";
}
?>
