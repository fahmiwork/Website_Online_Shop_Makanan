<?php
require_once dirname(__FILE__) . '/php/midtrans-php-master/Midtrans.php';

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-lLJ7-Z53MRr34bVY30nf4LEP';
\Midtrans\Config::$isProduction = false;

// Konfigurasi database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'shopping_cart';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data notifikasi dari Midtrans
$notification = new \Midtrans\Notification();

// Dapatkan data transaksi dari notifikasi
$transactionStatus = $notification->transaction_status;
$orderId = $notification->order_id; // Ini adalah order_id yang Anda kirim ke Midtrans
$fraudStatus = $notification->fraud_status;

// Pisahkan order_id untuk mendapatkan ID asli dari database
$realOrderId = explode('-', $orderId)[1]; // Ambil bagian kedua dari order_id (format: ORDER-12345-...)

// Update status transaksi berdasarkan notifikasi
if ($transactionStatus == 'capture') {
    if ($fraudStatus == 'accept') {
        // Pembayaran berhasil
        $status = '3'; // Misal, 2 = Pembayaran Berhasil
    }
} elseif ($transactionStatus == 'settlement') {
    // Pembayaran berhasil
    $status = '3'; // Misal, 2 = Pembayaran Berhasil
} elseif ($transactionStatus == 'pending') {
    // Pembayaran tertunda
    $status = '2'; // Misal, 1 = Menunggu Pembayaran
} elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
    // Pembayaran gagal
    $status = '0'; // Misal, 0 = Pembayaran Gagal
} else {
    // Status tidak dikenali
    $status = '0';
}

// Update status di database
$sql = "UPDATE transactions SET status = '$status' WHERE order_id = '$realOrderId'";
if ($conn->query($sql) === TRUE) {
    echo "Status transaksi berhasil diupdate.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>