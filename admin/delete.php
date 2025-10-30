<?php include 'auth_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Sản Phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-delete {
            background-color: #ff4d4d;
            color: #fff;
        }
        .btn-cancel {
            background-color: #ddd;
            color: #333;
        }
        button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bạn có chắc chắn xóa sản phẩm này?</h1>
        <h2><?php echo $sanpham['name_product']; ?></h2>
        <p>Mã sản phẩm: <strong><?php echo $sanpham['product_id']; ?></strong></p>
        <form action="index.php?chucnang=xulyxoa" method="post">
            <!-- Lưu mã xóa -->
            <input type="hidden" name="macanxoa" value="<?php echo $sanpham['product_id']; ?>">
            <button class="btn-delete" name="xacnhan" value="xoa">Xóa</button>
            <button class="btn-cancel" name="xacnhan" value="huy">Hủy</button>
        </form>
    </div>
</body>
</html>
