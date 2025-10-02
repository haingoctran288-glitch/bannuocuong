    <?php
    // Start session and include necessary files
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once('../model/config.php');
    require_once('../model/dangnhap.php');
    require_once('../model/product.php');
    require_once('../model/cart.php');
    include_once('../model/order.php');
    include_once('../model/danhmuc.php');

    if (isset($_GET['chucnang'])) {
        $chucnang = $_GET['chucnang'];

        switch ($chucnang) {
            
            case 'login':
                include('../views/dangnhap/dangnhap.php');
                break;

                case 'xulylogin':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
                        $username = trim($_POST['username']);
                        $password = trim($_POST['password']);
                
                        // Chuỗi salt cố định (phải giống với salt trong phần đăng ký)
                        $salt = 'chuoi_bao_mat'; 
                        $hashed_password = hash('sha256', $salt . $password);
                
                        // Truy vấn cơ sở dữ liệu
                        $sql = "SELECT * FROM User WHERE username = ? AND password = ? LIMIT 1";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $username, $hashed_password);
                        $stmt->execute();
                        $result = $stmt->get_result();
                
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                
                            // Khởi động session
                            if (session_status() === PHP_SESSION_NONE) {
                                session_start();
                            }
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['role_id'] = $row['role_id'];
                
                            // Chuyển hướng dựa trên vai trò
                            switch ($row['role_id']) {
                                case 1:
                                    header("Location: ../admin/admin.php");
                                    break;
                                case 2:
                                    header("Location: nhanvien.php");
                                    break;
                                default:
                                    header("Location: ../index.php");
                            }
                            exit();
                        } else {
                            $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
                        }
                        $stmt->close();
                    }
                
                    include('../views/dangnhap/dangnhap.php');
                    break;
                
                
                
                    
                    case 'logout':
                        // Start the session
                        session_start();
                        // Unset all session variables
                        session_unset();
                        // Destroy the session
                        session_destroy();
                        // Redirect to homepage or login page
                        header("Location: ../index.php");
                        exit(); // Ensure the script stops after redirection
                        break;
                
            case 'dangki':
                include('../views/dangnhap/dangki.php');
                break;

                case 'xulydangki':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
                        $username = trim($_POST['username']);
                        $email = trim($_POST['email']);
                        $password = trim($_POST['password']);
                        $phone_number = trim($_POST['phone_number']);
                        $address = trim($_POST['address']);
                
                        // Gọi hàm thêm mới tài khoản
                        if (themmoitk($username, $email, $password, $phone_number, $address)) {
                            echo "Đăng ký thành công!";
                            header("Location: index.php?chucnang=login");
                            exit();
                        } else {
                            echo "Lỗi: Không thể đăng ký!";
                        }
                    }
                    break;
                

                case 'themmoi':
                    // Show form to add new product
                    include('../admin/addproduct.php');
                    break;
        
                    case 'xulythemmoi':
                        if (isset($_POST['name_product']) && isset($_POST['price']) && isset($_POST['description']) && isset($_POST['category_id'])) {
                            if (isset($_FILES['ava'])) {
                                $picture = $_FILES['ava'];
                                $path = __DIR__ . '/uploadFiles';
                                if (!is_dir($path))
                                    mkdir($path, 0777, true); // Create directory if not exists
                    
                                // Move uploaded file
                                if (move_uploaded_file($picture['tmp_name'], $path . '/' . $picture['name'])) {
                                    $duongdan = 'uploadFiles/' . $picture['name'];
                    
                                    // Check if category exists in the database
                                    $madm = $_POST['category_id'];
                                    $madm_check_query = "SELECT * FROM Category WHERE category_id = ?";
                                    $stmt = $conn->prepare($madm_check_query);
                                    $stmt->bind_param("s", $madm);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                    
                                    if ($result->num_rows > 0) {
                                        // Insert product into the database
                                        $sql = "INSERT INTO Product (name_product, description, price, address, category_id) VALUES (?, ?, ?, ?, ?)";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("ssiss", $_POST['name_product'], $_POST['description'], $_POST['price'], $duongdan, $_POST['category_id']);
                                        if ($stmt->execute()) {
                                            echo 'Thêm mới sản phẩm thành công!';
                                            header('Location: ../admin/admin.php');
                                        } else {
                                            echo 'Lỗi khi chèn dữ liệu: ' . $stmt->error;
                                        }
                                    } else {
                                        echo 'Lỗi: Mã danh mục không tồn tại.';
                                    }
                                } else {
                                    echo 'Upload file không thành công!';
                                }
                            }
                        }
                        break;     
                        case 'sua':
                            if (isset($_GET['ma'])) {
                                $product_id = $_GET['ma'];
                        
                                // Truy vấn lấy thông tin sản phẩm từ database
                                $sql = "SELECT * FROM Product WHERE product_id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $product_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                        
                                if ($result->num_rows > 0) {
                                    $sanpham = $result->fetch_assoc();
                                } else {
                                    echo "Không tìm thấy sản phẩm.";
                                    exit;
                                }
                                $stmt->close();
                        
                                // Hiển thị form sửa (gọi file giao diện `updateproduct.php`)
                                include('../admin/updateproduct.php');
                            } else {
                                echo "Mã sản phẩm không hợp lệ.";
                                exit;
                            }
                            break;
                
                            case 'xulysua':
                                if (isset($_GET['ma'])) {
                                    $product_id = $_GET['ma'];
                                    
                                    // Truy vấn lấy thông tin sản phẩm
                                    $sql = "SELECT * FROM Product WHERE product_id = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $product_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                            
                                    if ($result->num_rows > 0) {
                                        $sanpham = $result->fetch_assoc();
                                    } else {
                                        echo "Không tìm thấy sản phẩm.";
                                        exit;
                                    }
                                    $stmt->close();
                            
                                    // Kiểm tra và xử lý dữ liệu từ form
                                    if (isset($_POST['name_product'], $_POST['description'], $_POST['price'])) {
                                        $name_product = $_POST['name_product'];
                                        $description = $_POST['description'];
                                        $price = $_POST['price'];
                            
                                        // Xử lý hình ảnh
                                        $address = $sanpham['address']; // Giữ hình ảnh hiện tại
                                        if (isset($_FILES['address']) && $_FILES['address']['error'] == 0) {
                                            $picture = $_FILES['address'];
                                            $filename = time() . '_' . basename($picture['name']);
                                            $upload_path = __DIR__ . '/../control/uploadFiles/' . $filename;
                            
                                            if (move_uploaded_file($picture['tmp_name'], $upload_path)) {
                                                $address = 'uploadFiles/' . $filename;
                                            } else {
                                                echo "Upload file không thành công!";
                                                exit;
                                            }
                                        }
                            
                                        // Cập nhật sản phẩm
                                        $sql_update = "UPDATE Product SET name_product = ?, description = ?, price = ?, address = ? WHERE product_id = ?";
                                        $stmt = $conn->prepare($sql_update);
                                        $stmt->bind_param("ssdsi", $name_product, $description, $price, $address, $product_id);
                                        if ($stmt->execute()) {
                                            header('Location: ../admin/admin.php');
                                            exit;
                                        } else {
                                            echo "Lỗi cập nhật: " . $stmt->error;
                                        }
                                        $stmt->close();
                                    } else {
                                        echo "Dữ liệu không hợp lệ.";
                                        exit;
                                    }
                                }
                                break; 
                                case 'xoa':
                                    if (isset($_GET['ma'])) {
                                        $product_id = $_GET['ma'];
                                
                                        // Truy vấn thông tin sản phẩm từ database
                                        $sql = "SELECT * FROM Product WHERE product_id = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $product_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                
                                        if ($result->num_rows > 0) {
                                            $sanpham = $result->fetch_assoc();
                                        } else {
                                            echo "Không tìm thấy sản phẩm cần xóa.";
                                            exit;
                                        }
                                        $stmt->close();
                                
                                        // Hiển thị giao diện xác nhận xóa
                                        include('../admin/delete.php');
                                    } else {
                                        echo "Mã sản phẩm không hợp lệ.";
                                        exit;
                                    }
                                    break;
                                case 'xulyxoa':
                                    if (isset($_POST['macanxoa'], $_POST['xacnhan'])) {
                                        if ($_POST['xacnhan'] == 'xoa') {
                                            xoa($_POST['macanxoa']);
                                            header('Location: ../admin/admin.php');
                                            exit;
                                        }
                                        if ($_POST['xacnhan'] == 'huy') {
                                            header('Location: ../admin/admin.php');
                                            exit;
                                        }
                                    }
                                    break;   
                                    case 'xoa_user':
                                        if (isset($_GET['ma'])) {
                                            $user_id = $_GET['ma'];
                                    
                                            // Truy vấn thông tin sản phẩm từ database
                                            $sql = "SELECT * FROM User WHERE user_id = ?";
                                            $stmt = $conn->prepare($sql);
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                    
                                            if ($result->num_rows > 0) {
                                                $user = $result->fetch_assoc();
                                            } else {
                                                echo "Không tìm thấy tài khoản cần xóa.";
                                                exit;
                                            }
                                            $stmt->close();
                                    
                                            // Hiển thị giao diện xác nhận xóa
                                            include('../admin/delete_user.php');
                                        } else {
                                            echo "Mã tài khoản không hợp lệ.";
                                            exit;
                                        }
                                        break; 
                                        case 'xulyxoa_user':
                                            if (isset($_POST['macanxoa'], $_POST['xacnhan'])) {
                                                if ($_POST['xacnhan'] == 'xoa') {
                                                    xoatk($_POST['macanxoa']);
                                                    header('Location: ../admin/user.php');
                                                    exit;
                                                }
                                                if ($_POST['xacnhan'] == 'huy') {
                                                    header('Location: ../admin/user.php');
                                                    exit;
                                                }
                                            }
                                            break;
                                            case 'update_profile':
                                                $user_id = $_SESSION['user_id'];
                                                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                                    $username = $_POST['username'];
                                                    $phone_number = $_POST['phone_number'];
                                                    $address = $_POST['address'];
                                            
                                                    if (chinhsuatk($user_id, $username, $phone_number, $address)) {
                                                        $message = "Thông tin đã được cập nhật thành công!";
                                                        // Chuyển hướng và hiển thị thông báo sau khi cập nhật thành công
                                                        include('../views/dangnhap/profile.php');
                                                    } else {
                                                        $message = "Cập nhật thất bại. Vui lòng thử lại!";
                                                        // Hiển thị thông báo khi cập nhật thất bại
                                                        include('../views/dangnhap/profile.php');
                                                    }
                                                } else {
                                                    include('../views/dangnhap/profile.php');
                                                }
                                                break;
                                            
                                             case 'view_profile':
                                                $user_id = $_SESSION['user_id'];
                                                if (isset($user_id)) {
                                                    // Truy vấn và lấy thông tin người dùng từ database
                                                    $stmt = $conn->prepare("SELECT username, phone_number, address FROM User WHERE user_id = ?");
                                                    $stmt->bind_param("i", $user_id);
                                                    $stmt->execute();
                                                    $result = $stmt->get_result();
                                                    
                                                    if ($result->num_rows > 0) {
                                                        $user = $result->fetch_assoc();
                                                    } else {
                                                        $message = "Không tìm thấy thông tin người dùng.";
                                                    }
                                                    $stmt->close();
                                                } else {
                                                    $message = "Không xác định được người dùng.";
                                                }
                                                include('../views/dangnhap/profile.php');
                                                    break;
                                                   
                    
                                    case 'add':
                                        if (!isset($_SESSION['user_id'])) {
                                            echo "Bạn cần đăng nhập để thực hiện thao tác này.";
                                            exit;
                                        }
                                    
                                        $user_id = $_SESSION['user_id']; // ID người dùng
                                    
                                        // Nếu có dữ liệu từ URL (GET), xử lý cho trường hợp "Đặt mua" trên trang chính
                                        if (isset($_GET['product_id'])) {
                                            $product_id = intval($_GET['product_id']);
                                        
                                            // Thêm sản phẩm vào bảng ProductDetail với thông tin mặc định
                                            $default_size = 'L';
                                            $default_sweetness = 'Bình thường';
                                            $default_ice = 'Bình thường';
                                        
                                            $sql_detail = "INSERT INTO ProductDetail (product_id, size, sweetness_level, ice_level) 
                                                           VALUES (?, ?, ?, ?)";
                                            $stmt_detail = $conn->prepare($sql_detail);
                                            $stmt_detail->bind_param("isss", $product_id, $default_size, $default_sweetness, $default_ice);
                                        
                                            if ($stmt_detail->execute()) {
                                                $stmt_detail->close();
                                        
                                                // Thêm sản phẩm trực tiếp vào giỏ hàng với thông tin mặc định
                                                $sql_cart = "SELECT cart_id FROM Cart WHERE user_id = ?";
                                                $stmt_cart = $conn->prepare($sql_cart);
                                                $stmt_cart->bind_param("i", $user_id);
                                                $stmt_cart->execute();
                                                $result_cart = $stmt_cart->get_result();
                                        
                                                if ($result_cart->num_rows == 0) {
                                                    $sql_insert_cart = "INSERT INTO Cart (user_id) VALUES (?)";
                                                    $stmt_insert_cart = $conn->prepare($sql_insert_cart);
                                                    $stmt_insert_cart->bind_param("i", $user_id);
                                                    $stmt_insert_cart->execute();
                                                    $cart_id = $conn->insert_id;
                                                    $stmt_insert_cart->close();
                                                } else {
                                                    $row_cart = $result_cart->fetch_assoc();
                                                    $cart_id = $row_cart['cart_id'];
                                                }
                                                $stmt_cart->close();
                                        
                                                // Thêm vào giỏ hàng
                                                $quantity = 1;
                                                $sql_insert_item = "INSERT INTO Cart_Item (cart_id, product_id, quantity) VALUES (?, ?, ?)";
                                                $stmt_insert_item = $conn->prepare($sql_insert_item);
                                                $stmt_insert_item->bind_param("iii", $cart_id, $product_id, $quantity);
                                        
                                                if ($stmt_insert_item->execute()) {
                                                    $stmt_insert_item->close();
                                                    header("Location: ../views/cart/cartview.php");
                                                    exit;
                                                } else {
                                                    echo "Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.";
                                                }
                                            } else {
                                                echo "Có lỗi xảy ra khi thêm sản phẩm chi tiết mặc định.";
                                            }
                                        }
                                        
                                        // Nếu dữ liệu đến từ form (POST), xử lý thêm vào giỏ hàng
                                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                            $product_id = $_POST['product_id'];
                                            $size = $_POST['size'];
                                            $sweetness = $_POST['sweetness'];
                                            $ice = $_POST['ice'];
                                    
                                            // Bước 1: Kiểm tra hoặc tạo mới giỏ hàng
                                            $sql_cart = "SELECT cart_id FROM Cart WHERE user_id = ?";
                                            $stmt_cart = $conn->prepare($sql_cart);
                                            $stmt_cart->bind_param("i", $user_id);
                                            $stmt_cart->execute();
                                            $result_cart = $stmt_cart->get_result();
                                    
                                            if ($result_cart->num_rows == 0) {
                                                $sql_insert_cart = "INSERT INTO Cart (user_id) VALUES (?)";
                                                $stmt_insert_cart = $conn->prepare($sql_insert_cart);
                                                $stmt_insert_cart->bind_param("i", $user_id);
                                                $stmt_insert_cart->execute();
                                                $cart_id = $conn->insert_id;
                                                $stmt_insert_cart->close();
                                            } else {
                                                $row_cart = $result_cart->fetch_assoc();
                                                $cart_id = $row_cart['cart_id'];
                                            }
                                            $stmt_cart->close();
                                    
                                            // Bước 2: Thêm sản phẩm vào bảng `Cart_Item`
                                            $sql_check_item = "SELECT cart_item_id, quantity FROM Cart_Item 
                                                               WHERE cart_id = ? AND product_id = ?";
                                            $stmt_check_item = $conn->prepare($sql_check_item);
                                            $stmt_check_item->bind_param("ii", $cart_id, $product_id);
                                            $stmt_check_item->execute();
                                            $result_check_item = $stmt_check_item->get_result();
                                    
                                            if ($result_check_item->num_rows > 0) {
                                                $row_item = $result_check_item->fetch_assoc();
                                                $new_quantity = $row_item['quantity'] + 1;
                                    
                                                $sql_update_item = "UPDATE Cart_Item SET quantity = ? WHERE cart_item_id = ?";
                                                $stmt_update_item = $conn->prepare($sql_update_item);
                                                $stmt_update_item->bind_param("ii", $new_quantity, $row_item['cart_item_id']);
                                                $stmt_update_item->execute();
                                                $stmt_update_item->close();
                                            } else {
                                                $quantity = 1;
                                                $sql_insert_item = "INSERT INTO Cart_Item (cart_id, product_id, quantity) 
                                                                    VALUES (?, ?, ?)";
                                                $stmt_insert_item = $conn->prepare($sql_insert_item);
                                                $stmt_insert_item->bind_param("iii", $cart_id, $product_id, $quantity);
                                                $stmt_insert_item->execute();
                                                $stmt_insert_item->close();
                                            }
                                            $stmt_check_item->close();
                                    
                                            // Chuyển hướng sau khi thêm thành công
                                            header("Location: ../views/cart/cartview.php");
                                            exit;
                                        }
                                        break;
                                    
                            
                                        case 'view':
                                            if (isset($_SESSION['user_id'])) {
                                                $user_id = $_SESSION['user_id'];
                                                $cart = getCart($user_id);
                                                include('../views/cart/cartview.php');
                                            } else {
                                                echo "Vui lòng đăng nhập để xem giỏ hàng.";
                                            }
                                            break;
                                            
                                            case 'update':
                                                if (isset($_POST['cart_item_id']) && isset($_POST['quantity'])) {
                                                    // Lặp qua từng sản phẩm và cập nhật số lượng
                                                    foreach ($_POST['cart_item_id'] as $key => $cart_item_id) {
                                                        $quantity = $_POST['quantity'][$key];
                                                
                                                        // Thực hiện cập nhật số lượng sản phẩm trong giỏ hàng
                                                        $sql = "UPDATE cart_item SET quantity = ? WHERE cart_item_id = ?";
                                                
                                                        // Chuẩn bị câu lệnh SQL và liên kết các tham số
                                                        if ($stmt = $conn->prepare($sql)) {
                                                            $stmt->bind_param("ii", $quantity, $cart_item_id); // 'ii' là kiểu dữ liệu (integer, integer)
                                                            $stmt->execute(); // Thực thi câu lệnh
                                                            $stmt->close(); // Đóng câu lệnh chuẩn bị
                                                        } else {
                                                            echo "Lỗi khi chuẩn bị câu lệnh: " . $conn->error;
                                                        }
                                                    }
                                                
                                                    // Sau khi cập nhật xong, bạn có thể chuyển hướng lại giỏ hàng hoặc hiển thị thông báo
                                                    header("Location: ../views/cart/cartview.php"); // Đổi sang trang giỏ hàng của bạn
                                                    exit();
                                                } else {
                                                    echo "Dữ liệu không hợp lệ.";
                                                }
                                            
                                            case 'remove':
                                                
                                                if (isset($_GET['cart_item_id'])) {
                                                    $cart_item_id = $_GET['cart_item_id'];
                                                    if (removeItemFromCart($cart_item_id)) {
                                                        header("Location: ../views/cart/cartview.php");
                                                        exit();
                                                    } else {
                                                        echo "Lỗi khi xóa sản phẩm khỏi giỏ hàng.";
                                                    }
                                                }
                                                break;
                                                case 'process_payment':
                                                    if (!isset($_SESSION['user_id'])) {
                                                        die("Lỗi: Không tìm thấy user_id trong session.");
                                                    }
                                                
                                                    $user_id = $_SESSION['user_id'];
                                                
                                                    // Kiểm tra giỏ hàng có rỗng không
                                                    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                                                        echo "Giỏ hàng của bạn hiện tại trống.";
                                                        exit;
                                                    }
                                                
                                                    // Tạo hóa đơn mới
                                                    $invoice_date = date('Y-m-d');
                                                    $due_date = date('Y-m-d', strtotime('+7 days'));
                                                
                                                    $recipient_name = $_POST['recipient_name'];
                                                    $address = $_POST['billing_address'];
                                                    $phone = $_POST['phone'];
                                                    $notes = isset($_POST['notes']) ? $_POST['notes'] : null;
                                                
                                                    // Tính tổng tiền từ giỏ hàng
                                                    $total_amount = array_sum(array_map(function ($item) {
                                                        return $item['quantity'] * $item['price'];
                                                    }, $_SESSION['cart']));
                                                
                                                    // Thêm hóa đơn vào bảng Invoice
                                                    $stmt = $conn->prepare("INSERT INTO Invoice (invoice_date, payment_status, total_amount, due_date, billing_address, user_id, recipient_name, phone, notes)
                                                                            VALUES (?, 'Chưa Thanh Toán', ?, ?, ?, ?, ?, ?, ?)");
                                                    $stmt->bind_param("sdssisss", $invoice_date, $total_amount, $due_date, $address, $user_id, $recipient_name, $phone, $notes);
                                                    $stmt->execute();
                                                    $invoice_id = $stmt->insert_id; // Lấy ID của hóa đơn vừa tạo
                                                    $stmt->close();
                                                
                                                    // Thêm các sản phẩm vào bảng Invoice_Detail
                                                    foreach ($_SESSION['cart'] as $item) {
                                                        $stmt = $conn->prepare("INSERT INTO Invoice_Detail (invoice_id, product_id, quantity, price, total_price)
                                                                                VALUES (?, ?, ?, ?, ?)");
                                                        $total_price = $item['quantity'] * $item['price'];
                                                        $stmt->bind_param("iiidd", $invoice_id, $item['product_id'], $item['quantity'], $item['price'], $total_price);
                                                        $stmt->execute();
                                                    }
                                                    $stmt->close();
                                                
                                                    // Xóa các mục trong bảng Cart_Item của người dùng
                                                    $stmt = $conn->prepare("DELETE FROM Cart_Item WHERE cart_id = (SELECT cart_id FROM Cart WHERE user_id = ?)");
                                                    $stmt->bind_param("i", $user_id);
                                                    $stmt->execute();
                                                    $stmt->close();
                                                
                                                    // Xóa giỏ hàng trong session
                                                    unset($_SESSION['cart']);
                                                
                                                    // Chuyển hướng đến trang giỏ hàng hoặc trang khác sau khi thanh toán
                                                    header("Location: ../views/cart/cartview.php");
                                                    exit(); // Dừng thực thi để đảm bảo không có lỗi xảy ra
                                                    break;
                                                
                                             
                                
                                                    case 'cancel_order':
                                                        if (!isset($_SESSION['user_id'])) {
                                                            die("Lỗi: Không tìm thấy user_id trong session.");
                                                        }
                                                    
                                                        $user_id = $_SESSION['user_id'];
                                                        $invoice_id = isset($_POST['invoice_id']) ? intval($_POST['invoice_id']) : null;
                                                    
                                                        if (!$invoice_id) {
                                                            echo "Lỗi: Mã đơn hàng không hợp lệ.";
                                                            exit;
                                                        }
                                                    
                                                        // Kiểm tra trạng thái đơn hàng và quyền sở hữu
                                                        $stmt = $conn->prepare("SELECT payment_status FROM Invoice WHERE invoice_id = ? AND user_id = ?");
                                                        $stmt->bind_param("ii", $invoice_id, $user_id);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $invoice = $result->fetch_assoc();
                                                        $stmt->close();
                                                    
                                                        if (!$invoice) {
                                                            echo "Lỗi: Không tìm thấy đơn hàng.";
                                                            exit;
                                                        }
                                                    
                                                        if ($invoice['payment_status'] !== 'Chưa Thanh Toán') {
                                                            echo "Lỗi: Chỉ có thể hủy đơn hàng chưa thanh toán.";
                                                            exit;
                                                        }
                                                    
                                                        // Xóa các mục chi tiết hóa đơn
                                                        $stmt = $conn->prepare("DELETE FROM Invoice_Detail WHERE invoice_id = ?");
                                                        $stmt->bind_param("i", $invoice_id);
                                                        $stmt->execute();
                                                        $stmt->close();
                                                    
                                                        // Xóa đơn hàng
                                                        $stmt = $conn->prepare("DELETE FROM Invoice WHERE invoice_id = ?");
                                                        $stmt->bind_param("i", $invoice_id);
                                                        $stmt->execute();
                                                        $stmt->close();
                                                    
                                                        echo "Đơn hàng đã được hủy thành công.";
                                                    
                                                        // Chuyển hướng về trang danh sách hóa đơn hoặc trang khác
                                                        header("Location: ../views/Invoice/donhang.php");
                                                        exit();
                                                    
                                                        break;
                                                    
                                                        case 'payment_callback':
                                                            // Kiểm tra thông tin từ cổng thanh toán
                                                            if (isset($_POST['invoice_id']) && isset($_POST['payment_status'])) {
                                                                $invoice_id = $_POST['invoice_id'];
                                                                $status = $_POST['payment_status'];
                                                        
                                                                // Kiểm tra trạng thái thanh toán có hợp lệ không
                                                                if ($status == 'Thanh Toán Thành Công') {
                                                                    // Cập nhật trạng thái thanh toán thành công trong database
                                                                    $stmt = $conn->prepare("UPDATE Invoice SET payment_status = ? WHERE invoice_id = ? AND user_id = ?");
                                                                    $stmt->bind_param("sii", $status, $invoice_id, $_SESSION['user_id']);
                                                                    if ($stmt->execute()) {
                                                                        echo "Cập nhật trạng thái hóa đơn thành công!";
                                                                    } else {
                                                                        echo "Lỗi khi cập nhật trạng thái hóa đơn.";
                                                                    }
                                                                    $stmt->close();
                                                                } else {
                                                                    echo "Thanh toán không thành công hoặc không hợp lệ.";
                                                                }
                                                            } else {
                                                                echo "Thông tin thanh toán không hợp lệ.";
                                                            }
                                                            $conn->close();
                                                            break;
                                                        
                                                    case 'hienthidm':
                                                        $tatcadanhmuc = tatcadanhmuc();
                                                        include('../admin/danhmuc.php');
                                                        break;
                                            
                                                    case 'themdm':
                                                        include('../admin/themdanhmuc.php');
                                                        break;
                                            
                                                    case 'xulythemdm':
                                                        if (isset($_POST['category_id'], $_POST['name_category'])) {
                                                            $tendm = $_POST['name_category'];
                                                            $madm = $_POST['category_id'];
                                            
                                                            $sql = "INSERT INTO category (category_id, name_category) VALUES (?, ?)";
                                                            $stmt = $conn->prepare($sql);
                                                            $stmt->bind_param("is", $madm, $tendm);
                                                            if ($stmt->execute()) {
                                                                header('Location: index.php?chucnang=hienthidm');
                                                            } else {
                                                                echo "Lỗi: " . $stmt->error;
                                                            }
                                                            $stmt->close();
                                                        }
                                                        break;
                                            
                                                    case 'xoadm':
                                                        if (isset($_GET['ma'])) {
                                                            $madm = $_GET['ma'];
                                                    
                                                            // Truy vấn thông tin sản phẩm từ database
                                                            $sql = "SELECT * FROM category WHERE category_id = ?";
                                                            $stmt = $conn->prepare($sql);
                                                            $stmt->bind_param("i", $madm);
                                                            $stmt->execute();
                                                            $result = $stmt->get_result();
                                                    
                                                            if ($result->num_rows > 0) {
                                                                $danhmuc = $result->fetch_assoc();
                                                            } else {
                                                                echo "Không tìm thấy sản phẩm cần xóa.";
                                                                exit;
                                                            }
                                                            $stmt->close();
                                                    
                                                            // Hiển thị giao diện xác nhận xóa
                                                            include('../admin/xoadanhmuc.php');
                                                        } else {
                                                            echo "Mã sản phẩm không hợp lệ.";
                                                            exit;
                                                        }
                                                        break;
                                                    case 'xulyxoadm':
                                                        if (isset($_POST['macanxoa'], $_POST['xacnhan'])) {
                                                            if ($_POST['xacnhan'] == 'xoa') {
                                                                xoadm($_POST['macanxoa']);
                                                                header('Location: ../admin/danhmuc.php');
                                                                exit;
                                                            }
                                                            if ($_POST['xacnhan'] == 'huy') {
                                                                header('Location: ../admin/danhmuc.php');
                                                                exit;
                                                            }
                                                        }
                                            
                                                    case 'suadm':
                                                        if (isset($_GET['ma'])) {
                                                            $ma_danhmuc = $_GET['ma'];
                                                            $sql = "SELECT * FROM category WHERE category_id = ?";
                                                            $stmt = $conn->prepare($sql);
                                                            $stmt->bind_param("s", $ma_danhmuc);
                                                            $stmt->execute();
                                                            $result = $stmt->get_result();
                                                            $danhmuc = $result->fetch_assoc();
                                                            $stmt->close();
                                                        }
                                                        include('../admin/suadanhmuc.php');
                                                        break;
                                            
                                                    case 'xulysuadm':
                                                        if (isset($_GET['ma'])) {
                                                            $ma_danhmuc = $_GET['ma'];
                                                            if (isset($_POST['name_category'])) {
                                                                $sql = "UPDATE category SET name_category = ? WHERE category_id = ?";
                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->bind_param("ss", $_POST['name_category'], $ma_danhmuc);
                                                                if ($stmt->execute()) {
                                                                    header('Location: index.php?chucnang=hienthidm');
                                                                } else {
                                                                    echo "Lỗi cập nhật: " . $stmt->error;
                                                                }
                                                                $stmt->close();
                                                            }
                                                        }
                                                        break;
                                                        case 'list': // Hiển thị danh sách đánh giá
                                                            $reviews = getAllReviews();
                                                            include '../views/review/reviewList.php';
                                                            break;
                                                
                                                        case 'add': // Thêm đánh giá mới
                                                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                $user_id = $_POST['user_id'];
                                                                $product_id = $_POST['product_id'];
                                                                $rating = $_POST['rating'];
                                                                $comment = $_POST['comment'];
                                                
                                                                if (addReview($user_id, $product_id, $rating, $comment)) {
                                                                    $message = 'Thêm đánh giá thành công!';
                                                                } else {
                                                                    $message = 'Có lỗi xảy ra khi thêm đánh giá.';
                                                                }
                                                                echo "<script>alert('$message'); window.location.href = 'index.php?action=list';</script>";
                                                            }
                                                            include '../views/review/addReview.php';
                                                            break;
                                                
                                                        case 'reply': // Phản hồi đánh giá
                                                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                                                $review_id = $_POST['review_id'];
                                                                $admin_comment = $_POST['admin_comment'];
                                                
                                                                if (adminReply($review_id, $admin_comment)) {
                                                                    $message = 'Phản hồi thành công!';
                                                                } else {
                                                                    $message = 'Có lỗi xảy ra khi phản hồi.';
                                                                }
                                                                echo "<script>alert('$message'); window.location.href = 'index.php?action=list';</script>";
                                                            }
                                                            include '../views/review/adminReply.php';
                                                            break;
                                                
                                                            case 'reply_comment':
                                                                if (isset($_POST['comment_id']) && isset($_POST['admin_reply'])) {
                                                                    $comment_id = $_POST['comment_id'];
                                                                    $admin_reply = $_POST['admin_reply'];
                                                
                                                                    // Cập nhật phản hồi vào cơ sở dữ liệu
                                                                    $sql = "UPDATE comments SET admin_reply = ? WHERE comment_id = ?";
                                                                    $stmt = $conn->prepare($sql);
                                                                    $stmt->bind_param('si', $admin_reply, $comment_id);
                                                
                                                                    if ($stmt->execute()) {
                                                                        header("Location: ../admin/conments.php"); // Chuyển hướng về trang quản lý bình luận
                                                                        exit();
                                                                    } else {
                                                                        echo "Lỗi khi lưu phản hồi.";
                                                                    }
                                                
                                                                    $stmt->close();
                                                                } else {
                                                                    echo "Dữ liệu không hợp lệ.";
                                                                }
                                                                break;
                                                            // Thêm các case khác nếu cần xử lý các chức năng khác
                                                          
                                                        }
                                                    }
    ?>
