<?php include 'auth_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        img {
            display: block;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
<form action="index.php?chucnang=xulysua&ma=<?php echo $sanpham['product_id']; ?>" method="post" enctype="multipart/form-data">
    <label for="product_id">Mã Sản Phẩm</label>
    <input type="text" name="product_id" value="<?php echo $sanpham['product_id']; ?>" readonly><br>
    
    <label for="name_product">Tên Sản Phẩm</label>
    <input type="text" name="name_product" value="<?php echo $sanpham['name_product']; ?>"><br>
    
    <label for="description">Mô Tả</label>
    <input type="text" name="description" value="<?php echo $sanpham['description']; ?>"><br>
    
    <label for="price">Giá</label>
    <input type="number" name="price" step="0.001" value="<?php echo $sanpham['price']; ?>"><br>
    
    <label for="address">Hình ảnh hiện tại</label>
    <img src="../control/<?php echo $sanpham['address']; ?>" alt="Hình ảnh" width="100px" height="150px;"><br>
    
    <label for="address">Cập nhật hình ảnh</label>
    <input type="file" name="address"><br>
    
    <input type="submit" value="Chỉnh sửa sản phẩm">
</form>
</body>
</html>
