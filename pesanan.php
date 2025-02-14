<?php
session_start();

// cek apakah user telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="login"){
  header("location:login.php");
}

include 'database/koneksi.php';
          
$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result )> 0) {
  $data = mysqli_fetch_array($result);
    $_SESSION["id_user"] = $data["id_user"];

    
}
?>
<?php include 'header.php' ?>

<main>
    <div class="px-14 py-10 text-sm text-neutral-700">
        <div class="button-container">
            <button id="firstBtn" class="active">Belum Bayar</button>
            <button id="midBtn">Proses Pembayaran</button>
            <button id="lastBtn">Sudah Bayar</button>
        </div>
            <table class="w-full border-collapse border-spacing-0" id="firstTable">
                <thead>
              <tr>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">No Pesanan</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Total Pembayaran</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Status</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Tanggal Pemesanan</td>
              </tr>
            </thead>
            <tbody>
        <?php
          $id_user = $_SESSION["id_user"];
          $nomor = 1;
          $query = mysqli_query($conn, "SELECT orders.id AS order_id, transactions.gross_amount, transactions.status, transactions.created_at
                                        FROM orders 
                                        INNER JOIN transactions ON orders.id = transactions.order_id 
                                        WHERE transactions.id_user='$id_user' and status='1'");

          while ($data = mysqli_fetch_array($query)) { ?>
          
              <tbody>
                <tr>
                  <td class="border-b py-3 pl-3"><?= $nomor++ ?></td>
                  <td class="border-b py-3 pl-3"> INV-<?= $data["order_id"]; ?></td>
              <td class="border-b py-3 pl-3">Rp. <?= number_format($data['gross_amount'], 2) ?></td>
             
              
                <td class="border-b py-3 pl-3 text-center">Belum memilih pembayaran <a href="detail_bayar.php?id=<?= $data["order_id"]; ?>">Lihat</a></td>
              
              <?php
              setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian', 'id_ID'); 
              $tanggal = strtotime($data['created_at']); 
              $formatted_date = strftime('%A, %d %B %Y %H:%M', $tanggal); 
              ?>
              <td class="border-b py-3 text-center pl-3"><?= $formatted_date ?></td>

            </tr>
              </tbody>
              <?php } ?>
          </table>
            <table class="form hidden w-full border-collapse border-spacing-0" id="midTable">
                <thead>
              <tr>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">No Pesanan</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Total Pembayaran</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Status</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Tanggal Pemesanan</td>
              </tr>
            </thead>
            <tbody>
        <?php
          $id_user = $_SESSION["id_user"];
          $nomor = 1;
          $query = mysqli_query($conn, "SELECT orders.id AS order_id, transactions.gross_amount, transactions.status, transactions.created_at
                                        FROM orders 
                                        INNER JOIN transactions ON orders.id = transactions.order_id 
                                        WHERE transactions.id_user='$id_user' and status='2'");

          while ($data = mysqli_fetch_array($query)) { ?>
          
              <tbody>
                <tr>
                  <td class="border-b py-3 pl-3"><?= $nomor++ ?></td>
                  <td class="border-b py-3 pl-3"> INV-<?= $data["order_id"]; ?></td>
              <td class="border-b py-3 pl-3">Rp. <?= number_format($data['gross_amount'], 2) ?></td>
             
                <td class="border-b py-3 pl-3 text-center">Menunggu Transaksi Pembayaran <a href="pending.php?id=<?= $data["order_id"]; ?>">Bayar Disini</a></td>

              <?php
              setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian', 'id_ID'); 
              $tanggal = strtotime($data['created_at']); 
              $formatted_date = strftime('%A, %d %B %Y %H:%M', $tanggal); 
              ?>
              <td class="border-b py-3 text-center pl-3"><?= $formatted_date ?></td>

            </tr>
              </tbody>
              <?php } ?>
          </table>
            <table class="form hidden w-full border-collapse border-spacing-0" id="lastTable">
                <thead>
              <tr>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</td>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">No Pesanan</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Total Pembayaran</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Status</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Tanggal Transaksi</td>
              </tr>
            </thead>
            <tbody>
        <?php
          $id_user = $_SESSION["id_user"];
          $nomor = 1;
          $query = mysqli_query($conn, "SELECT orders.id AS order_id, transactions.gross_amount, transactions.status, transactions.created_at
                                        FROM orders 
                                        INNER JOIN transactions ON orders.id = transactions.order_id 
                                        WHERE transactions.id_user='$id_user' and status='3'");

          while ($data = mysqli_fetch_array($query)) { ?>
          
              <tbody>
                <tr>
                  <td class="border-b py-3 pl-3"><?= $nomor++ ?></td>
                  <td class="border-b py-3 pl-3"> INV-<?= $data["order_id"]; ?></td>
              <td class="border-b py-3 pl-3">Rp. <?= number_format($data['gross_amount'], 2) ?></td>
             
              
                <td class="border-b py-3 pl-3 text-center">Success <a href="invoice.php?id=<?= $data["order_id"]; ?>">Lihat Invoice</a></td>
             
              <?php
              setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian', 'id_ID'); 
              $tanggal = strtotime($data['created_at']); 
              $formatted_date = strftime('%A, %d %B %Y %H:%M', $tanggal); 
              ?>
              <td class="border-b py-3 text-center pl-3"><?= $formatted_date ?></td>

            </tr>
              </tbody>
              <?php } ?>
          </table>
    </div>
</main>

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
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
  const firstBtn = document.getElementById("firstBtn");
  const midBtn = document.getElementById("midBtn");
  const lastBtn = document.getElementById("lastBtn");
  const firstTable = document.getElementById("firstTable");
  const midTable = document.getElementById("midTable");
  const lastTable = document.getElementById("lastTable");

  // Sembunyikan form Register saat pertama kali halaman dimuat

  firstBtn.addEventListener("click", () => {
    firstTable.classList.remove("hidden");
    lastTable.classList.add("hidden");
    midTable.classList.add("hidden");

    firstBtn.classList.add("active");
    lastBtn.classList.remove("active");
    midBtn.classList.remove("active");
  });

  midBtn.addEventListener("click", () => {
    midTable.classList.remove("hidden");
    firstTable.classList.add("hidden");
    lastTable.classList.add("hidden");
    midBtn.classList.add("active");
    firstBtn.classList.remove("active");
    lastBtn.classList.remove("active");
  });
});

lastBtn.addEventListener("click", () => {
  lastTable.classList.remove("hidden");
  firstTable.classList.add("hidden");
  midTable.classList.add("hidden");

  lastBtn.classList.add("active");
  firstBtn.classList.remove("active");
  midBtn.classList.remove("active");
});

    </script>
</body>
</html>
