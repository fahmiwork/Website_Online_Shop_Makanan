<?php
require_once dirname(__FILE__) . '/php/midtrans-php-master/Midtrans.php';

// Set konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-lLJ7-Z53MRr34bVY30nf4LEP';
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Konfigurasi database
include 'database/koneksi.php';

// Ambil ID order dari parameter URL
$id = $_GET['id'];

// Cek apakah transaksi sudah memiliki Snap Token
$query = $conn->query("SELECT * FROM transactions WHERE order_id = '$id'");
if (!$query || mysqli_num_rows($query) == 0) {
    die("Transaksi tidak ditemukan.");
}

$transaction = $query->fetch_assoc();
$snapToken = $transaction['snap_token'];

if (!$snapToken) {
    // Ambil item terkait dengan order_id
    $orderQuery = $conn->query("SELECT order_items.quantity, order_items.product_name, order_items.price
                                FROM order_items 
                                WHERE order_items.order_id = '$id'");
    if (!$orderQuery || mysqli_num_rows($orderQuery) == 0) {
        die("Order tidak ditemukan.");
    }

    // Ambil data item
    $item_details = [];
    while ($row = mysqli_fetch_assoc($orderQuery)) {
        $item_details[] = array(
            'id' => $id,
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'name' => $row['product_name'],
        );
    }

    // Hitung total pembayaran
    $gross_amount = array_sum(array_column($item_details, 'price')) * array_sum(array_column($item_details, 'quantity'));

    // Buat parameter transaksi Midtrans
    $transaction_details = array(
        'order_id' => $id,
        'gross_amount' => $gross_amount,
    );

    $params = array(
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
    );

    try {
        // Buat Snap Token baru
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // Simpan Snap Token ke tabel transactions
        $conn->query("UPDATE transactions SET snap_token='$snapToken' WHERE order_id='$id'");
    } catch (Exception $e) {
        die("Gagal mendapatkan Snap Token: " . $e->getMessage());
    }
}

// Tutup koneksi database
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Midtrans</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-XXXXX"></script>
</head>
<body>
    <h2>Pembayaran Midtrans</h2>
    <button id="pay-button">Bayar Sekarang</button>

    <script>
        document.getElementById('pay-button').onclick = function () {
            var snapToken = "<?= $snapToken ?>";
            window.snap.pay(snapToken, {
onSuccess: function (result) {
        Swal.fire({
          icon: "success",
          title: "BERHASIL",
          text: "yes",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          window.location.href = "pesanan.php"; // Arahkan ke halaman checkout
        });
      },
      onPending: function (result) {
        Swal.fire({
          icon: "warning",
          title: "PENDING",
          text: "Waiting for your payment!",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          window.location.href = "pesanan.php"; // Arahkan ke halaman
        });
      },
      onError: function (result) {
        Swal.fire({
          icon: "eror",
          title: "ERROR",
          text: "Payment failed!",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          window.location.href = "pesanan.php"; // Arahkan ke halaman checkout
        });
      },
      onClose: function () {
        Swal.fire({
          icon: "warning",
          title: "PENDING",
          text: "You closed the popup without finishing the payment",
          showConfirmButton: true,
          timer: 2000,
        }).then(() => {
          window.location.href = "pesanan.php"; // Arahkan ke halaman checkout
        });
      },
            });

            
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
