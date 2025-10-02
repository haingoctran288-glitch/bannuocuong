<?php
require_once('../../model/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : 0;

if (!$invoice_id) {
    echo "Lỗi: Mã hóa đơn không hợp lệ.";
    exit;
}

// Kiểm tra trạng thái của hóa đơn
$stmt = $conn->prepare("SELECT payment_status FROM Invoice WHERE invoice_id = ? AND user_id = ?");
$stmt->bind_param("ii", $invoice_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();
$stmt->close();

if ($invoice) {
    if ($invoice['payment_status'] === 'chưa thanh toán') {
        // Xóa chi tiết hóa đơn
        $stmt = $conn->prepare("DELETE FROM Invoice_Detail WHERE invoice_id = ?");
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $stmt->close();

        // Xóa hóa đơn
        $stmt = $conn->prepare("DELETE FROM Invoice WHERE invoice_id = ?");
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $stmt->close();

        echo "Hóa đơn đã được hủy thành công.";
    } else {
        echo "Lỗi: Không thể hủy hóa đơn vì trạng thái đã thanh toán.";
    }
} else {
    echo "Lỗi: Không tìm thấy hóa đơn.";
}

$conn->close();
