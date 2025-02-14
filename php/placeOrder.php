<?php
header('Content-Type: application/json'); // Pastikan header respons adalah JSON
require_once dirname(__FILE__) . '/midtrans-php-master/Midtrans.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-lLJ7-Z53MRr34bVY30nf4LEP';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Invalid input data");
    }

    // Validasi data yang diperlukan
    if (empty($data['customer']['first_name']) || empty($data['customer']['last_name']) || empty($data['customer']['email']) || empty($data['customer']['phone']) || empty($data['customer']['address'])) {
        throw new Exception("All customer details are required");
    }

    if (empty($data['items']) || !is_array($data['items'])) {
        throw new Exception("Items data is required and must be an array");
    }

    $firstName = $data['customer']['first_name'];
    $lastName = $data['customer']['last_name'];
    $email = $data['customer']['email'];
    $phone = $data['customer']['phone'];
    $address = $data['customer']['address'];
    // $id_user = $data['customer']['id_user'];

    // Konfigurasi database
include '../database/koneksi.php';

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Simpan ke tabel orders terlebih dahulu untuk mendapatkan order_id
        $stmt = $conn->prepare("INSERT INTO orders (id_user, total, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
        $stmt->bind_param("id", $id_user, $grossAmount);

        $id_user = $data['customer']['id_user'];
        $grossAmount = $data['total'];

        if (!$stmt->execute()) {
            throw new Exception("Failed to save data to orders table: " . $stmt->error);
        }

        $order_id = $stmt->insert_id; // Ambil order_id yang baru saja di-generate

        // Simpan ke tabel order_items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_name, price, quantity, total, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");

        foreach ($data['items'] as $item) {
            $product_name = $item['title'];
            $price = $item['price'];
            $quantity = $item['qty'];
            $item_total = $price * $quantity;

            $stmt->bind_param("isdid", $order_id, $product_name, $price, $quantity, $item_total);

            if (!$stmt->execute()) {
                throw new Exception("Failed to save data to order_items table: " . $stmt->error);
            }
        }

        // Persiapkan data untuk Midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => $order_id, // Gunakan order_id dari tabel orders
                'gross_amount' => $grossAmount,
            ),
            'item_details' => array_map(function($item) {
                return array(
                    'id' => $item['id'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'name' => $item['title'],
                );
            }, $data['items']),
            'customer_details' => array(
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Simpan ke tabel transactions dengan order_id yang sama
        $stmt = $conn->prepare("INSERT INTO transactions (order_id, snap_token, gross_amount, items, first_name, last_name, email, phone, address, id_user, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssssssii", $order_id, $snapToken, $grossAmount, $items, $firstName, $lastName, $email, $phone, $address, $id_user, $status);

        $items = json_encode($params['item_details']);
        $status = 1; // Contoh status

        if (!$stmt->execute()) {
            throw new Exception("Failed to save data to transactions table: " . $stmt->error);
        }

        // Commit transaksi
        $conn->commit();
        echo json_encode(['snapToken' => $snapToken]);
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error
        $conn->rollback();
        throw $e;
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>