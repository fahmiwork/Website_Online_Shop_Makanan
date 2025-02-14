<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register Form</title>
    <link rel="stylesheet" href="css/stylelogin.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="button-container">
                <button id="loginBtn" class="active">Login</button>
                <button id="registerBtn">Register</button>
            </div>
          
            <form method="post" action="login_action.php" id="loginForm" class="form">
                <img src="image/logo.jpg" alt="" srcset="">
                    <?php if (isset($_GET['error'])): ?>
        <p style="color: red; text-align: center;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>
                <input type="email" name="email" placeholder="Email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>" required>
                <input type="password" name="password" placeholder="Password" value="<?php echo isset($_GET['password']) ? $_GET['password'] : ''; ?>" required>
                <button type="submit">Login</button>
            </form>
            <form method="post" action="registrasi_action.php" id="registerForm" class="form hidden">
                <img src="image/logo.jpg" alt="" srcset="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const loginBtn = document.getElementById('loginBtn');
        const registerBtn = document.getElementById('registerBtn');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginBtn.addEventListener('click', () => {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            loginBtn.classList.add('active');
            registerBtn.classList.remove('active');
        });

        registerBtn.addEventListener('click', () => {
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
            registerBtn.classList.add('active');
            loginBtn.classList.remove('active');
        });

          // Cek URL untuk notifikasi SweetAlert
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: urlParams.get('success'),
                confirmButtonColor: '#3085d6',
            });
        }
        if (urlParams.has('error')) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: urlParams.get('error'),
                confirmButtonColor: '#d33',
            });
        }

        // Hapus parameter dari URL setelah notifikasi muncul
        if (urlParams.has('success') || urlParams.has('error')) {
            history.replaceState({}, document.title, window.location.pathname);
        }
        
        //bersihkan eror ketika di reload
        if (window.location.search.includes("error")) {
        history.replaceState({}, document.title, window.location.pathname);
    }
    </script>
</body>
</html>