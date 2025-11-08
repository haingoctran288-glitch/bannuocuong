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

// 🔹 Lấy thông tin hóa đơn
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

// 🔹 Chuẩn hóa số tiền
$tongTien = floatval(preg_replace('/[^0-9.]/', '', $invoice['total_amount']));
$amount = intval(round($tongTien));

if ($amount < 1000) {
    die("<p style='color:red;text-align:center;'>⚠️ Số tiền quá nhỏ. Tối thiểu 1,000 VND để thanh toán qua MoMo.</p>");
}
if ($amount > 50000000) {
    die("<p style='color:red;text-align:center;'>⚠️ Số tiền vượt quá 50,000,000 VND mà MoMo cho phép.</p>");
}

// 🔁 HÀM TẠO URL THANH TOÁN MOMO
function generateMoMoURL($invoice_id, $amount)
{
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = "MOMOBKUN20180529";
    $accessKey = "klm05TvNBzhg7h7j";
    $secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
    $redirectUrl = "http://localhost/ChuyenDeThucTap/views/Invoice/payment_return.php";
    $ipnUrl = "http://localhost/ChuyenDeThucTap/views/Invoice/thanhtoan_ipn.php";

    $orderId = "HD" . $invoice_id . "_" . time();
    $orderInfo = "Thanh toán hóa đơn #" . $invoice_id;
    $requestId = time() . "";
    $requestType = "captureWallet";
    $extraData = "";

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

    if (isset($response['payUrl'])) {
        return $response['payUrl'];
    } else {
        echo "<pre style='color:red;background:#ffeaea;padding:10px;border-radius:5px;'>⚠️ LỖI MoMo:\n";
        print_r($response);
        echo "</pre>";
        return null;
    }
}

// 💳 THANH TOÁN BẰNG MOMO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['payment_method'] ?? '') === 'momo') {
    $momoUrl = generateMoMoURL($invoice_id, $amount);
    if ($momoUrl) {
        header("Location: " . $momoUrl);
        exit;
    } else {
        echo "<script>alert('❌ Không tạo được URL thanh toán MoMo.');</script>";
    }
}

// 💵 THANH TOÁN TIỀN MẶT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['payment_method'] ?? '') === 'cash') {
    $stmt = $conn->prepare("UPDATE Invoice SET payment_status = 'Đã thanh toán' WHERE invoice_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $invoice_id, $user_id);
    if ($stmt->execute()) {
        $deleteCart = $conn->prepare("DELETE FROM Cart WHERE user_id = ?");
        $deleteCart->bind_param("i", $user_id);
        $deleteCart->execute();
        $deleteCart->close();
        echo "<script>alert('✅ Thanh toán tiền mặt thành công!'); window.location.href='donhang.php';</script>";
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
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .payment-container {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 40px 60px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .payment-container h2 {
            color: #a50064;
            margin-bottom: 25px;
        }

        .payment-container p {
            font-size: 16px;
            margin: 10px 0;
            color: #333;
            text-align: left;
        }

        .payment-container .amount {
            font-size: 22px;
            color: #a50064;
            font-weight: bold;
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn {
            background-color: #a50064;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            padding: 12px 22px;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #870050;
        }

        footer {
            margin-top: 25px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="payment-container">
    <h2>Thanh toán hóa đơn #<?php echo $invoice_id; ?></h2>

    <p><b>Địa chỉ giao hàng:</b> <?php echo htmlspecialchars($invoice['billing_address']); ?></p>
    <p><b>Trạng thái:</b> <?php echo htmlspecialchars($invoice['payment_status']); ?></p>
    <p><b>Tổng tiền:</b> <span class="amount"><?php echo number_format($amount, 0, ',', '.'); ?> VND</span></p>

    <div class="buttons">
        <form method="POST" style="display:inline;">
            <input type="hidden" name="payment_method" value="momo">
            <button type="submit" class="btn">Thanh toán bằng MoMo</button>
        </form>

        <form method="POST" style="display:inline;">
            <input type="hidden" name="payment_method" value="cash">
            <button type="submit" class="btn">Thanh toán tiền mặt</button>
        </form>
    </div>

    <footer>Vui lòng hoàn tất thanh toán để xác nhận đơn hàng của bạn.</footer>
</div>
</body>
</html>

