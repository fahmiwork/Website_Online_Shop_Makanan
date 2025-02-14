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

// Query untuk mendapatkan data order dan item terkait
$query = mysqli_query($conn, "SELECT orders.id AS order_id, orders.total,
    order_items.quantity, order_items.product_name, order_items.price
    FROM orders 
    INNER JOIN order_items ON orders.id = order_items.order_id 
    WHERE orders.id = '$id'");

if (!$query || mysqli_num_rows($query) == 0) {
    die("Order tidak ditemukan.");
}

// Ambil data item dari query
$item_details = [];
while ($row = mysqli_fetch_assoc($query)) {
    $item_details[] = array(
        'id' => $row['order_id'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'name' => $row['product_name'],
    );
}

$transaction_details = array(
    'order_id' => $id,
    'gross_amount' => array_sum(array_column($item_details, 'price')) * array_sum(array_column($item_details, 'quantity')),
);

$params = array(
    'transaction_details' => $transaction_details,
    'item_details' => $item_details,
);

try {
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    // header('location: pesanan.php');
} catch (Exception $e) {
    die("Gagal mendapatkan Snap Token: " . $e->getMessage());
}
?>


<?php
include 'header.php';

$id = $_GET['id'] ?? ''; // Tambahkan validasi jika ID tidak ada
$nomor = 1;

if (!$id) {
    echo "<p>Order ID tidak ditemukan.</p>";
    exit;
}

// Query mengambil data pesanan dan item
$query = mysqli_query($conn, "SELECT orders.id AS order_id, order_items.quantity, order_items.product_name, order_items.price,
                                  (order_items.price * order_items.quantity) AS total, orders.total AS grandtotal
                                  FROM orders 
                                  INNER JOIN order_items ON orders.id = order_items.order_id 
                                  WHERE orders.id = '$id'");

if (mysqli_num_rows($query) > 0) {
    // Ambil grand total dari hasil pertama (karena nilainya sama untuk semua item)
    $data = mysqli_fetch_assoc($query);
    $Grandtotal = $data['grandtotal'];
    ?>
    
    <div class="container">
        <table class="w-full border-collapse border-spacing-0">
            <thead>
                <tr>
                    <th class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</th>
                    <th class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Product details</th>
                    <th class="border-b-2 border-main pb-3 pl-2 text-left font-bold text-main">Price</th>
                    <th class="border-b-2 border-main pb-3 pl-2 text-left font-bold text-main">Qty.</th>
                    <th class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php do { ?>
                    <tr>
                        <td class="border-b py-3 pl-3 text-center"><?= $nomor++; ?></td>
                        <td class="border-b py-3 pl-3"><?= htmlspecialchars($data['product_name']) ?></td>
                        <td class="border-b py-3 pl-3">Rp. <?= number_format($data['price'], 2) ?></td>
                        <td class="border-b py-3 pl-3 text-center"><?= htmlspecialchars($data['quantity']) ?></td>
                        <td class="border-b py-3 pl-3 text-center">Rp. <?= number_format($data['total'], 2) ?></td>
                    </tr>
                <?php } while ($data = mysqli_fetch_assoc($query)); ?>
            </tbody>
        </table>

        <table class="w-full border-collapse border-spacing-0 mt-4">
            <tr>
                <td class="w-full"></td>
                <td>
                    <table class="w-full border-collapse border-spacing-0">
                        <tbody>
                            <tr>
                                <td class="bg-main p-3">
                                    <div class="whitespace-nowrap font-bold text-white">Total:</div>
                                </td>
                                <td class="bg-main p-3 text-right">
                                    <div class="whitespace-nowrap font-bold text-white">Rp. <?= number_format($Grandtotal, 2); ?></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <div class="containerBayar">
        <button id="pay-button">Bayar Sekarang</button>
    </div>
         </div>

        
   

    <?php
} else {
    echo "<p>Order tidak ditemukan.</p>";
}
?>
    <!-- Footer Section -->
    <footer class="footer">
        <div class="footer-container">
            <!-- Payment Method -->
            <div class="footer-section">
                <h3>Payment Method</h3>
                <div class="payment-icons">
                    <img src="image/mandiri.png" alt="Visa">
                    <img src="image/bca.png" alt="MasterCard">
                    <img src="image/bri.png" alt="PayPal">
                    <img src="image/bni.png" alt="PayPal">
                </div>
                <div class="payment-icons">
                    <img src="image/mastercard.png" alt="Visa">
                    <img src="image/visa.png" alt="MasterCard">
                </div>
                <div class="payment-icons">
                    <img src="image/gopay.png" alt="Visa">
                    <img src="image/shopeepay.png" alt="MasterCard">
                    <img src="image/ovo.png" alt="PayPal">
                    <img src="image/qris.png" alt="PayPal">
                </div>
                <div class="payment-icons">
                    <img src="image/indomart.jpg" alt="Visa">
                    <img src="image/alfamart.png" alt="MasterCard">

                </div>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h3>Indonesia</h3>
                <p><i class="fas fa-map-marker-alt"></i> Sumatera Barat, Padang</p>
                <p><i class="fas fa-phone"></i> +62 077-777-77</p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:support@gmail.com">support@gmail.com</a></p>
            </div>

            <!-- About Section -->
            <div class="footer-section">
                <h3>About</h3>
                <p>Di Channel ini kita akan berbagi berbagai tutorial desain, pemrograman, dan lain-lain.</p>
                <p>Silahkan subscribe, like, dan comment untuk mendukung kemajuan channel ini.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <div class="fixed">
             Supplier Company
        <span class="text-slate-300 px-2">|</span>
        info@company.com
        <span class="text-slate-300 px-2">|</span>
        +1-202-555-0106
        </div>
    </footer>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-BLSbpNLjy4wGPLyc"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function () {
        let snapToken = '<?php echo $snapToken; ?>';

        if (!snapToken) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Gagal mendapatkan token pembayaran",
                showConfirmButton: true
            });
            return;
        }

        snap.pay(snapToken, {
            onSuccess: function (result) {
                Swal.fire({
                    icon: "success",
                    title: "BERHASIL",
                    text: "Pembayaran berhasil!",
                    showConfirmButton: true,
                    timer: 2000,
                }).then(() => {
                    window.location.href = "pesanan.php";
                });
            },
            onPending: function (result) {
                Swal.fire({
                    icon: "warning",
                    title: "PENDING",
                    text: "Menunggu pembayaran Anda!",
                    showConfirmButton: true,
                    timer: 2000,
                }).then(() => {
                    window.location.href = "pesanan.php";
                });
            },
            onError: function (result) {
                Swal.fire({
                    icon: "error",
                    title: "ERROR",
                    text: "Pembayaran gagal!",
                    showConfirmButton: true,
                    timer: 2000,
                }).then(() => {
                    window.location.href = "pesanan.php";
                });
            },
            onClose: function () {
                Swal.fire({
                    icon: "warning",
                    title: "PENDING",
                    text: "Anda menutup popup tanpa menyelesaikan pembayaran.",
                    showConfirmButton: true,
                    timer: 2000,
                }).then(() => {
                    window.location.href = "pesanan.php";
                });
            }
        });
    });
</script>
