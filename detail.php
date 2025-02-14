<?php
session_start();

// cek apakah user telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="login"){
  header("location:login.php");
}
        // Konfigurasi database
include 'database/koneksi.php';
$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result )> 0) {
  $data = mysqli_fetch_array($result);
    $_SESSION["id_user"] = $data["id_user"];

    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Checkout</h1>
        </div>
    </header>
    <main>
        <div class="container">
<?php
            $id = $_GET['id'] ?? ''; // Tambahkan validasi jika ID tidak ada
            if (!$id) {
                echo "<p>Order ID tidak ditemukan.</p>";
                exit;
            }

            $query = mysqli_query($conn, "SELECT orders.id AS order_id, order_items.quantity, order_items.product_name, order_items.price
                                          FROM orders 
                                          INNER JOIN order_items ON orders.id = order_items.order_id 
                                          WHERE orders.id = '$id'");

            if (mysqli_num_rows($query) > 0): ?>
                
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($data = mysqli_fetch_assoc($query)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($data['product_name']) ?></td>
                                    <td><?= htmlspecialchars($data['quantity']) ?></td>
                                    <td><?= number_format($data['price'], 2) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                
            <?php else: ?>
                <p>Order tidak ditemukan.</p>
            <?php endif; ?>
           
        </div>
    </main>

</body>
</html>