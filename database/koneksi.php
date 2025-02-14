<?php

// Konfigurasi database
$host = "localhost";
$user = "root";
$password = "";
$database = "pemesanan_makanan";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["message" => "Database connection failed: " . $conn->connect_error]));
}

?>