<?php include 'auth_admin.php'; ?>

<?php
require_once('../model/config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$user = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$sql = 'SELECT * FROM Invoice';
$tacainvoice = mysqli_query($conn, $sql);
if (!$conn) {
    die("Kết nối cơ sở dữ liệu tdất bại: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Quản lý Hóa Đơn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        article { height: 100vh; }
    </style>
</head>
<body class="container-fluid">
    <article class="row">
        <section class="col-2 bg-secondary pb-4">
            <figure class="figure mt-3 center">
                <img src="../img/logo bee.png" class="figure-img img-fluid rounded">
                <figcaption class="figure-caption text-center text-white font-weight-bold">
                <?php if (isset($_SESSION['username'])) { ?>
                    <b style="position:relative; top:-4px; vertical-align: middle; font-weight:400;">Xin chào - <?php echo htmlspecialchars($user); ?></b>
                <?php } ?>
                    <br>
                    <a class="text-dark" href="../control/index.php?chucnang=logout">Đăng Xuất</a>
                </figcaption>
            </figure>
            <hr>
            <nav>   
            <div class="list-group">
                <a class="list-group-item list-group-item-action list-group-item-dark" href="index.php">
                    <i class="bi bi-box2 mr-2" style="font-size: 20px;"></i>Quản Lý sản phẩm
                </a>
                <a class="list-group-item list-group-item-action list-group-item-dark" href="user.php">
                    <i class="bi bi-person mr-2" style="font-size: 20px;"></i>Quản Lý Tài Khoản
                </a>
                <a class="list-group-item list-group-item-action list-group-item-dark" href="conments.php">
                    <i class="bi bi-chat-text mr-2" style="font-size: 20px;"></i>Quản Lý Bình Luận
                </a>

                <a class="list-group-item list-group-item-action list-group-item-dark" href="donhang.php">
                    <i class="bi bi-file-text mr-2" style="font-size: 20px;"></i>Quản Lý Đơn Hàng
                </a>
            </div>
            </nav>
        </section>
        <section class="col-10 bg-light">
            <h2 class="mt-3">Quản lý Hóa Đơn</h2>
            <table class="table table-bordered table-hover bg-white">
                <tr class="table-active">
                <td>Mã Hóa Đơn</td>
                    <td>Tổng tiền</td>
                    <td>Ngày đến hạn</td>
                    <td>Trạng thái đơn hàng</td>
                    <td>Địa chỉ đặt hàng</td>
                    <td>Tên người nhận</td>
                    <td>Số điện thoại</td>
                    <td>Ghi chú</td>
                    <td>thao tác</td>
                </tr>
                    <?php 
                    if ($tacainvoice) {
                        while ($invoice = mysqli_fetch_assoc($tacainvoice)) { ?>
                        <tr>
                        <td><?php echo $invoice['invoice_id']; ?></td>
                            <td><?php echo $invoice['total_amount'], "đ"; ?></td>
                            <td><?php echo $invoice['due_date']; ?></td>
                            <td><?php echo $invoice['payment_status']; ?></td>
                            <td><?php echo $invoice['billing_address']; ?></td>
                            <td><?php echo $invoice['recipient_name']; ?></td>
                            <td><?php echo $invoice['phone']; ?></td>
                            <td><?php echo $invoice['notes']; ?></td>
                            <td>
                                <a class="btn btn-info" href="sua_hoadon.php?id=<?php echo $invoice['invoice_id']; ?>">Sửa</a>
                                
                                
                                
                                <a class="btn btn-danger"
   href="delete_invoice.php?id=<?php echo $invoice['invoice_id']; ?>"
   onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này không?');">
   Xóa
</a>

                            
                            
                            </td>
                        </tr>
                    <?php }
                    } else {
                        echo "<tr><td colspan='3'>Không có dữ liệu</td></tr>";
                    } ?>
            </table>
            <ul class="pagination pagination-sm text-dark">
                <li class="page-item active" aria-current="page">
                    <a class="page-link bg-dark border-0" href="#">1</a>
                </li>
                <li class="page-item"><a class="page-link text-dark" href="#">2</a></li>
                <li class="page-item"><a class="page-link text-dark" href="#">3</a></li>
            </ul>
        </section>
    </article>
</body>
</html>