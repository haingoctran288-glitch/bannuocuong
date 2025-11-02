<?php
require_once('../model/config.php');

// Lấy ID đơn hàng từ URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID đơn hàng không hợp lệ!");
}

// Lấy thông tin đơn hàng từ DB
$sql = "SELECT * FROM invoice WHERE invoice_id = $id";
$result = mysqli_query($conn, $sql);
$invoice = mysqli_fetch_assoc($result);

if (!$invoice) {
    die("Không tìm thấy đơn hàng có mã #$id");
}

// Nếu người dùng bấm nút lưu thay đổi
if (isset($_POST['update'])) {
    $recipient_name = mysqli_real_escape_string($conn, $_POST['recipient_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $billing_address = mysqli_real_escape_string($conn, $_POST['billing_address']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $update_sql = "
        UPDATE invoice 
        SET recipient_name = '$recipient_name',
            phone = '$phone',
            billing_address = '$billing_address',
            notes = '$notes'
        WHERE invoice_id = $id
    ";

    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Cập nhật đơn hàng thành công!'); window.location='hoadon.php';</script>";
    } else {
        echo "Lỗi khi cập nhật: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa đơn hàng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2 class="mb-4">Sửa thông tin đơn hàng #<?php echo htmlspecialchars($invoice['invoice_id']); ?></h2>

    <form method="POST">
        <div class="form-group">
            <label>Tên người nhận</label>
            <input type="text" name="recipient_name" class="form-control" 
                   value="<?php echo htmlspecialchars($invoice['recipient_name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control" 
                   value="<?php echo htmlspecialchars($invoice['phone']); ?>" required>
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

        <button type="submit" name="update" class="btn btn-success">Lưu thay đổi</button>
        <a href="hoadon.php" class="btn btn-secondary">Quay lại</a>
    </form>
</body>
</html>
