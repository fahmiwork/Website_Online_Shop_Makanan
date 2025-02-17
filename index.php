<?php include 'header.php' ?>
    <main>
        <div class="container">
            <div class="products">
                <?php
                require_once 'database/koneksi.php';
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
<?php include 'footer.php' ?>
<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>