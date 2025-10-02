<?php
// Kết nối tới cơ sở dữ liệu
require_once('../../model/config.php');
require_once('../../model/product.php');

// Bắt đầu session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy thông tin người dùng
$user = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : null;

// Lấy thông tin giỏ hàng
$giohang_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

// Kiểm tra ID sản phẩm từ URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = intval($_GET['id']); // Chuyển đổi thành số nguyên

    // Truy vấn cơ sở dữ liệu để lấy thông tin sản phẩm
    $sql = "SELECT * FROM Product WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sanpham = $result->fetch_assoc();  
}  


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
$sql_comments = "SELECT c.comment_text, c.admin_reply, c.created_at, u.username 
                 FROM comments c 
                 JOIN User u ON c.user_id = u.user_id 
                 WHERE c.product_id = ? 
                 ORDER BY c.created_at DESC";
$stmt = $conn->prepare($sql_comments);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$comments = $stmt->get_result();

// Đừng quên đóng kết nối khi đã xong
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compact Header Section</title>
   <link rel="stylesheet" href="./chitiet1.css">
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
    color: darkorange;  /* Green color on hover */
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
.product-container {
    display: flex;
    flex-direction: row;
    align-items: flex-start; /* Align items to start */
    margin: 40px 0; /* Increased margin for better separation */
    border: 1px solid #e0e0e0; /* Light border around the container */
    border-radius: 10px; /* Rounded corners for the container */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    overflow: hidden; /* Prevent overflow of child elements */
}

.product-image {
    flex: 1; /* Allow image section to grow */
    padding: 20px; /* Padding around the image */
    background-color: #f9f9f9; /* Light background for contrast */
}

.product-image img {
    width: 100%; /* Full width for responsiveness */
    max-width: 100%; /* Ensure it doesn't exceed the container */
    height: auto; /* Maintain aspect ratio */
    border-radius: 10px; /* Rounded corners for the image */
}

.product-content {
    flex: 2; /* Allow content section to grow more */
    padding: 20px; /* Added padding for spacing */
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    justify-content: space-between; /* Space out elements */
    background-color: #ffffff; /* White background for content */
}

.product-info h2 {
    font-size: 28px; /* Increased font size */
    color: #333; /* Darker color for better readability */
    margin-bottom: 15px; /* Space below the heading */
}

.product-info p {
    font-size: 16px; /* Standard font size for description */
    color: #666; /* Softer color for description */
    margin-bottom: 20px; /* Space below the description */
}

.price-quantity {
    display: flex;
    justify-content: space-between; /* Space between price and quantity */
    align-items: center; /* Center align items vertically */
    margin: 15px 0; /* Added margin for spacing */
}

.price {
    font-size: 24px; /* Larger font for price */
    font-weight: bold; /* Bold for emphasis */
    color: #006400; /* Green color for price */
}

.quantity-label {
    font-size: 16px; /* Standard font size for quantity label */
    color: #333; /* Darker color for better readability */
}

.add-to-cart {
    margin-top: auto; /* Push to the bottom of the product content */
    text-align: center; /* Center the button within the container */
    padding: 15px; /* Add padding around the container */
    background-color: #f9f9f9; /* Light background for contrast */
    border-radius: 5px; /* Rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transitions */
}

.add-to-cart button {
    background-color: #006400; /* Green background */
    color: white; /* White text */
    padding: 12px 20px; /* Padding for the button */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size for the button text */
    font-weight: bold; /* Bold text for emphasis */
    cursor: pointer; /* Pointer cursor on hover */
    border: none; /* Remove default border */
    transition: background-color 0.3s, transform 0.3s; /* Smooth transitions */
    width: 100%; /* Full width for the button */
}

.add-to-cart button:hover {
    background-color: #004d00; /* Darker green on hover */
    transform: translateY(-2px); /* Slight lift effect on hover */
}

.add-to-cart button:active {
    transform: translateY(0); /* Reset lift effect on click */
}
label {
    font-size: 18px; /* Increased font size for labels */
    color: #333; /* Darker color for better readability */
    margin-bottom: 10px; /* Space below the label */
    display: block; /* Make labels block elements */
}

.size-options, .sweet-options, .ice-options {
    display: flex; /* Use flexbox for alignment */
    gap: 20px; /* Space between options */
    margin-bottom: 30px; /* Space below each option group */
}

.size-options label, .sweet-options label, .ice-options label {
    display: flex; /* Align label and input in a row */
    align-items: center; /* Center align items vertically */
    cursor: pointer; /* Change cursor to pointer on hover */
    transition: background-color 0.3s; /* Smooth background transition */
    padding: 8px 12px; /* Padding for better clickable area */
    border-radius: 5px; /* Rounded corners */
}

.size-options label:hover, .sweet-options label:hover, .ice-options label:hover {
    background-color: #f0f0f0; /* Light background on hover */
}

input[type="radio"] {
    margin-right: 8px; /* Space between radio button and label text */
    accent-color: #006400; /* Change the color of the radio button (modern browsers) */
}
body {
    font-family: Arial, sans-serif; /* Set a default font */
    background-color: #f4f4f4; /* Light background for the body */
    margin: 0; /* Remove default margin */
    padding: 20px; /* Add padding around the body */
}

.product-container {
    display: flex;
    flex-direction: row;
    align-items: flex-start; /* Align items to the start */
    margin: 40px auto; /* Center the container with margin */
    max-width: 1200px; /* Limit max width */
    background-color: #ffffff; /* White background for the product container */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    overflow: hidden; /* Prevent overflow of child elements */
}

.product-image {
    flex: 1; /* Allow image section to grow */
    padding: 20px; /* Padding around the image */
}

.product-image img {
    width: 100%; /* Full width for responsiveness */
    max-width: 100%; /* Ensure it doesn't exceed the container */
    height: auto; /* Maintain aspect ratio */
    border-radius: 10px; /* Rounded corners for the image */
}

.product-content {
    flex: 2; /* Allow content section to grow more */
    padding: 20px; /* Added padding for spacing */
    display: flex;
    flex-direction: column; /* Stack elements vertically */
}

.product-info h2 {
    font-size: 28px; /* Increased font size */
    color: #333; /* Darker color for better readability */
    margin-bottom: 15px; /* Space below the heading */
}

.product-info p {
    font-size: 16px; /* Standard font size for description */
    color: #666; /* Softer color for description */
    margin-bottom: 20px; /* Space below the description */
}

.price-quantity {
    display: flex;
    justify-content: space-between; /* Space between price and quantity */
    margin: 15px 0; /* Added margin for spacing */
}

.price {
    font-size: 24px; /* Larger font for price */
    font-weight: bold; /* Bold for emphasis */
    color: #006400; /* Green color for price */
}

label {
    font-size: 18px; /* Increased font size for labels */
    color: #333; /* Darker color for better readability */
    margin-bottom: 10px; /* Space below the label */
    display: block; /* Make labels block elements */
}

.size-options, .sweet-options, .ice-options {
    display: flex; /* Use flexbox for alignment */
    gap: 20px; /* Space between options */
    margin-bottom: 30px; /* Space below each option group */
}

.size-options label, .sweet-options label, .ice-options label {
    display: flex; /* Align label and input in a row */
    align-items: center; /* Center align items vertically */
    cursor: pointer; /* Change cursor to pointer on hover */
    transition: background-color 0.3s; /* Smooth background transition */
    padding: 8px 12px; /* Padding for better clickable area */
    border-radius: 5px; /* Rounded corners */
}

.size-options label:hover, .sweet-options label:hover, .ice-options label:hover {
    background-color: #f0f0f0; /* Light background on hover */
}

input[type="radio"] {
    margin-right: 8px; /* Space between radio button and label text */
    accent-color: #006400; /* Change the color of the radio button (modern browsers) */
}

.add-to-cart {
    margin-top: auto; /* Push to the bottom of the product content */
    text-align: center; /* Center the button within the container */
    padding: 15px; /* Add padding around the container */
    background-color: #f9f9f9; /* Light background for contrast */
    border-radius: 5px; /* Rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

.add-to-cart button {
    background-color: #006400; /* Green background */
    color: white; /* White text */
    padding: 12px 20px; /* Padding for the button */
    border-radius: 5px;
}
.comment-section {
    margin-top: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    background-color: #f9f9f9;
}

.comment-section textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
}

.comment-list {
    margin-top: 20px;
}

.comment-item {
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
}

.comment-item p {
    margin: 5px 0;
}

.comment-item small {
    color: gray;
    font-size: 0.9em;
}

    </style>
</head>
<body>
<header class="header">
    <div class="header-left">
        <img src="../../img/logo bee.png" height="50%" width="500px;" alt="Logo" class="logo">
        <input type="text" placeholder="Bạn muốn mua gì..." class="search-bar">
    </div>
    
    <div class="header-right">
        <div class="icons">
            <!-- <a href="./control/index.php?chucnang=cart"> -->
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
            <img src="../../img/profile.png" alt="Profile Icon" class="icon">

            <ul>
                    <?php if (isset($_SESSION['username'])) { ?>
                        <li><a href="../../control/index.php?chucnang=view">Giỏ hàng</a></li>
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

<div class="product-container">
    <div class="product-image">
        <img src="../../control/<?php echo htmlspecialchars($sanpham['address']); ?>" alt="<?php echo htmlspecialchars($sanpham['name_product']); ?>">
    </div>
    <div class="product-content">
    <div class="product-info">
        <h2><?php echo $sanpham['name_product']; ?></h2>
        <p>Mô tả: <?php echo $sanpham['description']; ?></p>
        <div class="price-quantity">
    <div class="price"><?php echo $sanpham['price']; ?> đ</div>
</div>
<h2>Chọn chi tiết sản phẩm</h2>
<form action="../../control/index.php?chucnang=add" method="POST">
    <!-- Chọn kích cỡ -->
    <label>Chọn kích cỡ:</label>
    <div class="size-options">
        <label>
            <input type="radio" name="size" value="M" required> M
        </label>
        <label>
            <input type="radio" name="size" value="L" checked> L
        </label>
        <label>
            <input type="radio" name="size" value="XL"> XL
        </label>
    </div>

    <!-- Độ ngọt -->
    <label>Chọn độ ngọt:</label>
    <div class="sweet-options">
        <label>
            <input type="radio" name="sweetness" value="Ít" required> Ít
        </label>
        <label>
            <input type="radio" name="sweetness" value="Bình thường" checked> Bình thường
        </label>
        <label>
            <input type="radio" name="sweetness" value="Nhiều"> Nhiều
        </label>
    </div>

    <!-- Chọn đá -->
    <label>Chọn độ đá:</label>
    <div class="ice-options">
        <label>
            <input type="radio" name="ice" value="Ít" required> Ít
        </label>
        <label>
            <input type="radio" name="ice" value="Bình thường" checked> Bình thường
        </label>
        <label>
            <input type="radio" name="ice" value="Nhiều"> Nhiều
        </label>
    </div>

    <!-- Product ID (Ẩn đi) -->
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

    <!-- Nút submit -->
    <div class="add-to-cart">
        <button type="submit">🛒 Thêm vào giỏ hàng</button>
    </div>
</form>

    </div>
    </div>
    
</div>

    <div class="comment-section">
    <h3>Bình luận</h3>
    <?php if ($user): ?>
        <form action="conment.php" method="POST">
            <textarea name="comment_text" rows="3" placeholder="Viết bình luận của bạn..." required></textarea>
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
            <button type="submit">Gửi bình luận</button>
        </form>
    <?php else: ?>
        <p>Bạn cần <a href="../../control/index.php?chucnang=login">đăng nhập</a> để bình luận.</p>
    <?php endif; ?>

    <div class="comment-list">
    <h3>Các bình luận</h3>
    <?php if ($comments->num_rows > 0): ?>
        <?php while ($row = $comments->fetch_assoc()): ?>
            <div class="comment-item">
                <p><strong><?php echo $row['username']; ?>:</strong></p>
                <p><?php echo $row['comment_text']; ?></p>
                <?php if (!empty($row['admin_reply'])): ?>
                    <p><strong>Phản hồi từ admin:</strong> <?php echo $row['admin_reply']; ?></p>
                <?php else: ?>
                    <p><em>Chưa có phản hồi từ admin.</em></p>
                <?php endif; ?>
                <small><?php echo $row['created_at']; ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Chưa có bình luận nào.</p>
    <?php endif; ?>
</div>

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
    <p>© Công ty CP Phúc Long Heritage 2024</p>
    <div>
      <img src="http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png" alt="Đã thông báo Bộ Công Thương" style="height: 40px; margin-right: 15px;">
      <a href="#"><img src="./img//inta.png" alt="Instagram" style="height: 30px;"></a>
      <a href="#"><img src="./img//face.png" alt="Facebook" style="height: 30px; margin: 0 15px;"></a>
      <a href="#"><img src="./img//youtube.png" alt="YouTube" style="height: 30px;"></a>
    </div>
  </div><script src="chitiet.js"></script>
  </footer>

</body>
</html>
