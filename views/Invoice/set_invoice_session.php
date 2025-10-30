<?php
require_once('../../model/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy invoice_id từ POST request
if (isset($_POST['invoice_id'])) {
    $_SESSION['invoice_id'] = intval($_POST['invoice_id']);
    // Chuyển hướng đến trang chi tiết hóa đơn
    header("Location: donhangchitiet.php");
    exit;
} else {
    echo "Mã hóa đơn không hợp lệ.";
    exit;
}
?>
