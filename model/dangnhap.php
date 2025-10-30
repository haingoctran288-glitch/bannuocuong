<?php
require_once('config.php');

function tatcataikhoan()
{
    global $conn;
    $sql = "SELECT * FROM User limit 1";
    return mysqli_query($conn, $sql);
}

function themmoitk($username, $email, $password, $phone_number, $address)
{
    global $conn;

    // Default role is 3 (user)
    $role_id = 3;

    // Chuỗi salt cố định (thay đổi nếu cần)
    $salt = 'chuoi_bao_mat'; 
    $hashed_password = hash('sha256', $salt . $password); // Mã hóa SHA256

    $sql = "INSERT INTO User (role_id, username, email, password, phone_number, address) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $role_id, $username, $email, $hashed_password, $phone_number, $address);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function chinhsuatk($user_id, $username, $phone_number, $address)
{
    global $conn;

    $sql = "UPDATE User 
            SET username = ?, phone_number = ?, address = ? 
            WHERE user_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssi", $username, $phone_number, $address, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $stmt->close();
            return true; // Cập nhật thành công
        } else {
            $stmt->close();
            return false; // Không có hàng nào bị ảnh hưởng
        }
    } else {
        // Trả về false nếu có lỗi khi chuẩn bị câu lệnh
        return false;
    }
}

function xoatk($id) {
    global $conn;
    $sqlDeleteCart = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sqlDeleteCart);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Xóa tài khoản người dùng
    $sqlDeleteUser = "DELETE FROM User WHERE user_id = ?";
    $stmt = $conn->prepare($sqlDeleteUser);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

?>
