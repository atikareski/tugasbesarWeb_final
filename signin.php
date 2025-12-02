<?php

session_start();
ob_start();

include 'config.php';

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';

unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

if (mysqli_connect_errno()) {
    $error_message = "Koneksi database gagal.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username_input = $_POST['username'] ?? '';
    $nama_lengkap_input = $_POST['nama_lengkap'] ?? '';
    $password_input = $_POST['password'] ?? ''; 
    $confirm_password_input = $_POST['confirm_password'] ?? '';

    if (empty($username_input) || empty($nama_lengkap_input) || empty($password_input) || empty($confirm_password_input)) {
        $_SESSION['error_message'] = "Semua input harus diisi!";
    } elseif ($password_input !== $confirm_password_input) {
        $_SESSION['error_message'] = "Konfirmasi password salah!";
    } else {
        $username_safe = mysqli_real_escape_string($conn, $username_input);
        $nama_lengkap_safe = mysqli_real_escape_string($conn, $nama_lengkap_input);

        $check_sql = "SELECT username FROM pengguna WHERE username = '$username_safe'";
        if (mysqli_num_rows(mysqli_query($conn, $check_sql)) > 0) {
            $_SESSION['error_message'] = "Username sudah digunakan.";
        } else {
            $hashed_password = password_hash($password_input, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO pengguna (username, password_hash, nama_lengkap) 
                           VALUES ('$username_safe', '$hashed_password', '$nama_lengkap_safe')";

            if (mysqli_query($conn, $sql_insert)) {
                $_SESSION['success_message'] = "Pendaftaran berhasil!";
            } else {
                $_SESSION['error_message'] = "Gagal mendaftar: " . mysqli_error($conn);
            }
        }
    }

    header('location: signin.php');
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
    <title>Sign In | DriveNow</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand text-white m-0 fw-bold">DriveNow</h1>
            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item mx-3"><a class="nav-link text-white" href="LandingPage.php">Home</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="DaftarKendaraan.php">Daftar Kendaraan</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="Login.php">Login</a></li>
            </ul>
        </div>
    </nav>
    <div class="login-container shadow-lg" style="max-width: 450px;">
        <h2 class="text-center text-white mb-4">Sign In</h2>
        
        <?php if (!empty($success_message)): ?>
            <p style="text-align: center;"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="signin.php" method="post" class="d-flex flex-column align-items-center">
            <input type="text" class="form-control mb-3" id="username" name="username" placeholder="Username">
            <input type="text" class="form-control mb-3" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap">
            <input type="password" class="form-control mb-3" id="password" name="password" placeholder="Password">
            <input type="password" class="form-control mb-4" id="confirm_password" name="confirm_password" placeholder="Konfirmasi Password">
            <p>Sudah punya akun? <a href="Login.php">Login</a></p>
            <button type="submit" class="btn btn-warning text-white px-4">Daftar</button>
        </form>
    </div>
    <footer>
        <p class="m-0">&copy; 2025 DriveNow. All rights reserved.</p>
    </footer>
</body>
</html>
