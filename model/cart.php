<?php
// Thêm sản phẩm vào giỏ hàng
function addToCart($user_id, $product_id, $quantity) {
    global $conn;
    // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
    $sql = "SELECT c.cart_id FROM Cart c WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Giỏ hàng đã tồn tại, thêm sản phẩm vào Cart_Item
        $cart = $result->fetch_assoc();
        $cart_id = $cart['cart_id'];

        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $checkProduct = "SELECT * FROM Cart_Item WHERE cart_id = ? AND product_id = ?";
        $stmt = $conn->prepare($checkProduct);
        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();
        $productResult = $stmt->get_result();

        if ($productResult->num_rows > 0) {
            // Nếu sản phẩm đã có, cập nhật số lượng
            $updateQuantity = "UPDATE Cart_Item SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?";
            $stmt = $conn->prepare($updateQuantity);
            $stmt->bind_param("iii", $quantity, $cart_id, $product_id);
            return $stmt->execute();
        } else {
            // Nếu sản phẩm chưa có, thêm sản phẩm vào giỏ hàng
            $insertProduct = "INSERT INTO Cart_Item (cart_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertProduct);
            $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
            return $stmt->execute();
        }
    } else {
        // Nếu giỏ hàng chưa có, tạo giỏ hàng mới
        $insertCart = "INSERT INTO Cart (user_id) VALUES (?)";
        $stmt = $conn->prepare($insertCart);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $cart_id = $stmt->insert_id;
            $insertProduct = "INSERT INTO Cart_Item (cart_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertProduct);
            $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
            return $stmt->execute();
        }
    }
}

// Lấy giỏ hàng của người dùng
function getCart($user_id) {
    global $conn;
    $sql = "SELECT ci.cart_item_id, ci.quantity, p.name_product, p.price
            FROM Cart_Item ci
            JOIN Product p ON ci.product_id = p.product_id
            JOIN Cart c ON ci.cart_id = c.cart_id
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
function updateCartItem($cart_item_id, $quantity) {
    global $conn;
    $sql = "UPDATE Cart_Item SET quantity = ? WHERE cart_item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $quantity, $cart_item_id);
    return $stmt->execute();
}

// Xóa sản phẩm khỏi giỏ hàng
function removeItemFromCart($cart_item_id) {
    global $conn;
    $sql = "DELETE FROM Cart_Item WHERE cart_item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_item_id);
    return $stmt->execute();
}
?>
