<?php
require_once('../../model/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['invoice_id'])) {
    $invoice_id = $_SESSION['invoice_id'];
} else {
    echo "Không tìm thấy hóa đơn.";
    exit;
}
$user_id = $_SESSION['user_id'];

if (!$invoice_id) {
    echo "Lỗi: Mã đơn hàng không hợp lệ.";
    exit;
}

// Lấy chi tiết hóa đơn từ bảng Invoice_Detail và thông tin sản phẩm từ bảng Product
$sql = "SELECT d.invoice_detail_id, p.name_product,p.address, d.quantity, d.price, d.total_price
        FROM Invoice_Detail d
        JOIN Product p ON d.product_id = p.product_id
        WHERE d.invoice_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// Đảm bảo kết nối được đóng sau khi lấy dữ liệu
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết hóa đơn</title>
    
    <style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
}

h1 {
    text-align: center;
    margin: 20px 0;
    font-size: 26px;
    color: #222;
    font-weight: bold;
    letter-spacing: 1px;
}

/* Định dạng bảng */
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    text-align: center;
    vertical-align: middle;
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: #555;
}

table th {
    background-color: darkorange;
    color: white;
    font-weight: bold;
    text-transform: uppercase;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table td img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

table td.product-info {
    display: flex;
    align-items: center;
    gap: 10px;
    justify-content: start;
}

table td.product-info h3 {
    font-size: 14px;
    margin: 0;
    color: #333;
    text-align: left;
    font-weight: normal;
}

/* Nút quay lại */
button {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    font-size: 14px;
    background-color: orange;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-transform: uppercase;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: darkorange;
}

/* Đáp ứng trên màn hình nhỏ */
@media screen and (max-width: 768px) {
    table {
        width: 100%;
    }

    h1 {
        font-size: 20px;
    }

    table td img {
        width: 60px;
        height: 60px;
    }

    button {
        width: 80%;
        font-size: 12px;
    }
}

    </style>
</head>
<body>
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
                            <img src="../../control/<?php echo $detail['address']; ?>" alt="Sản phẩm">
                            <h3><?php echo $detail['name_product']; ?></h3>
                        </td>
                        <td><?php echo $detail['quantity']; ?></td>
                        <td><?php echo $detail['price'], "đ"; ?></td>
                        <td><?php echo $detail['total_price'], "đ"; ?></td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Không tìm thấy chi tiết hóa đơn.</p>
    <?php endif; ?>

    <button onclick="window.location.href='../../views/Invoice/hoadon.php';">Quay lại</button>
</body>
</html>
