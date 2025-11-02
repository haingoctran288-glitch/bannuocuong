<?php
include '../model/config.php'; // file kết nối CSDL

if (isset($_GET['id'])) {
    $invoice_id = intval($_GET['id']);

    // Xóa chi tiết hóa đơn trước (nếu có ràng buộc)
    $sql_detail = "DELETE FROM invoice_detail WHERE invoice_id = $invoice_id";
    mysqli_query($conn, $sql_detail);

    // Xóa hóa đơn chính
    $sql_invoice = "DELETE FROM invoice WHERE invoice_id = $invoice_id";
    $result = mysqli_query($conn, $sql_invoice);

    if ($result) {
        header("Location: hoadon.php?msg=xoa_thanh_cong");
        exit();
    } else {
        echo "Lỗi khi xóa hóa đơn: " . mysqli_error($conn);
    }
} else {
    header("Location: hoadon.php");
    exit();
}
?>
