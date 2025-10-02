<?php
require_once('../../model/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$invoice_id = isset($_GET['invoice_id']) ? $_GET['invoice_id'] : null;

if (!$invoice_id) {
    echo "Lỗi: Mã đơn hàng không hợp lệ.";
    exit;
}

// Lấy thông tin hóa đơn từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT i.total_amount, i.billing_address, i.payment_status FROM Invoice i WHERE i.invoice_id = ? AND i.user_id = ?");
$stmt->bind_param("ii", $invoice_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();
$stmt->close();

if (!$invoice) {
    echo "Không tìm thấy hóa đơn.";
    exit;
}

// Tạo URL thanh toán VNPay
function generateVNPayURL($orderId, $amount) {
    $vnp_TmnCode = "YOUR_VNP_TMNCODE"; // Thay bằng mã TMN của bạn
    $vnp_HashSecret = "YOUR_VNP_HASHSECRET"; // Chuỗi bí mật
    $vnp_Url = "https://pay.vnpay.vn/vpcpay.html";

    $vnp_Amount = $amount * 100; // Quy đổi sang đơn vị VNPay
    $vnp_TxnRef = $orderId;
    $vnp_OrderInfo = "Thanh toán hóa đơn #" . $orderId;
    $vnp_ReturnUrl = "https://beeteacoffee.vn/payment_return.php"; // Thay bằng URL của bạn

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
        "vnp_Locale" => 'vn',
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_ReturnUrl" => $vnp_ReturnUrl,
        "vnp_TxnRef" => $vnp_TxnRef
    );

    ksort($inputData);
    $query = "";
    $hashdata = "";
    foreach ($inputData as $key => $value) {
        $hashdata .= $key . "=" . $value . '&';
        $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }
    $query = rtrim($query, '&');
    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    return $vnp_Url . "?" . $query . "&vnp_SecureHash=" . $vnpSecureHash;
}

$qr_url = generateVNPayURL($invoice_id, $invoice['total_amount']);

// Xử lý thanh toán bằng tiền mặt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method']) && $_POST['payment_method'] === 'cash') {
    $stmt = $conn->prepare("UPDATE Invoice SET payment_status = 'Thanh toán thành công' WHERE invoice_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $invoice_id, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('Thanh toán tiền mặt thành công!'); window.location.href = 'hoadon.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật trạng thái thanh toán.');</script>";
    }
    $stmt->close();
    $conn->close();
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            font-size: 16px;
            line-height: 1.5;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:active {
            background-color: #003f7f;
        }

        img {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: white;
        }

        #qr-section, #cash-section {
            margin-top: 20px;
            font-size: 16px;
            color: #444;
        }
    </style>
</head>
<body>
    <h1>Thanh toán hóa đơn</h1>
    <p>Chọn phương thức thanh toán:</p>
    <div style="text-align: center; margin-bottom: 20px;">
        <button onclick="showQRCode()" style="margin-right: 10px; padding: 10px 20px;">Thanh toán QR Code</button>
        <form method="POST" style="display: inline;">
            <input type="hidden" name="payment_method" value="cash">
            <button type="submit" style="padding: 10px 20px;">Thanh toán Tiền mặt</button>
        </form>
    </div>
    <div id="qr-section" style="display: none; text-align: center;">
        <a href="<?php echo $qr_url; ?>" target="_blank">
            <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo urlencode($qr_url); ?>&size=200x200" alt="VNPay QR Code">
        </a>
        <p>Quét mã QR để thanh toán qua VNPay.</p>
    </div>
    <script>
        function showQRCode() {
            document.getElementById('qr-section').style.display = 'block';
        }
    </script>
</body>
</html>
