<?php
include __DIR__ . '/../../model/config.php';

$msg = "";

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['password']);

    // kiểm tra email có tồn tại trong DB không
    $check = $conn->query("SELECT * FROM user WHERE email='$email'");

    if ($check->num_rows > 0) {
        // mã hóa mật khẩu (dùng md5 cho đơn giản, có thể đổi sang password_hash)
        $salt = 'chuoi_bao_mat'; // cùng chuỗi salt với file đăng ký
$hashed = hash('sha256', $salt . $new_password);

        $conn->query("UPDATE user SET password='$hashed' WHERE email='$email'");
        $msg = "<span style='color:green;'>✅ Đổi mật khẩu thành công! <a href='../../index.php'>Quay về trang chủ</a></span>";

    } else {
        $msg = "<span style='color:red;'>❌ Email không tồn tại!</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f3f4f6; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
        }
        .box {
            background: #fff; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 350px; 
            text-align: center;
        }
        input {
            width: 90%; 
            padding: 10px; 
            margin: 10px 0; 
            border: 1px solid #ccc; 
            border-radius: 5px;
        }
        button {
            background: orange; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px; 
            color: white; 
            cursor: pointer;
        }
        button:hover { opacity: 0.9; }
        a { color: blue; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Quên mật khẩu</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Nhập email của bạn" required><br>
            <input type="password" name="password" placeholder="Nhập mật khẩu mới" required><br>
            <button type="submit" name="submit">Cập nhật mật khẩu</button>
        </form>
        <p><?= $msg ?></p>
    </div>
</body>
</html>
