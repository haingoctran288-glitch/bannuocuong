<?php
require_once './model/config.php'; // Đường dẫn đến file config


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
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

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
$sql = 'SELECT * FROM Product limit 5' ;
$tacasanpham = mysqli_query($conn, $sql);

// Đừng quên đóng kết nối khi đã xong
$conn->close();

?>

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
.pl-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .pl-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .pl-header h1 {
        color: #006341;
        font-size: 32px;
        margin-bottom: 8px;
    }

    .pl-header p {
        color: #666;
        font-style: italic;
    }

    .pl-promo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
    }

    .pl-promo-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }

    .pl-promo-card:hover {
        transform: translateY(-4px);
    }

    .pl-promo-image {
        width: 100%;
        padding-top: 66.67%;
        position: relative;
        overflow: hidden;
    }

    .pl-promo-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pl-promo-details {
        padding: 16px;
    }

    .pl-view-count {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        color: #666;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .pl-view-count i {
        font-size: 16px;
    }

    .pl-divider {
        height: 1px;
        background-color: #eee;
        margin: 12px 0;
    }

    .pl-promo-title {
        color: #333;
        font-size: 16px;
        font-weight: 500;
        line-height: 1.4;
        margin: 0;
        position: relative;
        padding-left: 16px;
    }

    .pl-promo-title::before {
        content: "-";
        position: absolute;
        left: 0;
        top: 0;
    }

    @media (max-width: 768px) {
        .pl-promo-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .pl-header h1 {
            font-size: 24px;
        }
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
    background-color: #e4507fff;
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

.search-form {
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 25px;
    padding: 5px 10px;
    margin-left: 20px;
    height: 35px;
}

.search-form input {
    border: none;
    outline: none;
    padding: 5px 10px;
    width: 160px;
    font-size: 14px;
}

.search-form button {
    border: none;
    background: none;
    cursor: pointer;
    color: #f5a623;
    font-size: 16px;
}











/* --- Lớp phủ nền --- */
.popup-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.6);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.4s ease;
  font-family: "Poppins", sans-serif;
}

/* --- Hộp nội dung chính --- */
.popup-content {
  background: #ffffff;
  width: 520px;
  border-radius: 26px;
  padding: 28px 35px;
  position: relative;
  box-shadow: 0 10px 40px rgba(0, 126, 70, 0.25);
  animation: slideUp 0.4s ease;
}

/* --- Nút đóng --- */
.close-btn {
  position: absolute;
  right: 18px;
  top: 12px;
  background: none;
  border: none;
  font-size: 28px;
  cursor: pointer;
  color: #0a6b3e;
  transition: .2s;
}
.close-btn:hover { color: #008f4c; transform: rotate(90deg); }

/* --- Thanh tab lựa chọn --- */
.tab-container {
  display: flex;
  background: #ffffffff;  
  padding: 5px;
  margin-bottom: 20px;
  border-radius: 50px;
  gap: 5px;
}

.tab {
  flex: 1;
  padding: 12px 0;
  cursor: pointer;
  border: none;
  border-radius: 50px;
  background: transparent;
  font-weight: 600;
  font-size: 15px;
  color: #0c5a32; 
  transition: .3s;
}
.tab i { margin-right: 6px; }
.tab.active {
  background: linear-gradient(135deg, #FFD700, #FFB100);
  color: #fff;
  box-shadow: 0 4px 12px rgba(255, 183, 0, .6);
}
/* --- Nội dung Tab --- */
.tab-content {
    display: none;
    background: #ffffff;     /* ✅ đổi nền xám → trắng */
    padding: 16px 18px;      /* ✅ tránh dính sát viền */
    border-radius: 16px;     /* ✅ bo góc giao diện đẹp hơn */
}

.tab-content.active { display: block; }

/* --- Ô nhập địa chỉ --- */
.form-control {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #78dba3; /* ✅ đổi border */
  border-radius: 10px;
  font-size: 15px;
  margin-bottom: 18px;
  background: #d8f8e1; /* ✅ đổi nền */
  color: #024c26; /* ✅ text xanh đậm */
  transition: .3s;
}
.form-control:focus {
  border-color: #009f54;
  box-shadow: 0 0 8px rgba(0, 159, 84, 0.4);
}

/* --- Danh sách cửa hàng --- */
.store-item {
  display: flex;
  padding: 14px 0;
  align-items: flex-start;
  gap: 12px;
  background: #d8f8e1; /* ✅ xanh nhạt */
  border-radius: 12px;
  transition: .3s;
  border-bottom: none;
}
.store-item:hover {
  background: #c6f3d6;
  transform: translateY(-2px);
}

.store-img {
  width: 55px;
  height: 55px;
  border-radius: 10px;
  object-fit: cover;
  background: #b7e6c6;
}

.store-info {
  flex: 1;
  font-size: 14px;
  line-height: 1.4;
  color: #064826;
}
.store-info strong {
  font-size: 15px;
  display: block;
  margin-bottom: 4px;
  color: #05361d;
}

.store-actions .btn {
  font-size: 15px;
  padding: 10px 20px;
}

/* --- Nút chung giữ màu vàng --- */
.btn {
  background: linear-gradient(135deg, #FFC800, #FFAD00);
  color: #fff;
  font-weight: bold;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  letter-spacing: .5px;
  transition: .3s;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}
.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 14px rgba(255, 183, 0, 0.4);
}

/* --- Hiệu ứng --- */
@keyframes fadeIn {
  from { opacity: 0; } to { opacity: 1; }
}
@keyframes slideUp {
  from { transform: translateY(25px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}









/* --- Nút tròn nhỏ góc phải --- */
.chat-toggle {
    position: fixed;
    right: 20px;
    bottom: 20px;
    background: #0b74de;
    color: white;
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    transition: transform 0.2s;
    z-index: 9999;
  }
  .chat-toggle:hover {
    transform: scale(1.1);
  }
  
  /* --- Hộp chat --- */
  .chat-widget {
    position: fixed;
    right: 20px;
    bottom: 85px; /* nằm trên nút tròn */
    width: 320px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    font-family: Arial, sans-serif;
    display: none; /* ẩn mặc định */
    background: #fff;
    z-index: 9998;
  }
  
  .chat-header {
    background: #0b74de;
    color: white;
    padding: 10px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  
  .close-chat {
    cursor: pointer;
    font-size: 20px;
  }
  
  .chat-body {
    height: 340px;
    overflow-y: auto;
    padding: 10px;
    background: #fff;
  }
  
  .chat-footer {
    display: flex;
    border-top: 1px solid #ddd;
    background: #f7f7f7;
    padding: 8px;
  }
  
  .chat-footer input {
    flex: 1;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 6px;
  }
  
  .chat-footer button {
    background: #0b74de;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 6px 10px;
    margin-left: 6px;
    cursor: pointer;
  }
  
  .msg { margin-bottom: 10px; }
  .bubble {
    display: inline-block;
    padding: 8px 12px;
    border-radius: 12px;
    max-width: 80%;
    word-wrap: break-word;
  }
  .msg.user { text-align: right; }
  .bubble.user { background: #DCF8C6; }
  .bubble.bot { background: #eee; }

  

    </style>




















</head>
<body>
<header class="header">
    <div class="header-left">
        <img src="./img/logo bee.png" height="50%" width="500px;" alt="Logo" class="logo">
        
    </div>

    
    
    <div class="header-right">
        <div class="icons">
            <a href="./views/cart/cartview.php">
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
            <a href="./control/index.php?chucnang=view_profile">
                <img src="./img/profile.png" alt="Profile Icon" class="icon">
            </a>
                <ul>
                    <?php if (isset($_SESSION['username'])) { ?>
                        
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
            <li><a href="#">Trang Chủ</a></li>
            <li class="dropdown">
                <a href="./views/menu/menu1.php " class="nav-link">Menu</a>  
                <div class="dropdown-content">
                    <div class="submenu">
                        <h4>THỨC UỐNG</h4>
                        <ul>
                            <li><a href="./views/menu/menu1.php">Trà Sữa</a></li>
                            <li><a href="./views/menu/menu2.php">Coffe</a></li>
                            <li><a href="./views/menu/menu3.php">Trà Hoa Quả Đặt Biệt</a></li>
                            <li><a href="./views/menu/menu4.php">OLong</a></li>
                            <li><a href="./views/menu/menu5.php">Sữa Tươi</a></li>
                            <li><a href="./views/menu/menu6.php">Trà Trái Cây</a></li>
                            <li><a href="./views/menu/menu7.php">Món Nóng</a></li>
                            <li><a href="./views/menu/menu8.php">Đá Xay</a></li>
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
                            <li><a href="./views/menu/menu9.php">Lục Trà</a></li>
                            <li><a href="./views/menu/menu12.php">Trà OLong</a></li>
                        </ul>
                    </div>
                    <div class="submenu">
                        <h4>COFFEE</h4>
                        <ul>
                            <li><a href="./views/menu/menu10.php">Cà Phê Phin</a></li>
                            <li><a href="./views/menu/menu11.php">Cà Phê Hạt</a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" class="nav-link">Về chúng tôi</a>
                <div class="dropdown-content">
                    <div class="submenu">
                        <ul>
                        <li><a href="./views/menu/menu13.php">Cà Phê </a></li>
                        <li><a href="./views/menu/menu14.php">Trà</a></li>
                        <li><a href="./views/menu/menu15.php">Về chúng tôi</a></li>
                        </ul>


            <li><a href="./views/menu/menu16.php">Hỗ Trợ</a></li>

    <form class="search-form" action="search.php" method="GET">
        <input type="text" name="keyword" placeholder="Tìm sản phẩm..." required>
        <button type="submit"><i class="fa fa-search"></i></button>
    </form> 





















<!-- Nút mở popup -->
<button id="btnPhuongThuc" class="btn btn-success">Chọn Phương Thức Nhận Hàng</button>

<!-- Popup -->
<div id="popupPhuongThuc" class="popup-overlay" style="display:none;">
  <div class="popup-content">
    <button id="closePopup" class="close-btn">&times;</button>

    <div class="tab-container">
      <button class="tab active" data-tab="giaohang">GIAO HÀNG</button>
      <button class="tab" data-tab="denlay">ĐẾN LẤY</button>
    </div>

    <!-- TAB: GIAO HÀNG -->
    <div id="giaohang" class="tab-content active">
      <h4>Nhập địa chỉ giao hàng của bạn</h4>
      <input type="text" id="deliveryAddress" class="form-control" placeholder="">
      <button id="btnXacNhanGiaoHang" class="btn btn-primary">🚚 Xác nhận</button>

      <p id="mapMessage" style="margin-top: 10px; font-weight: bold; color: green;"></p>
    </div>

    <!-- TAB: ĐẾN LẤY -->
    <div id="denlay" class="tab-content">
      <input type="text" id="searchStore" class="form-control" placeholder="Tìm cửa hàng theo địa điểm, tên...">

      <div id="storeList" class="store-list">
        <!-- Cửa hàng 1 -->
        <div class="store-item">
          <div class="store-info">
            <h4>BDG-CH 44 Nguyễn Đình Chiểu P.PC</h4>
            <p><strong>Địa chỉ:</strong> 44 Nguyễn Đình Chiểu, Phú Cường, Thủ Dầu Một, Bình Dương</p>
            <p><strong>Số điện thoại:</strong> (028) 7100 1968 (Ext.20028)</p>
            <p><strong>Giờ hoạt động:</strong> 07:00 - 22:30</p>
            <p><strong>Trạng thái:</strong> Mở cửa</p>
          </div>
          <div class="store-actions">
            <a href="https://www.google.com/maps/dir/?api=1&destination=44+Nguyen+Dinh+Chieu,+Phu+Cuong,+Thu+Dau+Mot,+Binh+Duong"
               target="_blank" class="btn btn-success">🧭 Chỉ đường</a>
          </div>
        </div>

        <!-- Cửa hàng 2 -->
        <div class="store-item">
          <div class="store-info">
            <h4>CTO-CH Vincom Hùng Vương Số 2 HV</h4>
            <p><strong>Địa chỉ:</strong> Vincom Hùng Vương, P. Thới Bình, Q. Ninh Kiều, TP. Cần Thơ</p>
            <p><strong>Số điện thoại:</strong> (029) 2384 4444</p>
            <p><strong>Giờ hoạt động:</strong> 07:30 - 22:00</p>
            <p><strong>Trạng thái:</strong> Mở cửa</p>
          </div>
          <div class="store-actions">
            <a href="https://www.google.com/maps/dir/?api=1&destination=Vincom+Hung+Vuong,+Ninh+Kieu,+Can+Tho"
               target="_blank" class="btn btn-success">🧭 Chỉ đường</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>





<head>
<meta charset="UTF-8">
<title>Chatbox AI - Web bán hàng</title>
<link rel="stylesheet" href="../chat.css">
</head>
<body>

<!-- Nút tròn bật/tắt chat -->
<div class="chat-toggle" id="chatToggle">
  💬
</div>

<!-- Hộp chat (ẩn mặc định) -->
<div class="chat-widget" id="chatWidget">
  <div class="chat-header">
    Hỗ trợ trực tuyến
    <span class="close-chat" id="closeChat">&times;</span>
  </div>
  <div class="chat-body" id="chatBody">
    <div class="msg bot">
      <div class="bubble bot">Xin chào 👋! Tôi có thể giúp gì cho bạn?</div>
    </div>
  </div>
  <div class="chat-footer">
    <input id="chatInput" type="text" placeholder="Nhập tin nhắn..." />
    <button id="sendBtn">Gửi</button>
  </div>
</div>

<script src="chat.js"></script>
</body>









        </ul>
    </nav>
<div class="slideshow-container">
    <div class="mySlides slide-left">
        <img src="./img/baner3.jpg" style="width:100%; height:500px;">
    </div>
    
    <div class="mySlides slide-left">
        <img src="img/BANNER-V1.5.jpg" style="width:100%; height:500px;">
    </div>
    
    <div class="mySlides slide-left">
        <img src="./img/baner2.jpg" style="width:100%; height:500px;">
    </div>
</div>

<br>

<div style="text-align:center">
    <span class="dot" onclick="currentSlide(1)"></span> 
    <span class="dot" onclick="currentSlide(2)"></span> 
    <span class="dot" onclick="currentSlide(3)"></span> 
</div>
<section class="customer-service-section">
    <div class="container">
        <div class="service-item">
            <img src="./img/customer1.png" alt="Customer Service Icon" class="service-icon">
            <div class="service-text">
                <p>CHĂM SÓC KHÁCH HÀNG</p>
                <p>0962 455 517</p>
            </div>
        </div>
        <div class="service-item">
            <img src="./img/customer2.png" alt="Delivery Icon" class="service-icon">
            <div class="service-text">
                <p>GIAO HÀNG</p>
                <p>Giao hàng nhanh chóng</p>
            </div>
        </div>
        <div class="service-item">
            <img src="./img/customer3.png" alt="Hotline Icon" class="service-icon">
            <div class="service-text">
                <p>LIÊN HỆ HOTLINE</p>
                <p>19008021 (Miễn phí)</p>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <!-- Best Seller Section -->
    <h2>TRÀ NỔI BẬT</h2>
    <div class="product-list">
    <?php while ($product = mysqli_fetch_assoc($tacasanpham)) { ?>
    <div class="product-card">
        <div class="item">
        <a href="./views/chitietsanpham/chitiet.php?id=<?php echo $product['product_id']; ?>">
            <img src="./control/<?php echo $product['address']; ?>" style="margin-left: 18px;">
        </a>

        </div>
        <div class="description">
            <h3><?php echo ($product['name_product']); ?></h3>
            <p><?php echo ($product['price']); ?> ₫</p>
            <a href="./control/index.php?chucnang=add&product_id=<?php echo $product['product_id']; ?>"><button class="btn-order">🛒 Đặt mua</button></a>
        </div>
    </div>
<?php } ?>

</div>  
 </div>




















        
 <div class="pl-container">
  <div class="pl-header">
      <h1>Tin tức & Khuyến mãi</h1>
      <p>Tin tức & Khuyến mãi</p>
  </div>

  <div class="pl-promo-grid">

      <!-- Bài viết 1 -->
      <a href="khuyenmai_detail.php?id=1" class="pl-promo-card" style="text-decoration:none; color:inherit;">
          <div class="pl-promo-image">
              <img src="uploadFiles/ChatGPT Image 12_06_13 7 thg 11, 2025.png" alt="TÀI KHOẢN MỚI ĐƯỢC GIẢM 20%">
          </div>
          <div class="pl-promo-details">
              <div class="pl-view-count">
                  <i>👁</i> 112159
              </div>
              <div class="pl-divider"></div>
              <h3 class="pl-promo-title">🆕 TÀI KHOẢN MỚI ĐƯỢC GIẢM 20% CHO ĐƠN HÀNG ĐẦU TIÊN 🤑</h3>
          </div>
      </a>

      <!-- Bài viết 2 -->
      <a href="khuyenmai_detail.php?id=2" class="pl-promo-card" style="text-decoration:none; color:inherit;">
          <div class="pl-promo-image">
              <img src="uploadFiles/14902476-bb20-4d3d-bdb1-a55382ea4299.png" alt="ƯU ĐÃI TƯNG BỪNG GIẢM ĐẾN 10%">
          </div>
          <div class="pl-promo-details">
              <div class="pl-view-count">
                  <i>👁</i> 65788
              </div>
              <div class="pl-divider"></div>
              <h3 class="pl-promo-title">🔥 ƯU ĐÃI TƯNG BỪNG GIẢM ĐẾN 10% CHO ĐƠN HÀNG TỪ 149K TRỞ LÊN 💥</h3>
          </div>
      </a>

      <!-- Bài viết 3 -->
      <a href="khuyenmai_detail.php?id=3" class="pl-promo-card" style="text-decoration:none; color:inherit;">
          <div class="pl-promo-image">
              <img src="uploadFiles/a819da63-8665-4ed8-888a-67202538f1e1.png" alt="GIÁNG SINH">
          </div>
          <div class="pl-promo-details">
              <div class="pl-view-count">
                  <i>👁</i> 20271
              </div>
              <div class="pl-divider"></div>
              <h3 class="pl-promo-title">🎄 TẬN HƯỞNG GIÁNG SINH CÙNG BEE TEA AND COFFEE 🎅</h3>
          </div>
      </a>

      <!-- Bài viết 4 -->
      <a href="khuyenmai_detail.php?id=4" class="pl-promo-card" style="text-decoration:none; color:inherit;">
          <div class="pl-promo-image">
              <img src="uploadFiles/aa0d8659-1086-4e08-8765-9d595d9963d1.png" alt="HALLOWEEN">
          </div>
          <div class="pl-promo-details">
              <div class="pl-view-count">
                  <i>👁</i> 31742
              </div>
              <div class="pl-divider"></div>
              <h3 class="pl-promo-title">🎃 HAPPY HALLOWEEN CÙNG BEE TEA AND COFFEE NÀO 🕷️</h3>
          </div>
      </a>

  </div>
</div>






















































    <?php
// đọc dữ liệu JSON
$stores = json_decode(file_get_contents('stores.json'), true);
?>

<head>
  <meta charset="utf-8">
  <title>Danh sách cửa hàng</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Leaflet CDN -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <style>
    body { margin:0; font-family: Arial, sans-serif; }
    header { text-align:center; padding:16px; background:#fff; border-bottom:1px solid #eee; }
    header h2 { color:#0a8a4a; margin:0; }
    .container { display:flex; flex-wrap:wrap; max-width:1200px; margin:0 auto; padding:16px; gap:16px; }
    #map { flex:1 1 55%; height:700px; border-radius:6px; }
    .list { flex:1 1 40%; border-left:1px solid #eee; padding-left:16px; max-height:700px; overflow-y:auto; }
    .store { padding:10px 0; border-bottom:1px solid #f0f0f0; cursor:pointer; }
    .store h3 { margin:0; color:#0a8a4a; font-size:16px; }
    .store p { margin:4px 0; color:#555; font-size:14px; }
    .btn-route { display:inline-block; margin-top:6px; padding:6px 10px; background:#0a8a4a; color:white; border-radius:4px; text-decoration:none; font-size:13px; }
    .search-box { margin-bottom:12px; }
    .search-box input { width:80%; padding:8px; border:1px solid #ccc; border-radius:4px; }
    .gps-btn { padding:8px; border:1px solid #ccc; border-radius:4px; cursor:pointer; }
  </style>
</head>
<body>

<header>
  <h2>Danh sách cửa hàng </h2>
  <p style="color:#666">Tìm kiếm cửa hàng gần bạn</p>
</header>

<div class="container">
  <div id="map"></div>
  <div class="list">
    <div class="search-box">
      <input type="text" id="search" placeholder="Tìm cửa hàng hoặc địa chỉ...">
      <button class="gps-btn" id="gpsBtn">📍</button>
    </div>

    <div id="storeList">
      <?php foreach ($stores as $s): ?>
        <div class="store" data-lat="<?= $s['lat'] ?>" data-lng="<?= $s['lng'] ?>">
          <h3><?= htmlspecialchars($s['name']) ?></h3>
          <p><?= htmlspecialchars($s['address']) ?></p>
          <p>📞 <?= htmlspecialchars($s['phone']) ?></p>
          <p>🕒 <?= $s['open_time'] ?> - <?= $s['close_time'] ?></p>
          <a class="btn-route" target="_blank"
             href="https://www.google.com/maps/dir/?api=1&destination=<?= $s['lat'] ?>,<?= $s['lng'] ?>">Chỉ đường</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
  // khởi tạo bản đồ
  var map = L.map('map').setView([10.7769, 106.7009], 12);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  // load marker từ PHP
  var stores = <?php echo json_encode($stores); ?>;
  var markers = [];

  stores.forEach(function(s) {
    var m = L.marker([s.lat, s.lng]).addTo(map)
      .bindPopup('<b>'+s.name+'</b><br>'+s.address+'<br>📞 '+s.phone);
    markers.push(m);
  });

  // click vào danh sách → zoom marker
  document.querySelectorAll('.store').forEach((el, idx) => {
    el.addEventListener('click', () => {
      let lat = parseFloat(el.dataset.lat), lng = parseFloat(el.dataset.lng);
      map.setView([lat, lng], 15);
      markers[idx].openPopup();
    });
  });

  // tìm kiếm
  document.getElementById('search').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.store').forEach(el => {
      var text = el.innerText.toLowerCase();
      el.style.display = text.includes(q) ? 'block' : 'none';
    });
  });

  // định vị người dùng
  document.getElementById('gpsBtn').addEventListener('click', () => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(pos => {
        var lat = pos.coords.latitude, lng = pos.coords.longitude;
        L.circle([lat, lng], {radius: 50}).addTo(map);
        map.setView([lat, lng], 13);
      }, err => alert('Không thể lấy vị trí: ' + err.message));
    } else {
      alert('Trình duyệt không hỗ trợ định vị.');
    }
  });
</script>

</body>






<section class="partners-section">
    <div class="container">
        <h2>Đối tác đồng hành</h2>
        <p>Những đơn vị uy tín mà chúng tôi đang hợp tác chung</p>
        <div class="partners">
            <div class="partner">
                <img src="./img//partners1.png" alt="Grab Food">
            </div>
            <div class="partner">
                <img src="./img//partners2.png" alt="Shopee Food">
            </div>
            <div class="partner">
                <img src="./img//partners3.jpg" alt="Gojek">
            </div>
            <div class="partner">
                <img src="./img//partners4.jpg" alt="Be">
            </div>
        </div>
    </div>
</section>







 <footer style=" padding: 20px; font-size: 15px; line-height: 1.6;">
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
          Email: <a href="mailto:sales@phuclong.masangroup.com" style="color: white;">sales@phuclong.masangroup.com</a>, <a href="mailto:info2@phuclong.masangroup.com" style="color: white;">info2@phuclong.masangroup.com</a>
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
    <p>© web dự án 1</p>
    <div>
      <img src="http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png" alt="Đã thông báo Bộ Công Thương" style="height: 40px; margin-right: 15px;">
<a href="https://www.instagram.com" target="_blank" title="Instagram">
    <img src="./img/inta.png" alt="Instagram" style="height: 30px;">
  </a>
  <a href="https://www.facebook.com" target="_blank" title="Facebook">
    <img src="./img/face.png" alt="Facebook" style="height: 30px; margin: 0 15px;">
  </a>
  <a href="https://www.youtube.com" target="_blank" title="YouTube">
    <img src="./img/youtube.png" alt="YouTube" style="height: 30px;">  
    </a>
</div>
  </footer>
  
  

  
  <script>
const btn = document.getElementById('btnPhuongThuc');
const popup = document.getElementById('popupPhuongThuc');
const closeBtn = document.getElementById('closePopup');

btn.onclick = () => popup.style.display = 'flex';
closeBtn.onclick = () => popup.style.display = 'none';
window.onclick = (e) => { if (e.target === popup) popup.style.display = 'none'; };

// Chuyển tab
document.querySelectorAll('.tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelector('.tab.active').classList.remove('active');
    tab.classList.add('active');
    document.querySelector('.tab-content.active').classList.remove('active');
    document.getElementById(tab.dataset.tab).classList.add('active');
  });
});

// Tìm kiếm cửa hàng
document.getElementById('searchStore').addEventListener('input', function() {
  const keyword = this.value.toLowerCase();
  document.querySelectorAll('.store-item').forEach(item => {
    const text = item.innerText.toLowerCase();
    item.style.display = text.includes(keyword) ? 'flex' : 'none';
  });
});

// Xác nhận giao hàng và mở Google Maps
document.getElementById('btnXacNhanGiaoHang').addEventListener('click', function() {
  const address = document.getElementById('deliveryAddress').value.trim();
  if (address === "") {
    alert("Vui lòng nhập địa chỉ giao hàng!");
    return;
  }
  const encodedAddress = encodeURIComponent(address);
  const storeLocation = "Bee+Tea+Coffee,+Thu+Duc,+Ho+Chi+Minh"; // vị trí mặc định cửa hàng
  const mapUrl = `https://www.google.com/maps/dir/?api=1&origin=${storeLocation}&destination=${encodedAddress}`;

  document.getElementById('mapMessage').innerHTML =
    `👉 <a href="${mapUrl}" target="_blank">Mở Google Maps để xem đường đi</a>`;
});
</script>

</body>



</html>


        