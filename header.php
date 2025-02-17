<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Online Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <nav>
        <div class="container">
            <strong class="logo">Warung Rohman</strong>
            <!-- Menu Tengah -->
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <li><a href="pesanan.php">Pesanan</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
            <!-- Tombol Cart dan Hamburger -->
            <div class="nav-icons">
                <button class="cart-btn" id="cart">
                    <i class="bi bi-cart"></i>
                    <small class="cart-qty">0</small>
                </button>
            <div class="hamburger" id="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            </div>
        </div>
    </nav>
    <div class="cart-overlay"></div>
    <div class="cart">
        <div class="cart-header">
            <i class="bi bi-arrow-right cart-close"></i>
            <h2>Your Cart</h2>
        </div>
        <div class="cart-body"></div>
        <div class="cart-footer">
            <div>
                <strong>Total</strong>
                <span class="cart-total">0</span>
            </div>
            <button class="cart-clear">Clear Cart</button>
            <button id="checkoutBtn" class="checkout">Checkout</button>
        </div>
    </div>