<?php include 'auth_admin.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Danh Mục</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            text-align: left;
        }

        input[type="text"] {
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sửa Danh Mục</h2>
        <?php if (isset($danhmuc)) { ?>
        <form action="index.php?chucnang=xulysuadm&ma=<?php echo $danhmuc['category_id']; ?>" method="post">
            <label for="name_category">Tên danh mục</label>
            <input type="text" name="name_category" id="name_category" value="<?php echo $danhmuc['name_category']; ?>" required>
            <br>
            <input type="submit" value="Cập nhật">
        </form>
        <?php } else { ?>
        <p>Không tìm thấy danh mục.</p>
        <?php } ?>
        <a href="your-back-url.php" class="back-link">Quay lại</a> <!-- Thay đổi your-back-url.php thành URL thực tế -->
    </div>
</body>
</html>