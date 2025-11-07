<?php
// khuyenmai_detail.php
$id = $_GET['id'] ?? 0; // Lấy id tin khuyến mãi trên URL

// Bạn có thể thay đoạn này bằng truy vấn SQL thật từ CSDL
$promotions = [
  1 => [
    'title' => 'TÀI KHOẢN MỚI ĐƯỢC GIẢM 20%',
    'image' => 'uploadFiles/ChatGPT Image 12_06_13 7 thg 11, 2025.png',
    'content' => '
      Chào mừng thành viên mới đến với Bee Tea & Coffee! 💛<br>
      Từ ngày <b>07.11 – 28.11.2025</b>, mọi tài khoản mới đăng ký sẽ được giảm ngay <b>20%</b> cho đơn hàng đầu tiên.<br><br>
      👉 Hãy bắt đầu hành trình thưởng thức trà sữa cùng Bee Tea, nơi mỗi ly đều chứa niềm vui và năng lượng tích cực! 🌼
    ',
    'slogan' => 'Bee Tea – Ngọt ngào từng khoảnh khắc 🍯'
  ],
  2 => [
    'title' => 'ƯU ĐÃI 10% CHO ĐƠN HÀNG TỪ 149K',
    'image' => 'uploadFiles/14902476-bb20-4d3d-bdb1-a55382ea4299.png',
    'content' => '
      Mùa lễ hội đến rồi! Từ ngày <b>15.09 – 20.11.2025</b>, Bee Tea tặng ngay <b>10%</b> cho các đơn hàng từ 149K trở lên.<br><br>
      🧋 Hãy cùng bạn bè tận hưởng những buổi chiều mát lành bên ly trà sữa thơm ngát, đậm vị yêu thương!
    ',
    'slogan' => 'Uống cùng Bee Tea – Ưu đãi tràn đầy 🎉'
  ],
  3 => [
    'title' => 'TẬN HƯỞNG GIÁNG SINH CÙNG BEE TEA',
    'image' => 'uploadFiles/a819da63-8665-4ed8-888a-67202538f1e1.png',
    'content' => '
      Mùa Giáng Sinh ấm áp đã về 🎄<br>
      Bee Tea & Coffee gửi đến bạn chương trình khuyến mãi đặc biệt từ <b>7.11 – 25.12.2025</b>.<br>
      Nhận ngay Voucher giảm đến 10% khi bạn mua Việt Quất Đá Xay từ 2 ly trở lên!
    ',
    'slogan' => 'Bee Tea – Hạnh phúc lan tỏa qua từng giọt ☕'
  ],
  4 => [
    'title' => 'HAPPY HALLOWEEN 🎃 CÙNG BEE TEA VÀ COFFEE NÀO',
    'image' => 'uploadFiles/aa0d8659-1086-4e08-8765-9d595d9963d1.png',
    'content' => '
      Đến hẹn lại lên! Halloween này, Bee Tea mang đến cho bạn chương trình GIẢM 10% cực hấp dẫn 🎃<br>
      Từ ngày <b>31.10 – 12.11.2025</b>, khi mua từ 2 ly Trà RuBy Cam Đào trở lên!!! CÒN CHỜ GÌ NỮA?.<br><br>
      👻 Đừng bỏ lỡ cơ hội cùng bạn bè hóa thân và thưởng thức hương vị ma mị, ngọt ngào cùng Bee Tea!
    ',
    'slogan' => 'Bee Tea – Trick or Treat, ngọt ngào hết cỡ! 👻'
  ]
];

$post = $promotions[$id] ?? null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title><?= $post['title'] ?? 'Chi tiết khuyến mãi' ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background: #fff;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .promo-container {
      max-width: 900px;
      margin: 60px auto;
      padding: 20px;
      text-align: center;
    }
    .promo-container img {
      max-width: 100%;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    h1 {
      color: #0a7c3d;
      margin-bottom: 15px;
    }
    .slogan {
      font-style: italic;
      color: #e67e22;
      font-weight: bold;
      margin-top: 25px;
      font-size: 18px;
    }
  </style>
</head>
<body>
  <?php if ($post): ?>
    <div class="promo-container">
      <h1><?= $post['title'] ?></h1>
      <img src="<?= $post['image'] ?>" alt="<?= $post['title'] ?>">
      <p><?= $post['content'] ?></p>
      <p class="slogan">🌟 <?= $post['slogan'] ?> 🌟</p>
    </div>
  <?php else: ?>
    <div class="promo-container">
      <h2>Không tìm thấy bài viết khuyến mãi!</h2>
    </div>
  <?php endif; ?>









<div style="text-align: center; margin-top: 40px;">
    <a href="index.php" style="
        display: inline-block;
        padding: 10px 20px;
        background-color: #ffb700;
        color: #fff;
        font-weight: bold;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s;
    ">⬅️ Trở về Trang chủ</a>
</div>











</body>
</html>
