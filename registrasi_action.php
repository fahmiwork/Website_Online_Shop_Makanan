<?php 
include 'database/koneksi.php';

// Ambil data dari form
$email = $_POST['email'];
$password = $_POST['password'];

// Persiapkan query untuk insert data dengan prepared statements
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $password); // "ss" berarti kedua parameter adalah string

// Eksekusi query
if ($stmt->execute()) {
    // Jika berhasil, arahkan ke halaman utama
    header("location:login_form.php?success=Registrasi berhasil! Silakan login.");
} else {
    // Jika gagal, arahkan kembali ke form login
    header("location:login_form.php?error=Registrasi gagal! Coba lagi.&form=register");
}

$stmt->close();
?>
