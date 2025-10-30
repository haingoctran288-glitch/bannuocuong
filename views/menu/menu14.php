<?php
include_once('../../model/config.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sau khi xác thực, lưu tên người dùng vào session
$user = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : null;
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Kiểm tra nếu người dùng đã đăng nhập
if ($user) {
    // Lấy user ID từ session
    $user_id = $_SESSION['user_id'];

    // Truy vấn dữ liệu giỏ hàng từ cơ sở dữ liệu nếu cần
    $cart_query = "SELECT c.cart_id, ci.cart_item_id, ci.product_id, ci.quantity, p.name_product, p.price, p.address
                FROM Cart c
                JOIN Cart_Item ci ON c.cart_id = ci.cart_id
                JOIN Product p ON ci.product_id = p.product_id
                WHERE c.user_id = ?";
    
    $stmt = $conn->prepare($cart_query);
    $stmt->bind_param("i", $user_id); // 'i' cho kiểu dữ liệu integer
    $stmt->execute();

    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
} else {
    $cart_items = $_SESSION['cart']; // Nếu chưa đăng nhập, sử dụng dữ liệu từ session
}

// Đếm số lượng sản phẩm trong giỏ hàng
$giohang_count = count($cart_items);

// Chạy truy vấn khác sau khi truy vấn giỏ hàng đã xong
$sql = 'SELECT * FROM Product WHERE category_id = 12';
$tacasanpham = mysqli_query($conn, $sql);

// Kiểm tra nếu có lỗi khi thực thi truy vấn
if (!$tacasanpham) {
    die("Lỗi truy vấn: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compact Header Section</title>
    <link>
    <link rel="stylesheet" href="menu.css">
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
    max-width: 2000px;
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
    background-color: pink;
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


body h1{
  background-color: #fafafa;
  margin: 0;
  padding: 0;
}


.about-container {
  max-width: 900px;
  margin: 50px auto;
  padding: 30px;
  font-family: "Segoe UI", sans-serif;
  line-height: 1.7;
  color: #333;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.about-container h1 {
  text-align: center;
  color: #00723f;
  font-size: 34px;
  text-transform: uppercase;
  margin-bottom: 25px;
}

.about-container p {
  text-align: justify;
  font-size: 17px;
  margin-bottom: 15px;
}

.about-container img {
  display: block;
  margin: 30px auto;
  width: 100%;
  max-width: 700px;
  border-radius: 10px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

.about-highlight {
  background-color: #f2f8f5;
  border-left: 5px solid #00723f;
  padding: 15px 20px;
  margin: 25px 0;
  font-style: italic;
  color: #444;
  border-radius: 5px;
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



    </style>
</head>
<body>
<header class="header">
    <div class="header-left">
        <img src="../../img/logo bee.png" height="50%" width="500px;" alt="Logo" class="logo">
        
    </div>
    
    <div class="header-right">
        <div class="icons">
            <a href="../../views/cart/cartview.php">
            <span class="cart-count">🛒 <?php echo $giohang_count; ?></span>
            </a>
        </div>
        <div class="user-greeting">
            <?php if ($user): ?>
                <b style="position:relative; vertical-align: middle; font-weight:400; margin-top: 40px;">Xin chào - <?php echo $user; ?></b>
            <?php endif; ?>
        </div>
        
        <div class="l1">
        <i class="icons">
            <a href="/ChuyenDeThucTap/control/index.php?chucnang=view_profile">
            <img src="../../img/profile.png" alt="Profile Icon" class="icon">
                <ul>
                    <?php if (isset($_SESSION['username'])) { ?>
                        <li><a href="../../views/Invoice/donhang.php">Đơn hàng</a></li>
                        <li><a href="../../views/Invoice/hoadon.php">Hóa đơn</a></li>
                        <li><a href="../../control/index.php?chucnang=logout">Đăng xuất</a></li>
                    <?php } else { ?>
                        <li><a href="../../control/index.php?chucnang=login">Đăng nhập</a></li>
                        <li><a href="../../control/index.php?chucnang=dangki">Đăng ký</a></li>
                    <?php } ?>
                </ul>
            </i>
        </div>
        
    </div>
</header>

    <nav class="navbar">
        <ul class="nav-list">
             <li><a href="../../index.php">Trang Chủ</a></li>
            <li class="dropdown">
                <a href="#" class="nav-link">Menu</a>  
                <div class="dropdown-content">
                    <div class="submenu">
                        <h4>THỨC UỐNG</h4>
                        <ul>
                            <li><a href="menu1.php">Trà Sữa</a></li>
                            <li><a href="menu2.php">Coffe</a></li>
                            <li><a href="menu3.php">Trà Hoa Quả Đặt Biệt</a></li>
                            <li><a href="menu4.php">OLong</a></li>
                            <li><a href="menu5.php">Sữa Tươi</a></li>
                            <li><a href="menu6.php">Trà Trái Cây</a></li>
                            <li><a href="menu7.php">Món Nóng</a></li>
                            <li><a href="menu8.php">Đá Xay</a></li>
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
                            <li><a href="menu9.php">Lục Trà</a></li>
                            <li><a href="menu12.php">Trà OLong</a></li>
                        </ul>
                    </div>
                    <div class="submenu">
                        <h4>COFFEE</h4>
                        <ul>
                            <li><a href="menu10.php">Cà Phê Phin</a></li>
                            <li><a href="menu11.php">Cà Phê Hạt</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link">Về chúng tôi</a>
                <div class="dropdown-content">
                    <div class="submenu">
                        <ul>
                        <li><a href="menu13.php">Cà Phê </a></li>
                        <li><a href="menu14.php">Trà</a></li>
                        <li><a href="menu15.php">Về chúng tôi</a></li>
                        </ul>


            <li><a href="menu16.php">Hỗ Trợ</a></li>
        </ul>
    </nav>
   
    <div class="about-container">
  <h1>HÀNH TRÌNH TÁCH TRÀ</h1>
<p>Xuyên suốt hơn nửa thế kỷ qua, từ những mầm trà chúng tôi tạo ra niềm đam mê, với sự nỗ lực không ngừng nghỉ trong từng ngày, Phúc Long đã có thể mang đến cho bạn tách trà đậm vị. Trên hành trình tôn trọng bản sắc trà, Phúc Long đảm bảo tận dụng hết những tính năng của trà để có thể hoà quyện cả hương lẫn vị. Những lá trà thượng hạng sau khi thu hoạch được xử lý sạch, làm héo, phơi/ sấy khô, cho vào máy cắt, vò và nghiền. Tiếp đến, trà được sàng lọc, cho đến khi đủ kích thước tiêu chuẩn để lên men. Sau quá trình lên men, trà được đưa đi sấy khô lần nữa, ướp hương và đóng gói.

</p>

<h2>1. Nguồn gốc tinh khiết quyết định hương vị tươi nguyên
</h2>
<p>Trà Phúc Long có nguồn gốc tinh khiết từ hai đồi chè do chúng tôi sở hữu tại Thái Nguyên và Bảo Lộc – hai nơi này cũng được xem là lãnh thổ trà trứ danh của Việt Nam. Sở dĩ Trà Phúc Long mang đặc tính thượng hạng từ hương thơm, mùi vị đến độ đậm là do trong quá trình vun trồng chúng tôi đặc biệt chú trọng hai yếu tố: điều kiện thiên nhiên và thổ nhưỡng. Bên cạnh đó, độ tươi nguyên là yếu tố quan trọng để đánh giá chất lượng trà. Chính vì vậy, để giữ gìn độ tươi nguyên, chúng tôi đã xây dựng nhà máy tại hai đồi chè, đảm bảo trà được sấy khô ngay sau khi thu hoạch. 

</p>
  <div class="about-content">
    <div class="about-image">
      <img src="../../img/tra.jpeg" alt="Bee Coffee & Tea">
    </div>


    <h2>2. Tôn trọng nguyên bản cho tách trà đậm vị
</h2>
<p>Tiên phong trong việc cung cấp trà tươi đóng gói tại nơi thu hoạch đến tận tay người thưởng thức, Phúc Long đã và đang không ngừng tìm kiếm những giống trà hảo hạng, không ngừng tìm tòi - cải tiến phương pháp sản xuất để cho ra đời sản phẩm thuần vị. Xuyên suốt quá trình tôn trọng nguyên bản cho tách trà đậm vị hơn nửa thế kỷ qua, chúng tôi hân hoan nhận ra rằng: bảo tồn di sản trà cũng chính là bảo tồn văn hoá Việt. </p>
<h2>3. Đậm vị trà truyền thống
</h2>
<p>Nuôi dưỡng sự tự nhiên trong trà, Phúc Long chú trọng sản xuất bằng phương pháp thủ công truyền thống. Tuy rằng phương pháp này luôn đòi hỏi trình độ cao và sự hoàn hảo ở tất cả các khâu, không được có bất kỳ sai sót, từ giai đoạn khởi đầu tuyển chọn nghiêm ngặt hái một búp và hai lá non đến giai đoạn đóng gói thành phẩm, nhưng để giữ gìn đặc tính thượng hạng của trà, chúng tôi sẵn sàng ưu tiên lựa chọn.</p>
<div class="about-content">
    <div class="about-image">
      <img src="../../img/coc1.jpeg" alt="Bee Coffee & Tea">
    </div>
<p>Một cây trà nếu được trồng ở những vùng đất có độ cao và khí hậu khác nhau thì sẽ thu được những loại trà cũng khác nhau. Có thể thấy sự phức tạp đến từ phía bên trong, từ những thành phần cũng như cấu tạo hoá học độc nhất vô nhị mà chỉ mình cây trà có được. Thấu hiểu điều đó, để giữ trọn vị tươi nguyên, bảo toàn dưỡng chất tốt nhất, một búp và hai lá non thường được chúng tôi thu hái vào thời điểm sáng sớm. Tiếp đến, quy trình sản xuất để cho ra các sản phẩm trà chất lượng cũng được thực hiện khép kín.

</p>


<p>Trong quá trình tìm kiếm từng loại trà thượng hạng, Phúc Long luôn giữ gìn những hợp chất đặc biệt từ lá trà để làm nên tách trà đậm vị.
<br>- Theanine (vị ngon) là cảm nhận được trạng thái tỉnh táo, tràn đầy năng lượng khi thưởng thức trà.
<br>-Carbohydrate (vị ngọt) là đường tích trữ trong lá trà.
<br>- Polyphenols (vị chát) là thành phần đặc biệt có nhiều trong lá trà non.
<br>- Caffein (vị đắng) là thành phần bị ảnh hưởng bởi 2 yếu tố: nhiệt độ nước và cách ngâm. Để tiết chế caffein, khi pha nên dùng nước nhiệt độ vừa phải và giảm thời gian ngâm trà.
<br>- Enzyme (men) là chất xúc tác sinh học thúc đẩy quá trình lên men của lá trà

</p>

    </div>
  </div>
</div>


    <!-- Footter -->
    <footer style="background-color: #007a2a; color: white; padding: 20px; font-size: 15px; line-height: 1.6;">
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
          <!-- Phần địa chỉ -->
          <div style="flex: 1 1 300px; min-width: 300px;">
            <h4 style="font-size: 17px; margin-bottom: 10px;">ĐỊA CHỈ</h4>
            <p style="margin-bottom: 10px;">
              Trụ sở chính: Công ty Cổ Phần Phúc Long Heritage - ĐKKD: 0316 871719 do sở KHĐT TPHCM cấp lần đầu ngày 21/05/2021<br>
              Nhà máy: D_8D_CN Đường XE 1, Khu Công Nghiệp Mỹ Phước III, phường Mỹ Phước, thị xã Bến Cát, tỉnh Bình Dương, Việt Nam.<br>
              Địa chỉ: Phòng 702, Tầng 7, Tòa nhà Central Plaza, số 17 Lê Duẩn, phường Bến Nghé, quận 1, Hồ Chí Minh.
            </p>
            <p style="margin-bottom: 10px;">
              Hotline Đặt hàng: <b>1800 6779</b><br>
              Hotline Công ty: <b>1900 2345 18</b> (Bấm phím 0: Lễ Tân | phím 1: CSKH)<br>
              Email: <a href="mailto:sales@masangroup.com" style="color: white;">sales@phuclong.masangroup.com</a>, <a href="mailto:info2@phuclong.masangroup.com" style="color: white;">info2@phuclong.masangroup.com</a>
            </p>
          </div>
      
          <!-- Các danh mục -->
          <div style="flex: 2 1 600px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
            <!-- Cột 1 -->
            <div>
              <h4 style="font-size: 17px; margin-bottom: 8px;">CÔNG TY</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 8px;"><a href="/gioi-thieu" style="color: white; text-decoration: none;">Giới thiệu công ty</a></li>
                <li style="margin-bottom: 8px;"><a href="/thu-vien-hinh-anh" style="color: white; text-decoration: none;">Thư viện hình ảnh</a></li>
                <li style="margin-bottom: 8px;"><a href="/lien-he" style="color: white; text-decoration: none;">Liên hệ</a></li>
              </ul>
            </div>
      
            <div>
              <h4 style="font-size: 17px; margin-bottom: 8px;">TUYỂN DỤNG</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 8px;"><a href="/tuyen-dung/htch" style="color: white; text-decoration: none;">HTCH</a></li>
                <li style="margin-bottom: 8px;"><a href="/tuyen-dung/kiosk" style="color: white; text-decoration: none;">Kiosk</a></li>
                <li style="margin-bottom: 8px;"><a href="/tuyen-dung/van-phong" style="color: white; text-decoration: none;">Văn phòng</a></li>
                <li style="margin-bottom: 8px;"><a href="/tuyen-dung/nha-may" style="color: white; text-decoration: none;">Nhà máy</a></li>
              </ul>
            </div>
      
            <div>
              <h4 style="font-size: 17px; margin-bottom: 8px;">KHUYẾN MÃI</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
    <li style="margin-bottom: 8px;"><a href="/khuyen-mai" style="color: white; text-decoration: none;">Tin khuyến mãi</a></li>
              </ul>
            </div>
      
            <!-- Cột 2 -->
            <div>
              <h4 style="font-size: 17px; margin-bottom: 8px;">CỬA HÀNG</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 8px;"><a href="/cua-hang" style="color: white; text-decoration: none;">Danh sách cửa hàng</a></li>
              </ul>
            </div>
      
            <div>
              <h4 style="font-size: 17px; margin-bottom: 8px;">HỘI VIÊN</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 8px;"><a href="/hoi-vien/faq" style="color: white; text-decoration: none;">Câu hỏi thường gặp (FAQ)</a></li>
                <li style="margin-bottom: 8px;"><a href="/hoi-vien/dieu-khoan-chuong-trinh" style="color: white; text-decoration: none;">Điều khoản và điều kiện chương trình hội viên</a></li>
                <li style="margin-bottom: 8px;"><a href="/hoi-vien/dieu-khoan-the-tra-truoc" style="color: white; text-decoration: none;">Điều khoản & Điều kiện Thẻ trả trước</a></li>
              </ul>
            </div>
      
            <div>
              <h4 style="font-size: 17px; margin-bottom: 8px;">ĐIỀU KHOẢN SỬ DỤNG</h4>
              <ul style="list-style: none; padding: 0; margin: 0;">
                <li style="margin-bottom: 8px;"><a href="/dieu-khoan/chinh-sach-bao-mat" style="color: white; text-decoration: none;">Chính sách bảo mật thông tin</a></li>
                <li style="margin-bottom: 8px;"><a href="/dieu-khoan/chinh-sach-dat-hang" style="color: white; text-decoration: none;">Chính sách đặt hàng</a></li>
              </ul>
            </div>
          </div>
        </div>
      
       <!-- Phần cuối -->
    <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; font-size: 16px;">
        <p>© Công ty CP Phúc Long Heritage 2024</p>
        <div>
          <img src="http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png" alt="Đã thông báo Bộ Công Thương" style="height: 40px; margin-right: 15px;">
          <a href="https://www.instagram.com" target="_blank" title="Facebook">
          <img src="../../img/inta.png" alt="Instagram" style="height: 30px;">
  </a>
  <a href="https://www.facebook.com" target="_blank" title="Facebook">
    <img src="../../img/face.png" alt="Facebook" style="height: 30px; margin: 0 15px;">
  </a>
  <a href="https://www.youtube.com" target="_blank" title="YouTube">
    <img src="../../img/youtube.png" alt="YouTube" style="height: 30px;">
        </div>
      </div>
      </footer>
    
</body>
</html>