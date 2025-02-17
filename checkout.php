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
            <div class="checkout-items"> 
            </div>
            <div class="checkout-summary">
              <h2>Order Summary</h2>
              <p><strong>Total:</strong> <span class="checkout-total">0</span></p>
            </div>
          </div>       
        <div class="checkout-input">
            <input type="hidden" name="id_user" value="<?= $id ?>">
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
<?php include 'footer.php' ?>
<script src="checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-BLSbpNLjy4wGPLyc"></script>
