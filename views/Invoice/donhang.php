<?php
require_once('../../model/config.php');

// Kiểm tra trạng thái session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Chuyển hướng đến trang đăng nhập nếu chưa đăng nhập
    exit;
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách hóa đơn từ cơ sở dữ liệu
$sql = "SELECT i.invoice_id, i.invoice_date, i.payment_status, i.total_amount, 
               i.due_date, i.billing_address
        FROM Invoice i
        WHERE i.user_id = ?
        ORDER BY i.invoice_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$invoices = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn của tôi</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #046933; /* Dark green color */
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #007a2a; /* Darker green for header */
    color: white;
}

tr:hover {
    background-color: #f1f1f1; /* Light grey on hover */
}

button {
    background-color: #FFA500; /* Orange color */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    display: block;
    margin: 20px auto;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #FF8C00; /* Darker orange on hover */
}

p {
    text-align: center;
    font-size: 18px;
    color: #666;
}
    </style>
</head>
<body>
    <h1>Đơn Hàng Của Tôi</h1>
    
    <?php if (!empty($invoices)): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã Đơn hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đến hạn</th>
                    <th>Trạng thái đơn hàng</th>
                    <th>Địa chỉ đặt hàng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?php echo $invoice['invoice_id']; ?></td>
                        <td><?php echo $invoice['total_amount'], "đ"; ?></td>
                        <td><?php echo $invoice['due_date']; ?></td>
                        <td><?php echo $invoice['payment_status']; ?></td>
                        <td><?php echo $invoice['billing_address']; ?></td>
                        <td>
                            <!-- Nút hủy đơn -->
                            <form action="../../control/index.php?chucnang=cancel_order" method="post" style="display: inline;">
                                <input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
                                <button type="submit" name="action" value="cancel_order" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">Hủy Đơn</button>
                            </form>
                            
                            <!-- Nút thanh toán -->
                            <?php if ($invoice['payment_status'] == 'Chưa Thanh Toán'): ?>
                                <form action="./thanhtoan.php?invoice_id=<?php echo $invoice['invoice_id']; ?>" method="post" style="display: inline;">
                                    <input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
                                    <button type="submit" name="action" value="process_payment" onclick="return confirm('Bạn có chắc chắn muốn thanh toán đơn hàng này?');">Thanh Toán</button>
                                </form>
                            <?php endif; ?>

                            <!-- Nút chi tiết -->
                            <form action="set_invoice_session.php" method="post" style="display: inline;">
                                <input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
                                <button type="submit">Xem Chi Tiết</button>
                            </form>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
    <?php else: ?>
        <p>Bạn chưa có hóa đơn nào.</p>
    <?php endif; ?>

    <button onclick="window.location.href='../../index.php';">Quay lại trang chính</button>
</body>
</html>
