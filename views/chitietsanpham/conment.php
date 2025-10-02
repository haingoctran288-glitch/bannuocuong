<?php
require_once('../../model/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $comment_text = isset($_POST['comment_text']) ? trim($_POST['comment_text']) : '';
    session_start();

    // Kiểm tra nếu người dùng đã đăng nhập
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Kiểm tra dữ liệu đầu vào
        if ($product_id && !empty($comment_text)) {
            // Chuẩn bị câu truy vấn
            $sql = "INSERT INTO comments (product_id, user_id, comment_text, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Gán giá trị vào các tham số
                $stmt->bind_param("iis", $product_id, $user_id, $comment_text);

                // Thực thi câu truy vấn
                if ($stmt->execute()) {
                    // Chuyển hướng về lại trang chi tiết sản phẩm
                    header("Location: chitiet.php?id=" . $product_id);
                    exit();
                } else {
                    echo "Lỗi khi thêm bình luận: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Lỗi chuẩn bị câu truy vấn: " . $conn->error;
            }
        } else {
            echo "Dữ liệu không hợp lệ. Vui lòng kiểm tra thông tin.";
        }
    } else {
        echo "Bạn cần đăng nhập để bình luận.";
    }

    $conn->close();
}
?>
