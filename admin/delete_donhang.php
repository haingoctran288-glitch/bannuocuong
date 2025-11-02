<?php
include '../model/config.php'; // Kết nối CSDL

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']); // Lấy id đơn hàng

    if ($order_id <= 0) {
        header("Location: donhang.php?msg=invalid_id");
        exit();
    }

    // ======= XÓA LIÊN QUAN ĐẾN HÓA ĐƠN =======

    // 1️⃣ Lấy danh sách các hóa đơn thuộc đơn hàng này
    $sql_get_invoice = "SELECT invoice_id FROM invoice WHERE donhang_id = $order_id";
    $invoice_result = mysqli_query($conn, $sql_get_invoice);

    if ($invoice_result && mysqli_num_rows($invoice_result) > 0) {
        while ($invoice = mysqli_fetch_assoc($invoice_result)) {
            $invoice_id = $invoice['invoice_id'];

            // 2️⃣ Xóa chi tiết hóa đơn
            $sql_detail = "DELETE FROM invoice_detail WHERE invoice_id = $invoice_id";
            mysqli_query($conn, $sql_detail);

            // 3️⃣ Xóa hóa đơn
            $sql_invoice = "DELETE FROM invoice WHERE invoice_id = $invoice_id";
            mysqli_query($conn, $sql_invoice);
        }
    }

    // ======= XÓA CHI TIẾT ĐƠN HÀNG (nếu có bảng donhang_chitiet) =======
    $sql_detail_dh = "DELETE FROM donhang_chitiet WHERE donhang_id = $order_id";
    mysqli_query($conn, $sql_detail_dh);

    // ======= XÓA ĐƠN HÀNG =======
    $sql_order = "DELETE FROM donhang WHERE donhang_id = $order_id";
    $result = mysqli_query($conn, $sql_order);

    // ======= KẾT QUẢ =======
    if ($result) {
        header("Location: donhang.php?msg=xoa_thanh_cong");
        exit();
    } else {
        echo "Lỗi khi xóa đơn hàng: " . mysqli_error($conn);
    }

} else {
    header("Location: donhang.php");
    exit();
}
?>
