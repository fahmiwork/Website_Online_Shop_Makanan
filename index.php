<?php include 'header.php' ?>
    <main>
        <div class="container">
            <div class="products">
                <?php
                include 'database/koneksi.php';
                $query = mysqli_query($conn,"SELECT * FROM products");

                while ($q = mysqli_fetch_array($query)) { ?>
                <div class="product">
                    <a href="#">
                        <img src="<?= $q['image'] ?>" alt="Product"/>
                    </a>
                    <h3><?= $q['name'] ?></h3>
                   <div class="priceColumn">Rp.<p> <?= $q['price'] ?></p></div>
                    <button class="add-to-cart" data-id="<?= $q['id_products'] ?>">Add to Cart</button>
                </div>
                <?php } ?>
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
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
