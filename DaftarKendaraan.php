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
    <title>Daftar Kendaraan | DriveNow</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand text-white m-0 fw-bold">DriveNow</h1>
            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item mx-3"><a class="nav-link text-white" href="LandingPage.php">Home</a></li>
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
    <div class="judul-daftar container mt-65 text-left">
        <h2>Daftar Kendaraan yang Tersedia</h2>
    </div>
    <div class="tabel-daftar">
            <table>
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Jenis Kendaraan</th>
                        <th>Nama Kendaraan</th>
                        <th>Harga Sewa</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><img src="src/mobil-alphard.png" alt="mobil-alphard" width="100"></td>
                        <td>Mobil</td>
                        <td>Toyota Alphard</td>
                        <td>Rp 1.500.000 / hari</td>
                    </tr>
                    <tr>
                        <td><img src="src/mobil-avanza.png" alt="mobil-avanza" width="100"></td>
                        <td>Mobil</td>
                        <td>Toyota Avanza</td>
                        <td>Rp 500.000 / hari</td>
                    </tr>
                    <tr>
                        <td><img src="src/motor-nmax.png" alt="motor-nmax" width="100"></td>
                        <td>Motor</td>
                        <td>Yamaha NMAX</td>
                        <td>Rp 150.000 / hari</td>
                    </tr>
                    <tr>
                        <td><img src="src/motor1.png" alt="motor-vario" width="100"></td>
                        <td>Motor</td>
                        <td>Honda Vario</td>
                        <td>Rp 100.000 / hari</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="pesan-sekarang text-center py-4">
            <a href="FormPesan.php" class="btn btn-warning text-white px-4 py-3 rounded-pill">Pesan Sekarang</a>
        </div>
    <footer>
        <p class="m-0">&copy; 2025 DriveNow. All rights reserved.</p>
    </footer>
</body>
</html>
