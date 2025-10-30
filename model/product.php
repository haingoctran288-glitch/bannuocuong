<?php
require_once('config.php');

// Function to get all products
function tatcasanpham() {
    global $conn;  // Changed to $conn
    $sql = 'SELECT * FROM Product';  // Adjusted table name to 'Product'
    return mysqli_query($conn, $sql);  // Updated connection variable
}

// Function to add a new product
function themmoi($name_product, $category_id, $price, $description, $address) {
    global $conn;  // Assuming $conn is your database connection

    $sql = "INSERT INTO Product (name_product, description, price, address, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $name_product, $category_id, $price, $description, $address);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to edit an existing product
function chinhsua($product_id, $name_product, $price, $description, $address) {
    global $conn;  // Changed to $conn
    
    // Sanitize input data to prevent SQL injection
    $name_product = mysqli_real_escape_string($conn, $name_product);
    $price = mysqli_real_escape_string($conn, $price);
    $description = mysqli_real_escape_string($conn, $description);
    $address = mysqli_real_escape_string($conn, $address);
    
    // SQL query to update the product details
    $sql = "UPDATE Product SET name_product = '$name_product', price = '$price', description = '$description', address = '$address' 
            WHERE product_id = '$product_id'";
    
    mysqli_query($conn, $sql);  // Updated connection variable
}

// Function to delete a product
function xoa($product_id) {
    global $conn;  // Changed to $conn
    $product_id = mysqli_real_escape_string($conn, $product_id);
    
    // SQL query to delete the product from the 'Product' table
    $sql = "DELETE FROM Product WHERE product_id = '$product_id'";
    
    mysqli_query($conn, $sql);  // Updated connection variable
}

?>
