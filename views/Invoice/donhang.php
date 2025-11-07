<?php
require_once('../../model/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

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
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đơn hàng của tôi</title>

<style>
body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background: #f4f6f8;
    color: #333;
    margin: 0;
    padding: 30px 10px;
}

h1 {
    text-align: center;
    color: #2e7d32;
    font-size: 28px;
    margin-bottom: 25px;
}

.table-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0,0,0,0.1);
    overflow-x: auto;
    padding: 20px;
    max-width: 1100px;
    margin: 0 auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 16px;
}

thead {
    background: #43a047;
    color: white;
}

th, td {
    padding: 14px 12px;
    text-align: left;
}

tr:nth-child(even) {
    background: #f9f9f9;
}

tr:hover {
    background: #eef6ee;
}

th {
    font-weight: 600;
}

.action-buttons form {
    display: inline-block;
    margin: 4px;
}

button {
    border: none;
    border-radius: 6px;
    padding: 8px 14px;
    font-size: 14px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

button[name="action"][value="cancel_order"] {
    background-color: #e53935;
}

button[name="action"][value="cancel_order"]:hover {
    background-color: #c62828;
}

button[name="action"][value="process_payment"],
button[type="submit"][value="process_payment"] {
    background-color: #2e7d32;
}

button[name="action"][value="process_payment"]:hover {
    background-color: #1b5e20;
}

button[type="submit"]:not([name="action"]) {
    background-color: #607d8b;
}

button[type="submit"]:not([name="action"]):hover {
    background-color: #455a64;
}

.empty-message {
    text-align: center;
    font-size: 18px;
    color: #666;
    margin-top: 50px;
}

.back-btn {
    display: block;
    margin: 30px auto 0;
    background: #2196f3;
    color: white;
    border: none;
    padding: 12px 22px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.back-btn:hover {
    background: #1976d2;
    transform: translateY(-2px);
}
</style>
</head>

<body>
<h1>Đơn hàng của tôi</h1>

<div class="table-container">
    <?php if (!empty($invoices)): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Tổng tiền</th>
                    <th>Ngày đến hạn</th>
                    <th>Trạng thái</th>
                    <th>Địa chỉ đặt hàng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?php echo $invoice['invoice_id']; ?></td>
                        <td><?php echo number_format($invoice['total_amount'], 0, ',', '.'); ?>đ</td>
                        <td><?php echo $invoice['due_date']; ?></td>
                        <td><?php echo htmlspecialchars($invoice['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['billing_address']); ?></td>
                        <td class="action-buttons">
                            <form action="../../control/index.php?chucnang=cancel_order" method="post">
                                <input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
                                <button type="submit" name="action" value="cancel_order" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');">Hủy</button>
                            </form>

                            <?php if ($invoice['payment_status'] == 'Chưa Thanh Toán'): ?>
                                <form action="./thanhtoan.php?invoice_id=<?php echo $invoice['invoice_id']; ?>" method="post">
                                    <input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
                                    <button type="submit" name="action" value="process_payment" onclick="return confirm('Bạn có chắc chắn muốn thanh toán đơn hàng này?');">Thanh Toán</button>
                                </form>
                            <?php endif; ?>

                            <form action="set_invoice_session.php" method="post">
                                <input type="hidden" name="invoice_id" value="<?php echo $invoice['invoice_id']; ?>">
                                <button type="submit">Chi Tiết</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-message">Bạn chưa có hóa đơn nào.</p>
    <?php endif; ?>
</div>

<button onclick="window.location.href='../../index.php';" class="back-btn">← Quay lại trang chính</button>
</body>
</html>
