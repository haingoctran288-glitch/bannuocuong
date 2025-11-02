<?php
include '../model/config.php';

// Lấy ID hóa đơn cần sửa
if (!isset($_GET['id'])) {
    header("Location: hoadon.php");
    exit();
}
$invoice_id = intval($_GET['id']);

// Lấy thông tin hóa đơn từ DB
$sql = "SELECT * FROM invoice WHERE invoice_id = $invoice_id";
$result = mysqli_query($conn, $sql);
$invoice = mysqli_fetch_assoc($result);

if (!$invoice) {
    die("Không tìm thấy hóa đơn!");
}

// Khi người dùng nhấn “Lưu thay đổi”
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $billing_address = mysqli_real_escape_string($conn, $_POST['billing_address']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $update_sql = "
        UPDATE invoice
        SET payment_status = '$payment_status',
            billing_address = '$billing_address',
            notes = '$notes'
        WHERE invoice_id = $invoice_id
    ";
    $update_result = mysqli_query($conn, $update_sql);

    if ($update_result) {
        header("Location: hoadon.php?msg=capnhat_thanhcong");
        exit();
    } else {
        echo "Lỗi khi cập nhật hóa đơn: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Hóa Đơn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h3 class="mb-4">Sửa thông tin hóa đơn #<?php echo $invoice['invoice_id']; ?></h3>

    <form method="POST">
        <div class="form-group">
            <label>Trạng thái thanh toán</label>
            <select name="payment_status" class="form-control" required>
                <option value="Chưa Thanh Toán" <?php if($invoice['payment_status']=='Chưa Thanh Toán') echo 'selected'; ?>>Chưa Thanh Toán</option>
                <option value="Thanh toán thành công" <?php if($invoice['payment_status']=='Thanh toán thành công') echo 'selected'; ?>>Thanh toán thành công</option>
            </select>
        </div>

        <div class="form-group">
            <label>Địa chỉ giao hàng</label>
            <input type="text" name="billing_address" class="form-control" 
                   value="<?php echo htmlspecialchars($invoice['billing_address']); ?>" required>
        </div>

        <div class="form-group">
            <label>Ghi chú</label>
            <textarea name="notes" class="form-control" rows="3"><?php echo htmlspecialchars($invoice['notes']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
        <a href="hoadon.php" class="btn btn-secondary">Quay lại</a>
    </form>
</body>
</html>
