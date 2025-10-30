<?php include 'auth_admin.php'; ?>

<?php
    require_once('../model/config.php');
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    $sql = 'select * from User';
    $tacauser = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        article {
            height: 100vh;
        }
    </style>
</head>

<body class="container-fluid">
    <article class="row">
        <section class="col-2 bg-secondary pb-4">
            <figure class="figure mt-3 center">
                <img src="../img/logo bee.png" class="figure-img img-fluid rounded" alt="...">
                <figcaption class="figure-caption text-center text-white font-weight-bold">
                <?php if (isset($_SESSION['username'])) { ?>
                    <b style=" position:relative; top:-4px; vertical-align: middle; font-weight:400;"> Xin chào - <?php echo $user; ?></b>
                <?php } ?>
                    <br>
                    <a class="text-dark" href="../control/index.php?chucnang=logout">Đăng Xuất</a>
                </figcaption>
            </figure>
            <hr>
            <nav>
            <div class="list-group">
                <a class="list-group-item list-group-item-action list-group-item-dark" href="danhmuc.php">
                    <i class="bi bi-clipboard mr-2" style="font-size: 20px;"></i>Quản Lý Danh Mục
                </a>
                
                <a class="list-group-item list-group-item-action list-group-item-dark" href="index.php">
                    <i class="bi bi-box2 mr-2" style="font-size: 20px;"></i>Quản Lý Sản Phẩm
                </a>
                
                <a class="list-group-item list-group-item-action list-group-item-dark" href="conments.php">
                    <i class="bi bi-chat-text mr-2" style="font-size: 20px;"></i>Quản Lý Bình Luận
                </a>
                <a class="list-group-item list-group-item-action list-group-item-dark" href="donhang.php">
                    <i class="bi bi-file-text mr-2" style="font-size: 20px;"></i>Quản Lý Đơn Hàng
                </a>
                <a class="list-group-item list-group-item-action list-group-item-dark" href="hoadon.php">
                    <i class="bi bi-file-earmark-text mr-2" style="font-size: 20px;"></i>Quản Lý Hóa Đơn
                </a>
            </div>

            </nav>
        </section>
        <section class="col-10 bg-light">
            <h2 class="mt-3">Quản lý Tài Khoản</h2>
            <!--     -->
            <table class="table table-bordered table-hover bg-white">
                
                <tr class="table-active">
                    <td>Mã TK</td>
                    <td>Tên Tài Khoản</td>
                    <td>Email</td>
                    <td>Só Điện Thoại</td>
                    <td>Địa chỉ</td>
                    <td>Hành Động</td>
                </tr>
                <?php while ($user = mysqli_fetch_assoc($tacauser)) { ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['phone_number']; ?></td>
                <td><?php echo $user['address']; ?></td>
                
                <td>
                  
                    <a class="btn btn-danger" href="../control/index.php?chucnang=xoa_user&ma=<?php echo $user['user_id']; ?>">Xóa</a>
                </td>
            </tr>
        <?php } ?>
               

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
    <script>

    </script>
</body>

</html>