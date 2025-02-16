<?php
session_start();

// Cek apakah user telah login, jika belum login maka diarahkan ke halaman login
if ($_SESSION['status'] != "login") {
    header("location:login.php");
}

// Konfigurasi database
include 'database/koneksi.php';

$email = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_array($result);
    $_SESSION["id_user"] = $data["id_user"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="invoice/style.css" type="text/css" media="all" />
</head>

<body>
    <div class="py-4">
      <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <div>
                  <img src="https://menkoff.com/assets/brand-sample.png" class="h-12" />
                </div>
              </td>
                  <?php
        $id = $_GET['id'] ?? ''; // Ambil ID dari URL

        // Inisialisasi variabel $total
        $total = 0;
        $nomor = 1;

        $query3 = mysqli_query($conn, "SELECT * FROM transactions WHERE order_id = '$id'");
        if (mysqli_num_rows($query3) > 0): ?>
                <?php while ($datas = mysqli_fetch_assoc($query3)): 

              ?>
              <td class="align-top">
                <div class="text-sm">
                  <table class="border-collapse border-spacing-0">
                    <tbody>
                      <tr>
                        <td class="border-r pr-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Date</p>
                            <p class="whitespace-nowrap font-bold text-main text-right"><script type='text/javascript'>
						
						var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
						var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
						var date = new Date();
						var day = date.getDate();
						var month = date.getMonth();
						var thisDay = date.getDay(),
							thisDay = myDays[thisDay];
						var yy = date.getYear();
						var year = (yy < 1000) ? yy + 1900 : yy;
						document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);		
						//-->
						</script></p>
                          </div>
                        </td>
                        <td class="pl-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Invoice #</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">INV-<?= $datas['order_id']?></p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-slate-100 px-14 py-6 text-sm">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">Rohman Shop</p>
                  <p>Phone: 23456789</p>
                  <p>Email: 23456789</p>
                  <p>Jl. Raya Kalisalak - Limpung</p>
                  <p>Batang, 51271</p>
                  <p>Indonesia</p>
                </div>
              </td>
              
              <td class="w-1/2 align-top text-right">
                <div class="text-sm text-neutral-600">

                  <p class="font-bold"><?= $datas['first_name']?></p>
                  <p>Phone: <?= $datas['phone']?></p>
                  <p>Email: <?= $datas['email']?></p>
                  <p><?= $datas['address']?></p>
                  <p>Paradise, 43325</p>
                  <p>Indonesia</p>
                  <?php endwhile; ?>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        
          <table class="w-full border-collapse border-spacing-0">
            <thead>
              <tr>
                <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</td>
                <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Product details</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Price</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Qty.</td>
                <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Subtotal</td>
              </tr>
            </thead>
            <tbody>
              <?php
        $id = $_GET['id'] ?? ''; // Ambil ID dari URL
        if (!$id) {
            echo "<p>Order ID tidak ditemukan.</p>";
            exit;
        }

        // Inisialisasi variabel $total
        $total = 0;
        $nomor = 1;

$query = mysqli_query($conn, "SELECT orders.id AS order_id, orders.total, 
        order_items.quantity, order_items.product_name, order_items.price, 
        transactions.first_name, transactions.email, 
        transactions.phone, transactions.address, transactions.bank_name, 
        transactions.payment_number, transactions.notes, transactions.created_at
                                      FROM orders 
                                      INNER JOIN order_items ON orders.id = order_items.order_id 
                                      INNER JOIN transactions ON orders.id = transactions.order_id 
                                      WHERE orders.id = '$id'");
        if (mysqli_num_rows($query) > 0): ?>
                <?php while ($data = mysqli_fetch_assoc($query)): 
                  $Subtotal = $data['price'] * $data['quantity'];
                  $total += $Subtotal; // Tambahkan subtotal ke total

              ?>
                    <?php
                        setlocale(LC_TIME, 'id_ID.utf8', 'Indonesian', 'id_ID'); 
                        $tanggal = strtotime($data['created_at']); 
                        $formatted_date = strftime('%A, %d %B %Y %H:%M', $tanggal); 
                        $bank_name = $data['bank_name'];
                        $payment_number = $data['payment_number'];
                        $notes = $data['notes'];
                    ?>
              <tr>
                <td class="border-b py-3 pl-3"><?= $nomor++; ?></td>
                <td class="border-b py-3 pl-2"><?= htmlspecialchars($data['product_name']) ?></td>
                <td class="border-b py-3 pl-2 text-right">Rp. <?= number_format($data['price'], 2) ?></td>
                <td class="border-b py-3 pl-2 text-center"><?= htmlspecialchars($data['quantity']) ?></td>
                <td class="border-b py-3 pl-2 text-right">Rp. <?= number_format($Subtotal, 2) ?></td>
              </tr>
              <?php endwhile; ?>
                      <?php else: ?>
          <p>Order tidak ditemukan.</p>
        <?php endif; ?>
              <tr>
                <td colspan="7">
                  <table class="w-full border-collapse border-spacing-0">
                    <tbody>
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
                                  <div class="whitespace-nowrap font-bold text-white">Rp. <?= number_format($total, 2); ?></div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        <?php else: ?>
          <p>Order tidak ditemukan.</p>
        <?php endif; ?>
      </div>

      <div class="px-14 text-sm text-neutral-700">
        <p class="text-main font-bold">PAYMENT DETAILS</p>


        <p>Date: <?= $formatted_date ?></p>
        <p>Bank Name: <?= $bank_name ?></p>
        <p>Payment Number: <?= $payment_number ?></p>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <p class="text-main font-bold">Notes</p>
        <p class="italic"><?= $notes ?></p>
      </div>
    </div>
      <div class="button-print">
  <button onclick="printDiv('.py-4')">Print</button>
</div>
  </div>

</body>

</html>
<script>
function printDiv(className) {
    let printContents = document.querySelector(className).innerHTML;
    let originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // Reload agar tampilan kembali normal setelah print
}
</script>