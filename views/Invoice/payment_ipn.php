<?php
$data = json_decode(file_get_contents('php://input'), true);

if ($data && $data['resultCode'] == 0) {
    // Cập nhật trạng thái hóa đơn
    // Ví dụ:
    // $stmt = $conn->prepare("UPDATE Invoice SET payment_status='Đã thanh toán' WHERE invoice_id=?");
    // $stmt->bind_param("i", $data['orderId']);
    // $stmt->execute();
    // $stmt->close();
}

http_response_code(200);
echo json_encode(['message' => 'Received']);
?>
