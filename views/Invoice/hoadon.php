<?php
require_once('../../model/config.php');

// Kiểm tra session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy tất cả thông tin hóa đơn từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM Invoice WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$invoices = [];
while ($row = $result->fetch_assoc()) {
    $invoices[] = $row;
}
$stmt->close();

// Kiểm tra sự tồn tại của hóa đơn
if (empty($invoices)) {
    echo "Không tìm thấy hóa đơn.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách hóa đơn</title>
    <style>
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f4f7f6;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .invoice-container {
        width: 100%;
        max-width: 800px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        padding: 20px;
    }

    .invoice-container h1 {
        font-size: 24px;
        text-align: center;
        color: #333333;
        margin-bottom: 30px;
        text-transform: uppercase;
    }

    .invoice-details {
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .invoice-details:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .invoice-details p {
        font-size: 14px;
        color: #555555;
        margin: 5px 0;
    }

    .invoice-details p strong {
        font-weight: 500;
        color: #222222;
    }

    .back-btn {
        display: block;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 20px auto 0;
        padding: 10px 20px;
        background: #4CAF50;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        transition: background 0.3s, transform 0.2s;
        cursor: pointer;
    }

    .back-btn:hover {
        background: #388e3c;
        transform: translateY(-3px);
    }

    hr {
        border: none;
        border-top: 1px solid #e0e0e0;
        margin: 20px 0;
    }

    @media (max-width: 600px) {
        .invoice-container {
            padding: 15px;
        }

        .invoice-details {
            padding: 10px;
        }

        .back-btn {
            font-size: 14px;
            padding: 8px 16px;
        }
    }
</style>

    </style>
</head>
<body>
<div class="invoice-container">
    <h1>Danh sách hóa đơn</h1>
    <?php foreach ($invoices as $invoice): ?>
        <div class="invoice-details">
            <p><strong>Mã hóa đơn:</strong> <?php echo $invoice['invoice_id']; ?></p>
            <p><strong>Ngày hóa đơn:</strong> <?php echo $invoice['invoice_date']; ?></p>
            <p><strong>Tổng tiền:</strong> <?php echo $invoice['total_amount'], 3; ?> VND</p>
            <p><strong>Ngày hết hạn:</strong> <?php echo $invoice['due_date']; ?></p>
            <p><strong>Địa chỉ thanh toán:</strong> <?php echo $invoice['billing_address']; ?></p>
            <p><strong>Tên người nhận:</strong> <?php echo $invoice['recipient_name']; ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo $invoice['phone']; ?></p>
            <p><strong>Ghi chú:</strong> <?php echo $invoice['notes']; ?></p>
        </div>
        <hr>
    <?php endforeach; ?>
    <a href="../../index.php" class="back-btn">Quay lại Trang Chủ</a>
</div>
</body>
</html>
