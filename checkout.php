<?php
session_start();

// cek apakah user telah login, jika belum login maka di alihkan ke halaman login
if($_SESSION['status'] !="login"){
  header("location:login_form.php");
}
        // Konfigurasi database
include 'database/koneksi.php';
$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result )> 0) {
  $data = mysqli_fetch_array($result);
    $id = $data["id_user"];
}
include 'header.php';
?>
    <header>
      <div class="container">
        <h1>Checkout</h1>
      </div>
    </header>
    <main>
      <div class="container">
        <div class="container-checkout">
          <div class="checkout-wrapper">
            <form action="" method="POST">
              <input type="hidden" name="id_user" value="<?= $id ?>">
              <div class="checkout-items">
                
          </div>
          <div class="checkout-summary">
              <h2>Order Summary</h2>
            <p><strong>Total:</strong> <span class="checkout-total">0</span></p>
                  </div>
        </div>
                  
          <div class="checkout-input">
            
            <p>Nama: <input type="text" name="first_name" required></p>
            <p>Email: <input type="email" name="email" required></p>
            <p>Nomor Telepon: <input type="number" name="phone" required></p>
            <p>Alamat: <input type="text" name="address" required></p>
            Catatan: <p><textarea type="text" name="notes" required></textarea></p>
            <input type="hidden" value="1" name="status">
            <button type="button" id="placeOrderBtn" class="place-order">Place Order</button>
      
        </form>
          </div>
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
    <script src="checkout.js"></script>
    <script src="script.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-BLSbpNLjy4wGPLyc"></script>
  </body>
</html>