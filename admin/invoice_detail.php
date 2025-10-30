<?php
include 'auth_admin.php';
require_once('../model/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id'])) {
    $invoice_id = intval($_GET['id']); // ép kiểu int cho an toàn
} else {
    die("Không tìm thấy ID đơn hàng!");
}

$order_id = intval($_GET['id']);

// Lấy thông tin đơn hàng
$sql_order = "SELECT * FROM invoice WHERE invoice_id = $order_id";
$result_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($result_order);

if (!$order) {
    die("Không tìm thấy đơn hàng!");
}

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_items = "
    SELECT 
        d.product_id,
        p.name_product AS product_name,
        p.address AS product_image,
        d.quantity,
        d.price,
        d.total_price
    FROM invoice_detail d
    JOIN product p ON d.product_id = p.product_id
    WHERE d.invoice_id = $order_id
";
$result_items = mysqli_query($conn, $sql_items);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?php echo $order_id; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f7f7f7; }
        .container { margin-top: 30px; }
        .back-btn {
            display: inline-block;
            margin-bottom: 15px;
            background: #6c757d;
            color: white;
            padding: 8px 14px;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-btn:hover { background: #5a6268; }
        img { width: 70px; border-radius: 6px; }
    </style>
</head>
<body>
<div class="container bg-white p-4 rounded shadow-sm">
    <h2 class="mb-3">Chi tiết đơn hàng #<?php echo $order_id; ?></h2>

    <p><strong>Người đặt:</strong> <?php echo htmlspecialchars($order['recipient_name']); ?></p>

    <p><strong>Địa chỉ giao hàng:</strong> <?php echo $order['billing_address']; ?></p>
    <p><strong>Trạng thái:</strong> <?php echo $order['payment_status']; ?></p>
    <p><strong>Ngày đến hạn:</strong> <?php echo $order['due_date']; ?></p>
<p><strong>Tổng tiền:</strong> 
    <?php echo number_format($order['total_amount'] * 1000, 0, ',', '.'); ?> đ
</p>

    <h4 class="mt-4">Danh sách sản phẩm</h4>
    <table class="table">
    <thead>
        <tr>
            <th>Hình ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Thành tiền</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Giả sử bạn đã có $invoice_id từ $_GET['id']
        $sql = "SELECT p.name_product, p.address AS image, d.quantity, d.price, d.total_price
                FROM invoice_detail d
                JOIN product p ON d.product_id = p.product_id
                WHERE d.invoice_id = $invoice_id";


        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
               echo "<tr>";
$imgPath = '../' . $row['image']; // đi lên 1 cấp vì file này nằm trong /admin/
if (!file_exists(__DIR__ . '/../' . $row['image'])) {
    $imgPath = '../img/no-image.png'; // fallback nếu ảnh không tồn tại
}

            echo "<td><img src='" . htmlspecialchars($imgPath) . "' width='80' style='object-fit:cover;border-radius:6px;' onerror=\"this.src='../img/no-image.png'\" ></td>";
            echo "<td>" . htmlspecialchars($row['name_product']) . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . number_format($row['price'] * 1000, 0, ',', '.') . " đ</td>";
            echo "<td>" . number_format($row['total_price'] * 1000, 0, ',', '.') . " đ</td>";

            echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>Không có sản phẩm nào trong đơn hàng này.</td></tr>";
        }
        ?>
    </tbody>
</table>


    <a href="hoadon.php" class="back-btn">&larr; Quay lại</a>
</div>
</body>
</html>
