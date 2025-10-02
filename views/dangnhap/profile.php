
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
   
    <script src="./script.js"></script>
    <style>
        .logo{
            width: 100px;
            margin-right: 10px;
        }
        /* styles.css */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;

}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    border-bottom: 1px solid #e0e0e0;
}

.header-left {
    display: flex;
    align-items: center;
}
.header-left img {
    width: 300px;
}


.search-bar {
    width: 300px;
    padding: 8px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    background-color: #f0f0f0;
}

.header-right {
    display: flex;
    align-items: center;
}

.delivery-option {
    display: flex;
    align-items: center;
    background-color: #f0f0f0;
    padding: 5px 12px;
    border-radius: 20px;
    margin-right: 15px;
    font-size: 14px;
    color: #00693e;
    font-weight: 500;
}

.delivery-icon {
    width: 20px;
    height: 20px;
    margin-right: 8px;
}

.icons {
    display: flex;
    align-items: center;
    text-decoration: none;

}

.icon {
    width: 24px;
    height: 24px;
    margin-left: 12px;
    color: #00693e;
}


a{
    text-decoration: none;
}
/* Navbar styling */
.navbar {
    background-color: #fff;
    border-bottom: 1px solid #e0e0e0;
    padding: 10px 20px;
}

.nav-list {
    list-style: none;
    display: flex;
    justify-content: center;
    gap: 30px;
}

.nav-list li {
    position: relative;
}

.nav-list a {
    text-decoration: none;
    color: #666;
    font-size: 16px;
    font-weight: 500;
    padding: 10px;
}

.nav-list a:hover {
    color: #00693e; /* Green color on hover */
}

/* Dropdown menu styling */
.dropdown:hover .dropdown-content {
    display: flex;
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #fff;
    border: 1px solid #e0e0e0;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    gap: 30px;
    z-index: 100;
}

.submenu {
    display: flex;
    flex-direction: column;
    width: 200px;
}

.submenu h4 {
    color: black;
    font-size: 16px;
    margin-bottom: 10px;
}

.submenu ul {
    list-style: none;
}

.submenu ul li {
    color: #333;
    font-size: 14px;
    margin-bottom: 8px;
}

.submenu ul li:hover {
    color: #00693e; /* Green color on hover */
    cursor: pointer;
}



.contact-section {
    padding: 50px 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
}

.containerr {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
    display: flex;
    flex-wrap: wrap;
}

.contact-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.left-side {
    flex: 1;
    min-width: 300px;
    max-width: 600px;
    padding: 20px;
}

.left-side img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 10px;
}

.right-side {
    flex: 1;
    min-width: 300px;
    max-width: 600px;
    padding: 20px;
}

.right-side h2 {
    font-size: 24px;
    margin-bottom: 10px;
}

.right-side p {
    margin-bottom: 20px;
    color: #666;
}


.customer-service-section {
    padding: 50px 20px;
    background-color: #fff;
    text-align: center;
}

.customer-service-section .container {
    display: flex;
    justify-content: space-around;
    max-width: 1200px;
    margin: 0 auto;
    flex-wrap: wrap;
}

.service-item {
    display: flex;
    align-items: center;
    flex-direction: column;
    margin: 20px;
}

.service-icon {
    width: 50px;
    height: 50px;
    margin-bottom: 10px;
}

.service-text p {
    margin: 5px 0;
    font-size: 1.1em;
    color: #333;
}

.service-text p:first-child {
    font-weight: bold;
}



.slideshow-container {
    position: relative;
    overflow: hidden;
    max-width: 100%;
    height: 500px; /* Set the height to match your images */
    margin: auto;
}

.slides-wrapper {
    display: flex;
    transition: transform 0.5s ease; /* Smooth transition effect */
}

.mySlides {
    min-width: 100%; /* Each slide takes up 100% width */
    height: auto;
    flex-shrink: 0;
}

.slide-left {
    animation-name: slideLeft;
}

.slide-right {
    animation-name: slideRight;
}

@keyframes slideLeft {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}

@keyframes slideRight {
    from { transform: translateX(-100%); }
    to { transform: translateX(0); }
}

/* Dots styling */
.dot {
    cursor: pointer;
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    transition: background-color 0.6s ease;
}

.active, .dot:hover {   
    background-color: #717171;
}




/* Center content area with a max width */
.content {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Product list styling */
.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    max-width: 1200px;
    margin: 0 auto;
}

/* Product card styling */
/* Product card styling */
.product-card {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    width: calc(20% - 16px); /* Adjust width for five items per row */
    text-align: left; /* Align content to the left */
    padding: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column; /* Make the card contents stack vertically */
    justify-content: space-between; /* Ensure equal spacing between elements */
    align-items: center; /* Căn giữa tất cả các phần tử theo chiều ngang */
}

/* Item image styling */
.item {
    width: 100%;
    padding: 10px;
    border-radius: 8px ;
    overflow: hidden;
    display: flex;
    justify-content: center; /* Căn giữa theo chiều ngang */
    align-items: center; /* Căn giữa theo chiều dọc */
}

.item img {
    width: 80%; /* Tăng kích thước ảnh để phù hợp */
    height: auto;
    border-radius: 8px;
    object-fit: contain; /* Giữ tỷ lệ hình ảnh */
}

/* Name styling */
.product-card h3 {
    font-size: 16px;
    font-weight: bold;
    color: #006400; /* Green color for tea names */
    margin: 10px 0 5px 0;
    text-align: center; /* Căn giữa tên sản phẩm */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Button styling */
.product-card .add-to-cart {
    background-color: #FFA500; /* Orange color */
    color: white;
    padding: 10px 0;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    width: 100%;
    text-align: center;
    text-transform: uppercase;
    cursor: pointer;
    border: none;
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-card .add-to-cart:hover {
    background-color: #FF8C00; /* Darker orange on hover */
}
.btn-order {
    background-color: #FFA500; /* Orange background */
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    border: none;
    text-transform: none;
    transition: background-color 0.3s ease;
    width: 100%;
}

.btn-order:hover {
    background-color: #FF8C00; /* Darker orange on hover */
}

/* Heading styling */
h2 {
    color: black;
    margin-bottom: 20px;
    font-size: 24px;
    text-align: center;
}

/* Price styling */
.product-card p {
    font-size: 16px;
    font-weight: bold;
    color: black; /* Green color for price */
    margin: 5px 0 15px 0;
}

/* Button styling */
.btn-order {
    background-color: #006400; /* Green background */
    color: white;
    padding: 10px 20px; /* Padding for larger button */
    border-radius: 8px; /* Rounded corners */
    font-size: 16px; /* Font size for text */
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px; /* Space between icon and text */
    cursor: pointer;
    border: none;
    text-transform: none;
    transition: background-color 0.3s ease;
    width: 100%; /* Full width button */
}

.btn-order:hover {
    background-color: #004d00; /* Darker green on hover */
}

/* Heading styling */
h2 {
    color: #006400;
    margin-bottom: 20px;
    font-size: 24px;
    text-align: center;
}



.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
.subtitle {
    text-align: center;
    font-size: 18px;
    color: #555;
    margin-top: -10px; /* Đẩy gần hơn với tiêu đề */
    margin-bottom: 30px; /* Khoảng cách phía dưới subtitle */
    font-style: italic; /* Làm chữ hơi nghiêng để nổi bật */
}


.title {
    text-align: center;
    color: #046933;
    font-size: 32px;
    margin-bottom: 30px;
}

.news-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.news-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s;
    display: flex;
    flex-direction: column;
}

.news-card:hover {
    transform: scale(1.05);
}

.news-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.divider {
    height: 1px;
    background: black;
    margin: 0;
}

.news-info {
    padding: 15px;
    text-align: center;
}

.news-title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin: 10px 0 0;
    text-align: center;
}

.news-views {
    font-size: 14px;
    color: #777;
    margin-bottom: 10px;
    text-align: center;
}

.news-info {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: column;
}

.news-title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.news-title span {
    font-size: 14px;
    color: #777;
    margin-left: 10px;
    white-space: nowrap;
}
footer {
    background-color: black;
    color: white;
    padding: 20px;
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 0; /* Đảm bảo không có khoảng cách dưới footer */
}

.partners-section {
    margin-top: 0; /* Xóa khoảng cách phía trên */
    padding-top: 50px; /* Điều chỉnh padding nếu cần */
}



  .partners-section {
    text-align: center;
    padding: 50px 0;
}

.partners-section .container {
    max-width: 1200px;
    margin: 0 auto;
}

.partners-section h2 {
    font-size: 2em;
    margin-bottom: 10px;
}

.partners-section p {
    margin-bottom: 40px;
    color: #666;
}

.partners {
    display: flex;
    justify-content: space-around;
    align-items: center;
}

.partner img {
    max-width: 150px;
    max-height: 100px;
    object-fit: contain;
}
.user-greeting {
    font-size: 14px;
    color: black;
    margin-left: 10px;
}
.l1 {
    position: relative;
    display: inline-block;
}

/* Initially hide the login/sign-up menu */
.l1 ul {
    display: none;
    position: absolute;
    top: 100%; /* Place the menu directly below the icon */
    left: 50%;  /* Position it at the center */
    transform: translateX(-50%); /* Offset to ensure it's truly centered */
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Deeper shadow for better visibility */
    list-style: none;
    margin: 0;
    z-index: 100; /* Ensure the dropdown appears above other elements */
    width: 180px; /* Fixed width for consistent dropdown */
    opacity: 0; /* Hide the dropdown initially */
    visibility: hidden; /* Keep the dropdown invisible */
    transition: opacity 0.3s ease, visibility 0s 0.3s; /* Smooth fade-in/fade-out effect */
}

/* Show the menu when hovering over the .l1 container */
.l1:hover ul {
    display: block;
    opacity: 1;
    visibility: visible; /* Make it visible with a smooth transition */
    transition: opacity 0.3s ease, visibility 0s 0s; /* Instant visibility change when hovering */
}

/* Style the items inside the dropdown menu */
.l1 ul li {
    padding: 8px 15px;
    font-size: 16px;
    color: #333;
    transition: background-color 0.3s; /* Smooth background change on hover */
}

/* Style for the links inside the dropdown */
.l1 ul li a {
    text-decoration: none;
    color: #333;
    font-weight: 500;
    display: block;
}

/* Hover effect for the links */
.l1 ul li a:hover {
    color: #00693e;  /* Green color on hover */
}

/* Change background color of menu items when hovering */
.l1 ul li:hover {
    background-color: #f0f0f0;  /* Light grey background on hover */
}

/* Style the profile icon */
.l1 .icon {
    width: 30px; /* Adjust size of the profile icon */
    height: 30px;
    cursor: pointer;
    transition: transform 0.3s ease; /* Smooth scale-up effect on hover */
}

/* Add hover effect to profile icon */
.l1 .icon:hover {
    transform: scale(1.1); /* Slightly increase the size of the icon when hovered */
}
/* Thay đổi màu chữ menu khi hover */
.nav-list a:hover {
    color: #FFD700; /* Màu vàng khi hover */
}

/* Thay đổi màu nền cho các nút khi hover */
.product-card .add-to-cart:hover {
    background-color: #FFD700; /* Màu vàng cho nút khi hover */
}

/* Thay đổi màu chữ của sản phẩm thành đen */
.product-card h3 {
    color: black; /* Màu đen cho tên sản phẩm */
}

/* Thay đổi màu chữ giá thành đen */
.product-card p {
    color: black; /* Màu đen cho giá sản phẩm */
}

/* Nút Đặt Mua */
.btn-order:hover {
    background-color: darkorange;
    color: black;  /* Màu vàng cho nút khi hover */
}

/* Màu nền cho nút Đặt Mua */
.btn-order {
    background-color: orange; /* Màu xanh lá cây cho nút Đặt Mua */
    color: white; /* Màu chữ trắng */
}
 form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"] {
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="header-left">
        <img src="../img/logo bee.png" height="50%" width="500px;" alt="Logo" class="logo">
    </div>
    <div class="header-right">
        <div class="icons">
        </div>
        <div class="user-greeting">
        </div>
        <div class="l1">
            <i class="icons">
            <a href="./control/index.php?chucnang=view_profile">
                <img src="../img/profile.png" alt="Profile Icon" class="icon">
            </a>
                <ul>
                    <?php if (isset($_SESSION['username'])) { ?>
                        <li><a href="./control/index.php?chucnang=view">Giỏ hàng</a></li>
                        <li><a href="./views/Invoice/donhang.php">Đơn hàng</a></li>
                        <li><a href="./views/Invoice/hoadon.php">Hóa đơn</a></li>
                        <li><a href="./control/index.php?chucnang=logout">Đăng xuất</a></li>
                    <?php } else { ?>
                        <li><a href="./control/index.php?chucnang=login">Đăng nhập</a></li>
                        <li><a href="./control/index.php?chucnang=dangki">Đăng ký</a></li>
                    <?php } ?>
                </ul>
            </i>
        </div>
        
    </div>
</header>

    <nav class="navbar">
        <ul class="nav-list">
            <li><a href="../index.php">Trang Chủ</a></li>
            <li class="dropdown">
                <a href="./views/menu/menu1.php " class="nav-link">Menu</a>  
                <div class="dropdown-content">
                    <div class="submenu">
                        <h4>THỨC UỐNG</h4>
                        <ul>
                            <li><a href="../views/menu/menu1.php">Trà Sữa</a></li>
                            <li><a href="../views/menu/menu2.php">Coffe</a></li>
                            <li><a href="../views/menu/menu3.php">Trà Hoa Quả Đặt Biệt</a></li>
                            <li><a href="../views/menu/menu4.php">OLong</a></li>
                            <li><a href="../views/menu/menu5.php">Sữa Tươi</a></li>
                            <li><a href="../views/menu/menu6.php">Trà Trái Cây</a></li>
                            <li><a href="../views/menu/menu7.php">Món Nóng</a></li>
                            <li><a href="../views/menu/menu8.php">Đá Xay</a></li>
                        </ul>
                    </div>
                   
                </div>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link">Sản Phẩm Đóng Gói</a>
                <div class="dropdown-content">
                    <div class="submenu">
                        <h4>TRÀ</h4>
                        <ul>
                            <li><a href="../views/menu/menu9.php">Lục Trà</a></li>
                            <li><a href="../views/menu/menu12.php">Trà OLong</a></li>
                        </ul>
                    </div>
                    <div class="submenu">
                        <h4>COFFEE</h4>
                        <ul>
                            <li><a href="../views/menu/menu10.php">Cà Phê Phin</a></li>
                            <li><a href="../views/menu/menu11.php">Cà Phê Hạt</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link">Về chúng tôi</a>
                <div class="dropdown-content">
                    <div class="submenu">
                        <ul>
                            <li>Giới thiệu về công ty</li>
                            <li>Thư viện hình ảnh</li>
                            <li>Liên hệ</li>
                        </ul>


            <li><a href="#">Hỗ Trợ</a></li>
        </ul>
    </nav>

       
    </style>
</head>
<body>
    <div class="container">
        <h1>Thông tin Hồ Sơ</h1>
        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        <?php if (isset($user)) { ?>
            <form method="POST" action="../control/index.php?chucnang=update_profile">
                <label for="username">Tên người dùng:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
                
                <label for="phone_number">Số điện thoại:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required><br>
                
                <label for="address">Địa chỉ:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br>
                
                <input type="submit" value="Cập nhật">
            </form>
        <?php } ?>
    </div>
</body>
</html>
