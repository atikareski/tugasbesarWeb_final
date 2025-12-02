<?php
    session_start();
    ob_start();
    include 'config.php';
    include 'cek_cookie.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <title>Landing Page | DriveNow</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand text-white m-0 fw-bold">DriveNow</h1>
            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item mx-3"><a class="nav-link text-white" href="#container">Home</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="DaftarKendaraan.php">Daftar Kendaraan</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item mx-3"><a class="nav-link text-white" href="userPage.php">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a></li>
                <?php else: ?>
                    <li class="nav-item mx-3"><a class="nav-link text-white" href="Login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="container-page d-flex flex-column flex-md-row align-items-center py-10">
        <p class="text-white fs-5 me-md-5">
            Welcome to DriveNow<br>
            Layanan rental kendaraan untuk perusahaan maupun personal yang mengutamakan kenyamanan serta keamanan pelanggan.
        </p>
        <img src="src/mobil-alphard.png" alt="mobil-alphard" class="img-fluid mt-4 mt-md-0" style="max-width: 400px;">
    </div>
    <div class="pesan-sekarang text-center py-4">
        <a href="FormPesan.php" class="btn btn-warning text-white px-4 py-3 rounded-pill">Pesan Sekarang</a>
    </div>
    <div class="daftar-kendaraan container text-center rounded-4 py-4 my-5">
        <div class="row justify-content-center">
            <div class="col-md-4 col-6 mb-3">
                <img src="src/motor-nmax.png" alt="motor-nmax" class="img-fluid" width="270">
            </div>
            <div class="col-md-4 col-6 mb-3">
                <img src="src/mobil-avanza.png" alt="mobil-avanza" class="img-fluid" width="300">
            </div>
        </div>
        <div class="link-daftar-kendaraan mt-3">
            <a href="DaftarKendaraan.php">Lihat Daftar Kendaraan</a>
        </div>
    </div>
    <div class="lokasi text-center py-4">
        <h2>Lokasi Kami</h2>
        <p>&#128205; Jl. Tamangapa Raya 3, Makassar</p>
    </div>
    <footer>
        <p class="m-0">&copy; 2025 DriveNow. All rights reserved.</p>
    </footer>
</body>
</html>
