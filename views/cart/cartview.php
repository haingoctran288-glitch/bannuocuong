<?php
require_once('../../model/config.php');
require_once('../../model/product.php');
require_once('../../model/cart.php');

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

    // Initialize cart if it's not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the user is logged in
    $user = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : null;

    if ($user) {
        // Get user ID from session
        $user_id = $_SESSION['user_id'];

        // Fetch cart data from the database if necessary
        $cart_query = "SELECT c.cart_id, ci.cart_item_id, ci.product_id, ci.quantity, p.name_product, p.price, p.address
                    FROM Cart c
                    JOIN Cart_Item ci ON c.cart_id = ci.cart_id
                    JOIN Product p ON ci.product_id = p.product_id
                    WHERE c.user_id = ?";
        
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $user_id); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        $cart_items = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
    } else {
        $cart_items = $_SESSION['cart']; // If not logged in, use session cart data
    }

    // Count the number of items in the cart
    $giohang_count = count($cart_items);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="cart.css">
    <style>
      .l1 {
    position: relative;
    display: inline-block;
}
    .header-left img{
        width: 300px;
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
/* Giỏ hàng */
.cart-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.cart-items h2 {
    text-align: center;
    font-size: 34px;
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 30px;
    letter-spacing: 1px;
}

.cart-items table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
}

.cart-items th, .cart-items td {
    padding: 18px;
    text-align: left;
    font-size: 18px;
    color: #555;
}

.cart-items th {
    background-color: #ecf0f1;
    font-weight: 600;
    color: #34495e;
}

.cart-items td {
    border-bottom: 1px solid #ddd;
}

.cart-items input[type="number"] {
    width: 70px;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ddd;
    font-size: 16px;
    text-align: center;
    transition: border-color 0.3s ease;
}

.cart-items input[type="number"]:focus {
    border-color: #3498db;
}

.cart-items button {
    padding: 2px 7px; /* Nút nhỏ lại */
    background-color: #2ecc71; /* Màu xanh lá cây */
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px; /* Kích thước chữ nhỏ */
    transition: background-color 0.3s ease, transform 0.3s ease;
    margin-top: 5px;
}

.cart-items button:hover {
    background-color: #27ae60; /* Màu xanh lá cây tối hơn khi hover */
    transform: translateY(-3px);
}

.cart-items td a {
    color: #e74c3c;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.cart-items td a:hover {
    color: #c0392b;
}

.cart-summary {
    margin-top: 40px;
    padding: 30px;
    background-color: #ecf0f1;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.cart-summary h3 {
    font-size: 28px;
    font-weight: 700;
    color: #34495e;
    margin-bottom: 25px;
}

.cart-summary p {
    font-size: 18px;
    color: #555;
    margin: 10px 0;
}

.cart-summary span {
    font-weight: 600;
}

.cart-summary .highlight {
    color: #2ecc71;
    font-size: 22px;
}

.cart-summary button {
    width: 100%;
    padding: 14px;
    background-color: #2ecc71; /* Màu xanh lá cây */
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    margin-top: 25px;
    transition: background-color 0.3s ease;
}

.cart-summary button:hover {
    background-color: #27ae60; /* Màu xanh lá cây tối hơn khi hover */
}

.cart-container button {
    width: 100%;
    padding: 14px;
    background-color: #2ecc71; /* Màu xanh lá cây */
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    margin-top: 30px;
    transition: background-color 0.3s ease;
}

.cart-container button:hover {
    background-color: #27ae60; /* Màu xanh lá cây tối hơn khi hover */
}
/* Nút trong giỏ hàng */
.cart-items button {
    padding: 5px 12px; /* Giảm padding để làm nút nhỏ lại */
    background-color: #229954; /* Màu xanh lá cây đậm hơn */
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 12px; /* Kích thước chữ nhỏ */
    transition: background-color 0.3s ease, transform 0.3s ease;
    margin-top: 5px;
}

.cart-items button:hover {
    background-color: #1e8449; /* Màu xanh lá cây đậm tối hơn khi hover */
    transform: translateY(-3px);
}

/* Nút cập nhật giỏ hàng */
.cart-container button {
    width: auto; /* Giữ chiều rộng tự động */
    padding: 6px 14px; /* Giảm padding để nút nhỏ hơn */
    background-color: #229954; /* Màu xanh lá cây đậm hơn */
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px; /* Kích thước chữ nhỏ */
    cursor: pointer;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}

.cart-container button:hover {
    background-color: #1e8449; /* Màu xanh lá cây đậm tối hơn khi hover */
}

.cart-summary button {
        font-size: 14px; /* Kích thước chữ */
        padding: 8px 16px; /* Khoảng cách bên trong */
        background-color: green; /* Màu nền */
        color: white; /* Màu chữ */
        border: none; /* Bỏ đường viền */
        border-radius: 5px; /* Bo góc */
        cursor: pointer; /* Hiển thị con trỏ khi di chuột */
        transition: all 0.3s ease; /* Hiệu ứng khi hover */
    }

    .cart-summary button:hover {
        background-color:#00693e; /* Màu nền khi hover */
    }

    .cart-summary button:active {
        transform: scale(0.95); /* Hiệu ứng khi nhấn */
    }

    .cart-summary button + button {
        margin-left: 10px; /* Khoảng cách giữa các nút */
    }
    </style>
</head>
<body>
     <!--Header-->
     <header class="header">
    <div class="header-left">
        <img src="../../img/logo bee.png" height="50%" alt="Logo" class="logo">
        <input type="text" placeholder="Bạn muốn mua gì..." class="search-bar">
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

    <!-- Giỏ hàng -->
    <div class="cart-container">
    <div class="cart-items">
    <h2>Giỏ hàng của tôi</h2>
<?php if (!empty($cart_items)): ?>
    <form action="../../control/index.php?chucnang=update" method="post">
        <table>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tạm Tính</th>
                <th>Hành động</th>
            </tr>
            <?php 
            $total = 0; // Khởi tạo tổng tiền
            foreach ($cart_items as $item): 
                $item_total = $item['quantity'] * $item['price'];
                $total += $item_total; // Tính tổng tiền
            // Lưu tổng vào session
            $_SESSION['total'] = $total;
            ?>
            <tr>
            <td style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="flex: 1;"><?php echo $item['name_product']; ?></span>
                            <img src="../../control/<?php echo $item['address']; ?>" 
                                alt="Image of <?php echo $item['name_product']; ?>" 
                                style="width: 100px; height: auto; border-radius: 8px; border: 1px solid #ccc;">
                            </td>
                <td>
                    <input type="hidden" name="cart_item_id[]" value="<?php echo $item['cart_item_id']; ?>">
                    <input type="number" name="quantity[]" value="<?php echo $item['quantity']; ?>" min="1">
                </td>
                <td><?php echo $item['price'], "đ"; ?></td>
                <td><?php echo number_format($item_total, 3, '.', '.') . "đ"; ?></td>
                <td>
                    <a href="../../control/index.php?chucnang=remove&cart_item_id=<?php echo $item['cart_item_id']; ?>">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Nút cập nhật cho tất cả sản phẩm -->
        <button type="submit">Cập nhật giỏ hàng</button>
    </form>

    <div class="cart-summary">
    <p>Tổng thanh toán: <span class="highlight"><?php echo number_format($total, 3, '.', '.') . "đ"; ?></span></p>
    <button onclick="window.location.href='../Invoice/invoice.php';">Đặt Hàng</button> 
    <button onclick="window.location.href='../../index.php';">Quay lại menu</button>
</div>
<?php else: ?>
    <p>Giỏ hàng của bạn hiện tại trống.</p>
    <button onclick="window.location.href='../../views/menu/menu1.php';">Quay lại menu</button>
<?php endif; ?>
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
      <a href="#"><img src="/img/IG.jpg" alt="Instagram" style="height: 30px;"></a>
      <a href="#"><img src="/img/Face.jpg" alt="Facebook" style="height: 30px; margin: 0 15px;"></a>
      <a href="#"><img src="/img/youtube.jpg" alt="YouTube" style="height: 30px;"></a>
    </div>
  </div>
  </footer>


    <script src="cart.js"></script>   
</body>
</html>