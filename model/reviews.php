<?php
require_once('config.php');

// Lấy tất cả đánh giá
function getAllReviews() {
    global $conn;
    $sql = "SELECT r.*, u.username, p.product_name 
            FROM Review r
            LEFT JOIN User u ON r.user_id = u.user_id
            LEFT JOIN Product p ON r.product_id = p.product_id";
    return mysqli_query($conn, $sql);
}

// Thêm đánh giá
function addReview($user_id, $product_id, $rating, $comment) {
    global $conn;
    $sql = "INSERT INTO Review (user_id, product_id, rating, comment, review_date) 
            VALUES ('$user_id', '$product_id', '$rating', '$comment', NOW())";
    return mysqli_query($conn, $sql);
}

// Phản hồi admin
function adminReply($review_id, $admin_comment) {
    global $conn;
    $sql = "UPDATE Review SET admin_reply = '$admin_comment' WHERE review_id = '$review_id'";
    return mysqli_query($conn, $sql);
}
?>
