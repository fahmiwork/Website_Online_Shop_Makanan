<?php 
include 'database/koneksi.php';
$email = $_POST['email'];
$password = $_POST['password'];

$login = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
$cek = mysqli_num_rows($login);

if ($cek > 0) {
    session_start();
    $_SESSION['email'] = $email;
    $_SESSION['status'] = "login";
    header("location:index.php");
} else {
    header("location:login_form.php?error=Email atau password salah!&email=" . urlencode($email) . "&password=" . urlencode($password));
}
?>
