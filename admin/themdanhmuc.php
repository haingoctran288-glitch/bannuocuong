<?php 
include 'auth_admin.php'; 
require_once('../model/config.php');

// Khởi động session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy danh sách danh mục từ bảng Category
$sql_danhmuc = "SELECT category_id, name_category FROM Category";
$result_danhmuc = mysqli_query($conn, $sql_danhmuc);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Danh Mục</title>
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
            width: 320px;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        select,
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: black;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: white;
            color: black;
            box-shadow: inset 0px 0px 5px 5px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>

<form action="index.php?chucnang=xulythemdm" method="post" enctype="multipart/form-data" class="them-right">
    <label for="category_id">Mã Danh Mục</label>
    <select name="category_id" id="category_id" required>
        <option value="">-- Chọn danh mục --</option>
        <?php 
        if ($result_danhmuc && mysqli_num_rows($result_danhmuc) > 0) {
            while ($row = mysqli_fetch_assoc($result_danhmuc)) { ?>
                <option value="<?php echo $row['category_id']; ?>">
                    <?php echo htmlspecialchars($row['name_category']); ?>
                </option>
        <?php 
            } 
        } else {
            echo '<option value="">Chưa có danh mục nào</option>';
        }
        ?>
    </select>

    <label for="tendm">Tên danh mục</label>
    <input type="text" name="name_category" id="name_category" required> <br>

    <input type="submit" value="Thêm mới danh mục">
</form>

</body>
</html>
