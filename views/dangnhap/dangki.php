<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gé cafe Registration</title>
    <link rel="stylesheet" href="dangki.css">
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
            width: 100%;
            max-width: 480px;
            padding: 20px;
            box-sizing: border-box;
        }

        .register-container {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 15px;
            color: #2c3e50;
            font-weight: 700;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 6px;
            color: #495057;
            font-weight: 500;
            font-size: 15px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ced4da;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            border-color: #80bdff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        input[type="submit"] {
            background-color: orange; /* Green button */
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            border: none;
            padding: 14px;
            transition: background-color 0.3s;
            border-radius: 6px;
        }

        input[type="submit"]:hover {
            background-color: darkorange; /* Darker green on hover */
        }

        .error,
        .success {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: left;
            font-size: 13px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .logo img {
            max-width: 120px;
            margin-bottom: 2px;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .register-container {
                width: 90%;
                padding: 20px;
            }

            h1 {
                font-size: 22px;
            }

            label,
            input {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="logo">
             <!-- Ensure the image path is correct -->
            </div>
            <h1>Đăng ký tài khoản</h1>
            <form action="index.php?chucnang=xulydangki" method="POST">
                <label for="username">Tên tài khoản:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" required>

                <label for="phone_number">Số điện thoại:</label>
                <input type="text" id="phone_number" name="phone_number" required>

                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address">

                <input type="submit" value="Đăng ký" name="register">
            </form>

<script>
function validateName() {
    const usernameInput = document.getElementById("username");
    const username = usernameInput.value.trim();

    // Kiểm tra nếu tên không viết hoa chữ cái đầu của mỗi từ
    const capitalized = username.split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join(' ');

    if (username !== capitalized) {
        alert("Tên tài khoản phải viết hoa chữ cái đầu của mỗi từ!");
        usernameInput.value = capitalized; // Tự động sửa lại
        return false; // Ngăn việc gửi biểu mẫu
    }

    return true; // Hợp lệ
}
</script>
        </div>
    </div>
</body>
</html>
