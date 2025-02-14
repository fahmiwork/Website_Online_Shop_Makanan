<?php

header("Content-Type: application/json");

$host = "localhost";
$user = "root"; // Sesuaikan dengan user MySQL Anda
$password = ""; // Sesuaikan dengan password MySQL Anda
$database = "shopping_cart"; // Sesuaikan dengan nama database Anda

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed: " . $conn->connect_error]));
}

// Ambil data dari body request
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['items']) || !isset($data['total'])) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid input data"]);
    exit;
}

$items = $data['items'];
$total = $data['total'];

$conn->begin_transaction();

try {
    // Simpan ke tabel orders
    $stmt = $conn->prepare("INSERT INTO orders (total, created_at, updated_at) VALUES (?, NOW(), NOW())");
    $stmt->bind_param("d", $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Simpan ke tabel order_items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity, total, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");

    foreach ($items as $item) {
        $product_name = $item['title'];
        $price = $item['price'];
        $quantity = $item['qty'];
        $item_total = $price * $quantity;

        $stmt->bind_param("isdid", $order_id, $product_name, $price, $quantity, $item_total);
        $stmt->execute();
    }
    $stmt->close();

    // Commit transaksi
    $conn->commit();

    // Berikan response sukses
    http_response_code(201);
    echo json_encode(["message" => "Order placed successfully"]);
    
} catch (Exception $e) {
    // Rollback jika ada kesalahan
    $conn->rollback();
    error_log("Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["message" => "Failed to place order", "error" => $e->getMessage()]);
} finally {
    $conn->close();
}
