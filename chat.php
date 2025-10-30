<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

// =============================
// 🔐 Cấu hình API & Database
// =============================
$apiKey = "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"; // ← Thay bằng API key thật
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gecafe"; // ← Tên database bạn đã import

// =============================
// 📩 Nhận input
// =============================
$input = json_decode(file_get_contents("php://input"), true);
$msg = trim($input["message"] ?? "");

if ($msg === "") {
  echo json_encode(["reply" => "Bạn chưa nhập gì cả 😅."]);
  exit;
}

$lower = mb_strtolower($msg, "UTF-8");
date_default_timezone_set("Asia/Ho_Chi_Minh");

// =============================
// 1️⃣ Câu hỏi về thời gian
// =============================
if (strpos($lower, "mấy giờ") !== false) {
  echo json_encode(["reply" => "Bây giờ là " . date("H:i") . " phút ⏰."], JSON_UNESCAPED_UNICODE);
  exit;
}
if (strpos($lower, "ngày bao nhiêu") !== false || (strpos($lower, "hôm nay") !== false && strpos($lower, "ngày") !== false)) {
  echo json_encode(["reply" => "Hôm nay là ngày " . date("d/m/Y") . " 📅."], JSON_UNESCAPED_UNICODE);
  exit;
}
if (strpos($lower, "thứ mấy") !== false) {
  $days = ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
  echo json_encode(["reply" => "Hôm nay là " . $days[date("w")] . " 🌤️."], JSON_UNESCAPED_UNICODE);
  exit;
}

// =============================
// 2️⃣ Thông tin cố định của quán
// =============================
$info = [
  "địa chỉ" => "📍 Quán BeaCoffe nằm tại: Số 45 Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh",
  "ở đâu" => "📍 Quán BeaCoffe ở: Số 45 Nguyễn Huệ, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh",
  "mở cửa" => "⏰ Quán mở cửa từ 7h sáng đến 22h mỗi ngày.",
  "đóng cửa" => "⏰ Quán mở cửa từ 7h sáng đến 22h, tầm 10h là nghỉ rồi bạn nha.",
  "wifi" => "📶 WiFi quán là: Gecafe_Free — mật khẩu: uongdiroihetbuon 😄",
  "liên hệ" => "📞 Hotline: 0901 234 567 — Fanpage: facebook.com/BeaCoffe",
  "số điện thoại" => "📞 Số điện thoại BeaCoffe: 0901 234 567",
  "fanpage" => "🌐 Fanpage: facebook.com/BeaCoffe",
  "khuyến mãi" => "🎉 Hiện tại Gecafe đang có combo giảm 20% cho nhóm từ 3 người trở lên hoặc tặng topping miễn phí khi mua 2 ly trở lên nha!"
];

foreach ($info as $key => $reply) {
  if (strpos($lower, $key) !== false) {
    echo json_encode(["reply" => $reply], JSON_UNESCAPED_UNICODE);
    exit;
  }
}

// =============================
// 3️⃣ Kiểm tra xem có cần hỏi SQL không
// =============================
$keywords = ["menu", "đồ uống", "trà", "cà phê", "matcha", "sữa", "đào", "chanh", "bán gì", "loại", "uống gì", "sản phẩm"];
$needSQL = false;
foreach ($keywords as $kw) {
  if (strpos($lower, $kw) !== false) {
    $needSQL = true;
    $filterWord = $kw;
    break;
  }
}

if ($needSQL) {
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    echo json_encode(["reply" => "⚠️ Lỗi kết nối cơ sở dữ liệu."]);
    exit;
  }

  $sql = "SELECT name_product, price FROM product WHERE name_product LIKE ? OR description LIKE ? LIMIT 10";
  $stmt = $conn->prepare($sql);
  $like = "%" . $filterWord . "%";
  $stmt->bind_param("ss", $like, $like);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $list = [];
    while ($row = $result->fetch_assoc()) {
      $list[] = "{$row['name_product']} ({$row['price']}đ)";
    }
    $reply = "Dưới đây là các món có liên quan đến '$filterWord' mà quán đang có 🧋:\n- " . implode("\n- ", $list);
  } else {
    $reply = "Hiện chưa có món nào liên quan đến '$filterWord' ạ 😅.";
  }

  echo json_encode(["reply" => $reply], JSON_UNESCAPED_UNICODE);
  $stmt->close();
  $conn->close();
  exit;
}

// =============================
// 4️⃣ Nếu không thuộc SQL hay thông tin cứng → GPT trả lời tự nhiên
// =============================
$data = [
  "model" => "gpt-4o-mini",
  "messages" => [
    [
      "role" => "system",
      "content" => "Bạn là nhân viên tư vấn thân thiện của quán Gecafe (một quán cà phê ở Quy Nhơn). 
      Nhiệm vụ của bạn là trò chuyện tự nhiên, vui vẻ, khuyên khách chọn đồ uống phù hợp với tâm trạng hoặc thời tiết. 
      Hãy gợi ý món từ menu (các loại trà, cà phê, sữa, matcha...) nhưng không cần dữ liệu chính xác từ SQL. 
      Giữ giọng thân mật, tích cực, dễ thương."
    ],
    ["role" => "user", "content" => $msg]
  ],
  "temperature" => 0.9,
  "max_tokens" => 600
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
  ],
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
  echo json_encode(["reply" => "⚠️ Lỗi khi kết nối đến OpenAI."]);
  curl_close($ch);
  exit;
}
curl_close($ch);

$res = json_decode($response, true);
$reply = $res["choices"][0]["message"]["content"] ?? "Xin lỗi, mình chưa hiểu ý bạn 😅.";
echo json_encode(["reply" => $reply], JSON_UNESCAPED_UNICODE);
