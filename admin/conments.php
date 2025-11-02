<?php include 'auth_admin.php'; ?>

<?php
require_once('../model/config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Lấy tất cả bình luận
$sql = "SELECT c.comment_id, c.product_id, c.user_id, c.comment_text, c.admin_reply, c.created_at, p.name_product 
        FROM comments c 
        JOIN Product p ON c.product_id = p.product_id";
$comments = mysqli_query($conn, $sql);
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
                <img src="../img/logo bee.png" class="figure-img img-fluid rounded" alt="..." >
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
                <a class="list-group-item list-group-item-action list-group-item-dark" href="index.php">
                    <i class="bi bi-box2 mr-2" style="font-size: 20px;"></i>Quản Lý sản phẩm
                </a>

                <a class="list-group-item list-group-item-action list-group-item-dark" href="danhmuc.php">
                    <i class="bi bi-clipboard mr-2" style="font-size: 20px;"></i>Quản Lý Danh Mục
                </a>
                
                <a class="list-group-item list-group-item-action list-group-item-dark" href="user.php">
                    <i class="bi bi-person mr-2" style="font-size: 20px;"></i>Quản Lý Tài Khoản
                </a>
                
                <a class="list-group-item list-group-item-action list-group-item-dark" href="donhang.php">
                    <i class="bi bi-chat-text mr-2" style="font-size: 20px;"></i>Quản Lý Đơn Hàng
                </a>
                
                <a class="list-group-item list-group-item-action list-group-item-dark" href="hoadon.php">

                    <i class="bi bi-file-earmark-text mr-2" style="font-size: 20px;"></i>Quản Lý Hóa Đơn
                </a>
            </div>

            </nav>
        </section>
        <section class="col-10 bg-light">
        <h1 class="mt-4">Quản Lý Bình Luận</h1>

                        


    <table class="table table-bordered table-hover mt-3">
        <thead class="table-dark">
            <tr>
                <th>Mã BL</th>
                <th>Sản Phẩm</th>
                <th>Người Dùng</th>
                <th>Nội Dung</th>
                <th>Phản Hồi</th>
                <th>Ngày Tạo</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($comment = mysqli_fetch_assoc($comments)) { ?>
                <tr>
                    <td><?php echo $comment['comment_id']; ?></td>
                    <td><?php echo $comment['name_product']; ?></td>
                    <td><?php echo $comment['user_id']; ?></td>
                    <td><?php echo $comment['comment_text']; ?></td>
                    <td><?php echo $comment['admin_reply'] ?: 'Chưa phản hồi'; ?></td>
                    <td><?php echo $comment['created_at']; ?></td>
                    <td>
    <!-- Form Phản Hồi -->
    <form method="POST" action="../control/index.php?chucnang=reply_comment" class="mb-2">
        <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
        <textarea class="form-control mb-2" name="admin_reply" placeholder="Nhập phản hồi..."><?php echo htmlspecialchars($comment['admin_reply']); ?></textarea>
        <button type="submit" class="btn btn-sm btn-primary">Lưu Phản Hồi</button>
    </form>

    <!-- Nút xóa -->
    <a href="delete_comment.php?id=<?php echo $comment['comment_id']; ?>"
   onclick="return confirm('Bạn có chắc muốn xóa bình luận này không?');"
   class="btn btn-sm btn-danger">Xóa</a>
</td>

                </tr>
            <?php } ?>
        </tbody>
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