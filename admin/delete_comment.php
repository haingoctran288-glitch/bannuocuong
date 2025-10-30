<?php
ob_start();
require_once('../model/config.php');

if (isset($_GET['id'])) {
    $comment_id = intval($_GET['id']);

    $sql = "DELETE FROM comments WHERE comment_id = $comment_id";

    if (mysqli_query($conn, $sql)) {
        // ✅ chuyển hướng về file conments.php (đúng tên file của bạn)
        header("Location: /CHUYENDETHUCTAP/admin/conments.php?msg=deleted");
        exit();
    } else {
        echo "❌ Lỗi khi xóa bình luận: " . mysqli_error($conn);
    }
} else {
    // ✅ nếu không có id thì cũng quay lại conments.php
    header("Location: /CHUYENDETHUCTAP/admin/conments.php");
    exit();
}

ob_end_flush();
?>
