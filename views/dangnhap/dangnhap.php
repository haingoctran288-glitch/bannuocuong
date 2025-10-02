<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gé cafe Login</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo img {
            width: 120px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: 500;
            color: #495057;
            margin-bottom: 6px;
            font-size: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .btn-login {
            background-color: orange; /* Green color */
            color: #fff;
            border: none;
            padding: 14px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: darkorange; /* Darker green on hover */
        }

        .error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 12px;
            border-radius: 6px;
            text-align: left;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .forgot-password {
            font-size: 14px;
            color: #555;
            margin-top: 15px;
        }

        .forgot-password a {
            color: #007bff;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
           
        </div>
        <h1>Đăng Nhập</h1>
        <form action="index.php?chucnang=xulylogin" method="POST">
            <label for="username">Tên tài khoản:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>

            <?php if (!empty($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <input class ="btn-login"type="submit" value="Đăng Nhập" name="login">
        </form>

        <div class="forgot-password">
            Bạn chưa có tài khoản? <a href="index.php?chucnang=dangki">Đăng Ký</a> <br>
            <a href="../views/dangnhap/quenmk.php">Bạn quên mật khẩu?</a>
        </div>
    </div>
</body>
</html>
