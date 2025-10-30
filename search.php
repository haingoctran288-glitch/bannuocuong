<<<<<<< HEAD
<?php
require_once("model/config.php");

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $sql = "SELECT * FROM product WHERE name_product LIKE '%$keyword%'";
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #c27400;
            font-size: 26px;
            margin-bottom: 30px;
        }

        em {
            font-style: italic;
            color: #d17d00;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .product {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 15px;
            width: 220px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
        }

        .product img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
        }

        .product h3 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #333;
        }

        .product p {
            color: #777;
            font-size: 16px;
        }

        .back-btn {
            display: inline-block;
            background-color: #c27400;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            margin-top: 40px;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background-color: #a85f00;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Kết quả tìm kiếm cho: <em><?php echo htmlspecialchars($keyword); ?></em></h2>

    <div class="product-list">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="product">';
echo '<a href="views/chitietsanpham/chitiet.php?id=' . $row['product_id'] . '">';
echo '<img src="' . $row['address'] . '" alt="' . htmlspecialchars($row['name_product']) . '">';
echo '</a>';
echo '<h3><a href="views/chitietsanpham/chitiet.php?id=' . $row['product_id'] . '">' . htmlspecialchars($row['name_product']) . '</a></h3>';
echo '<p>' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
echo '</div>';


            }
        } else {
            echo "<p>Không tìm thấy sản phẩm nào.</p>";
        }
        ?>
    </div>

    <a href="index.php" class="back-btn">⬅ Trở về trang chính</a>
</div>

</body>
</html>
=======
<?php
require_once("model/config.php");

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $sql = "SELECT * FROM product WHERE name_product LIKE '%$keyword%'";
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            text-align: center;
        }

        h2 {
            color: #c27400;
            font-size: 26px;
            margin-bottom: 30px;
        }

        em {
            font-style: italic;
            color: #d17d00;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }

        .product {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 15px;
            width: 220px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.2);
        }

        .product img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 12px;
        }

        .product h3 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #333;
        }

        .product p {
            color: #777;
            font-size: 16px;
        }

        .back-btn {
            display: inline-block;
            background-color: #c27400;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 8px;
            margin-top: 40px;
            transition: background-color 0.2s;
        }

        .back-btn:hover {
            background-color: #a85f00;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Kết quả tìm kiếm cho: <em><?php echo htmlspecialchars($keyword); ?></em></h2>

    <div class="product-list">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="product">';
echo '<a href="views/chitietsanpham/chitiet.php?id=' . $row['product_id'] . '">';
echo '<img src="' . $row['address'] . '" alt="' . htmlspecialchars($row['name_product']) . '">';
echo '</a>';
echo '<h3><a href="views/chitietsanpham/chitiet.php?id=' . $row['product_id'] . '">' . htmlspecialchars($row['name_product']) . '</a></h3>';
echo '<p>' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>';
echo '</div>';


            }
        } else {
            echo "<p>Không tìm thấy sản phẩm nào.</p>";
        }
        ?>
    </div>

    <a href="index.php" class="back-btn">⬅ Trở về trang chính</a>
</div>

</body>
</html>
>>>>>>> 685257245985a8fff6921ee1a9bc01a0d89d1a51
