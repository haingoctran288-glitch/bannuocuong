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
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách hóa đơn</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #d4fc79, #96e6a1);
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            animation: fadeIn 0.6s ease;
        }

        h1 {
            text-align: center;
            color: #2e7d32;
            font-size: 28px;
            text-transform: uppercase;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .invoice {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            background: #f9fff7;
            transition: all 0.3s ease;
        }

        .invoice:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 128, 0, 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .invoice-id {
            font-weight: bold;
            color: #007a2a;
            font-size: 16px;
        }

        .status {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
        }

        .status.unpaid { background-color: #f57c00; }
        .status.paid { background-color: #43a047; }
        .status.pending { background-color: #0288d1; }

        .invoice p {
            margin: 6px 0;
            font-size: 15px;
            color: #555;
        }

        .invoice p strong {
            color: #2e7d32;
        }

        .btn-back {
            display: block;
            width: fit-content;
            margin: 25px auto 0;
            background-color: #2e7d32;
            color: white;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 30px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-back:hover {
            background-color: #1b5e20;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .invoice {
                padding: 15px;
            }
            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Danh sách hóa đơn của bạn</h1>

    <?php foreach ($invoices as $invoice): ?>
        <div class="invoice">
            <div class="invoice-header">
                <span class="invoice-id">Mã hóa đơn: #<?php echo $invoice['invoice_id']; ?></span>
                <?php 
                    $statusClass = 'unpaid';
                    if (strpos($invoice['payment_status'], 'thành') !== false) $statusClass = 'paid';
                    elseif (strpos($invoice['payment_status'], 'chờ') !== false) $statusClass = 'pending';
                ?>
                <span class="status <?php echo $statusClass; ?>">
                    <?php echo htmlspecialchars($invoice['payment_status']); ?>
                </span>
            </div>
            <p><strong>Ngày hóa đơn:</strong> <?php echo $invoice['invoice_date']; ?></p>
            <p><strong>Tổng tiền:</strong> <?php echo number_format($invoice['total_amount'], 0, ',', '.'); ?>đ</p>
            <p><strong>Ngày hết hạn:</strong> <?php echo $invoice['due_date']; ?></p>
            <p><strong>Địa chỉ thanh toán:</strong> <?php echo htmlspecialchars($invoice['billing_address']); ?></p>
            <p><strong>Người nhận:</strong> <?php echo htmlspecialchars($invoice['recipient_name']); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($invoice['phone']); ?></p>
            <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($invoice['notes']); ?></p>
        </div>
    <?php endforeach; ?>

    <a href="../../index.php" class="btn-back">← Quay lại Trang chủ</a>
</div>
</body>
</html>
