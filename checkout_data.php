<?php

header("Content-Type: application/json");

// Konfigurasi database
$host = "localhost";
$user = "root";
$password = "";
$database = "shopping_cart";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed: " . $conn->connect_error]));
}

// Cek apakah method adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil id_user dari input form
    $id_users = isset($_POST['id_users']);
    $data = json_decode(file_get_contents("php://input"), true);


    // Validasi input


    if (!isset($data['items'], $data['total'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid input data format. Ensure 'items' and 'total' exist."]);
        exit;
    }

    $items = $data['items'];
    $total = $data['total'];

    $conn->begin_transaction();

    try {
        // Simpan ke tabel orders
        $stmt = "INSERT INTO orders (id_user, total, created_at, updated_at) VALUES ($id_users, $total, NOW(), NOW())";
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
        echo json_encode(["message" => "Order placed successfully", "order_id" => $order_id]);
    } catch (Exception $e) {
        // Rollback jika ada kesalahan
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["message" => "Failed to place order", "error" => $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Method not allowed"]);
}
