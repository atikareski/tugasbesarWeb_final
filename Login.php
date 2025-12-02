<?php
session_start();

include 'config.php'; 
include 'cek_cookie.php';

$error_message = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (mysqli_connect_errno()) {
        $_SESSION['login_error'] = "Koneksi database gagal.";
        header('location: Login.php');
        exit();
    } 

    $username_input = $_POST['username'] ?? '';
    $password_input = $_POST['password'] ?? '';

    // Amankan input
    $username_safe = mysqli_real_escape_string($conn, $username_input);

    $sql = "SELECT id_pengguna, username, password_hash FROM pengguna WHERE username = '$username_safe'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password_input, $user['password_hash'])) {

            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];

            $expire = time() + (60 * 5); 

            setcookie('user_login', $user['username'], $expire, "/");

            setcookie('user_id', $user['id_pengguna'], $expire, "/");

            echo "<script>alert('Login berhasil! Selamat datang, " . $user['username'] . "');</script>";
            echo "<meta http-equiv='refresh' content='0; url=userPage.php'>";
            exit();

        } else {
            $_SESSION['login_error'] = "Password salah. Silakan coba lagi.";
        }
    } else {
        $_SESSION['login_error'] = "Username tidak terdaftar. Silakan coba lagi.";
    }

    header('location: Login.php');
    exit();
}

mysqli_close($conn); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <title>Login | DriveNow</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand text-white m-0 fw-bold">DriveNow</h1>
            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item mx-3"><a class="nav-link text-white" href="LandingPage.php">Home</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="DaftarKendaraan.php">Daftar Kendaraan</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="signin.php">Sign In</a></li>
            </ul>
        </div>
    </nav>
    <div class="login-container shadow-lg">
        <h2 class="text-center text-white mb-6">Login</h2>
        
        <?php if (!empty($error_message)): ?>
            <p style="color: red; text-align: center; font-weight: bold;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        
        <form action="Login.php" method="post" class="d-flex flex-column align-items-center">
            <input type="text" class="form-control mb-4" id="username" name="username" placeholder="Username">
            <input type="password" class="form-control mb-4" id="password" name="password" placeholder="Password">
            <p>Belum punya akun ? <a href="signin.php">Sign In</a></p>
            <button type="submit" class="btn btn-warning text-white px-4 py-8">Login</button>
        </form>
    </div>
    <footer>
        <p class="m-0">&copy; 2025 DriveNow. All rights reserved.</p>
    </footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.querySelector('.login-container form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');

        loginForm.addEventListener('submit', function(event) {
        if (usernameInput.value.trim() === '' || passwordInput.value.trim() === '') {
            event.preventDefault(); // Mencegah pengiriman form
            alert('Username dan Password tidak boleh kosong!');
        }
        })
    });
</script>
</body>
</html>
