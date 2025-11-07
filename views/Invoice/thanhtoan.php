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
    echo "❌ Lỗi: Mã đơn hàng không hợp lệ.";
    exit;
}

// ======================
// 🔹 Lấy thông tin hóa đơn
// ======================
$stmt = $conn->prepare("SELECT i.total_amount, i.billing_address, i.payment_status 
                        FROM Invoice i 
                        WHERE i.invoice_id = ? AND i.user_id = ?");
$stmt->bind_param("ii", $invoice_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();
$stmt->close();

if (!$invoice) {
    echo "❌ Không tìm thấy hóa đơn.";
    exit;
}

// ======================
// 🔹 Chuẩn hóa số tiền
// ======================
$tongTien = floatval(preg_replace('/[^0-9.]/', '', $invoice['total_amount']));
$amount = intval(round($tongTien));

if ($amount < 1000) {
    die("<p style='color:red;text-align:center;'>⚠️ Số tiền quá nhỏ. Tối thiểu 1,000 VND để thanh toán qua MoMo.</p>");
}
if ($amount > 50000000) {
    die("<p style='color:red;text-align:center;'>⚠️ Số tiền vượt quá 50,000,000 VND mà MoMo cho phép.</p>");
}

// ======================
// 🔁 HÀM TẠO URL THANH TOÁN MOMO
// ======================
function generateMoMoURL($invoice_id, $amount)
{
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = "MOMOBKUN20180529";
    $accessKey = "klm05TvNBzhg7h7j";
    $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
    $redirectUrl = "http://localhost/ChuyenDeThucTap/views/Invoice/thanhtoan_success.php";
    $ipnUrl = "http://localhost/ChuyenDeThucTap/views/Invoice/thanhtoan_ipn.php";

    $orderId = "HD" . $invoice_id . "_" . time();
    $orderInfo = "Thanh toán hóa đơn #" . $invoice_id;
    $requestId = time() . "";
    $requestType = "captureWallet";
    $extraData = "";

    // 🔸 Tạo chữ ký (signature)
    $rawSignature = "accessKey=" . $accessKey .
        "&amount=" . $amount .
        "&extraData=" . $extraData .
        "&ipnUrl=" . $ipnUrl .
        "&orderId=" . $orderId .
        "&orderInfo=" . $orderInfo .
        "&partnerCode=" . $partnerCode .
        "&redirectUrl=" . $redirectUrl .
        "&requestId=" . $requestId .
        "&requestType=" . $requestType;

    $signature = hash_hmac("sha256", $rawSignature, $secretKey);

    // 🔸 Chuẩn bị dữ liệu gửi đi
    $data = [
        'partnerCode' => $partnerCode,
        'partnerName' => "MoMo Test",
        'storeId' => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => (string)$amount,
        'orderId' => (string)$orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    ];

    // 🔸 Gửi request tới MoMo
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($data, JSON_UNESCAPED_UNICODE))
    ]);

    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);

    // 🔸 Kiểm tra phản hồi từ MoMo
    if (isset($response['payUrl'])) {
        return $response['payUrl'];
    } else {
        echo "<pre style='color:red;background:#ffeaea;padding:10px;border-radius:5px;'>⚠️ LỖI MoMo:\n";
        print_r($response);
        echo "</pre>";
        return null;
    }
}

// ======================
// 🔹 Tạo URL QR MoMo
// ======================
$qr_url = generateMoMoURL($invoice_id, $amount);

// ======================
// 💵 XỬ LÝ THANH TOÁN TIỀN MẶT
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method']) && $_POST['payment_method'] === 'cash') {
    $stmt = $conn->prepare("UPDATE Invoice SET payment_status = 'Thanh toán thành công' WHERE invoice_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $invoice_id, $user_id);
    if ($stmt->execute()) {
        echo "<script>alert('✅ Thanh toán tiền mặt thành công!'); window.location.href = 'hoadon.php';</script>";
    } else {
        echo "<script>alert('❌ Lỗi khi cập nhật trạng thái thanh toán.');</script>";
    }
    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán MoMo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8fafc;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #a50064;
            margin-bottom: 20px;
        }
        p {
            text-align: center;
            font-size: 16px;
        }
        .btn {
            background-color: #a50064;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            padding: 10px 20px;
            margin: 5px;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #88004f;
        }
        img {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: white;
        }
        #qr-section {
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
        <button class="btn" onclick="showQRCode()">Thanh toán bằng MoMo (QR)</button>
        <form method="POST" style="display: inline;">
            <input type="hidden" name="payment_method" value="cash">
            <button type="submit" class="btn">Thanh toán tiền mặt</button>
        </form>
    </div>

    <div id="qr-section" style="display: none; text-align: center;">
        <?php if ($qr_url): ?>
            <a href="<?php echo $qr_url; ?>" target="_blank">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo urlencode($qr_url); ?>&size=200x200" alt="MoMo QR Code">
            </a>
            <p>📱 Quét mã QR để thanh toán qua <b>MoMo</b>.</p>
        <?php else: ?>
            <p style="color:red;">Không tạo được QR MoMo. Vui lòng thử lại.</p>
        <?php endif; ?>
    </div>

    <script>
        function showQRCode() {
            document.getElementById('qr-section').style.display = 'block';
        }
    </script>
</body>
</html>
