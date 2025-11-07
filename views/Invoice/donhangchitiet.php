<?php
require_once('../../model/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$invoice_id = $_SESSION['invoice_id'] ?? null;

if (!$invoice_id) {
    echo "<div style='text-align:center; margin-top:50px; font-family:Arial;'>
            <h2>⚠️ Không tìm thấy hóa đơn</h2>
            <a href='../../views/Invoice/hoadon.php' 
               style='display:inline-block; padding:10px 20px; background:orange; color:white; border-radius:6px; text-decoration:none;'>
               Quay lại
            </a>
          </div>";
    exit;
}

// Lấy chi tiết hóa đơn
$sql = "SELECT d.invoice_detail_id, p.name_product, p.address, d.quantity, d.price, d.total_price
        FROM Invoice_Detail d
        JOIN Product p ON d.product_id = p.product_id
        WHERE d.invoice_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết hóa đơn</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        h1 {
            text-align: center;
            background-color: #ff8c00;
            color: white;
            margin: 0;
            padding: 18px 0;
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #ffa31a;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #f0f0f0;
        }

        td.product-info {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: flex-start;
        }

        td.product-info img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        td.product-info span {
            font-weight: 500;
            color: #333;
            text-align: left;
        }

        tr:hover {
            background-color: #f9f9f9;
            transition: 0.2s;
        }

        .btn-back {
            display: block;
            width: fit-content;
            margin: 25px auto;
            background-color: #ff8c00;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 12px 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #e67300;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            td.product-info {
                flex-direction: column;
                text-align: center;
            }
            td.product-info img {
                width: 60px;
                height: 60px;
            }
            th, td {
                font-size: 13px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Chi tiết hóa đơn #<?php echo htmlspecialchars($invoice_id); ?></h1>

        <?php if (!empty($details)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng giá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $detail): ?>
                        <tr>
                            <td class="product-info">
                                <img src="../../control/<?php echo htmlspecialchars($detail['address']); ?>" alt="Sản phẩm">
                                <span><?php echo htmlspecialchars($detail['name_product']); ?></span>
                            </td>
                            <td><?php echo $detail['quantity']; ?></td>
                            <td><?php echo number_format($detail['price'] * 1000, 0, ',', '.'); ?>đ</td>
                            <td><?php echo number_format($detail['total_price'] * 1000, 0, ',', '.'); ?>đ</td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center; padding:30px; font-size:16px; color:#666;">Không tìm thấy chi tiết hóa đơn.</p>
        <?php endif; ?>

        <button class="btn-back" onclick="window.location.href='../../views/Invoice/hoadon.php';">Quay lại</button>
    </div>
</body>
</html>
