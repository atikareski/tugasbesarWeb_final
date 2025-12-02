<?php
session_start();
ob_start();
include 'config.php';
include 'cek_cookie.php';

if(empty($_SESSION['id_pengguna'])){
    echo "<script>alert('Anda harus login terlebih dahulu.')</script>";
    echo "<meta http-equiv='refresh' content='0; url=Login.php'>";
    exit();
}

$id_pengguna_session = $_SESSION['id_pengguna'];
$username_session = $_SESSION['username'] ?? 'Pengguna';

$success_message = $_SESSION['success_message'] ?? '';
$error_message = $_SESSION['error_message'] ?? '';

unset($_SESSION['success_message']);
unset($_SESSION['error_message']);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (mysqli_connect_errno()) {
        $_SESSION['error_message'] = "Koneksi database gagal.";
    } else {
        $nama_lengkap_input = $_POST['nama'] ?? '';
        $nomor_telepon_input = $_POST['telpon'] ?? '';
        $kendaraan_dipilih_input = $_POST['vehicle'] ?? '';
        $lama_sewa_input = $_POST['rental-period'] ?? '';

        if (empty($nama_lengkap_input) || empty($nomor_telepon_input) || empty($kendaraan_dipilih_input) || empty($lama_sewa_input) || $lama_sewa_input < 1) {
            $_SESSION['error_message'] = "Semua field wajib diisi dan lama sewa minimal 1 hari.";
        } else {
            $nama_lengkap_safe = mysqli_real_escape_string($conn, $nama_lengkap_input);
            $nomor_telepon_safe = mysqli_real_escape_string($conn, $nomor_telepon_input);
            $kendaraan_dipilih_safe = mysqli_real_escape_string($conn, $kendaraan_dipilih_input);
            $lama_sewa_safe = (int)$lama_sewa_input;

            $sql_insert = "
                INSERT INTO pemesanan (
                    id_pengguna, 
                    nama_lengkap_pemesan, 
                    nomor_telepon, 
                    kendaraan_dipilih, 
                    lama_sewa
                ) VALUES (
                    '$id_pengguna_session', 
                    '$nama_lengkap_safe', 
                    '$nomor_telepon_safe', 
                    '$kendaraan_dipilih_safe', 
                    '$lama_sewa_safe'
                )
            ";

            if (mysqli_query($conn, $sql_insert)) {
                echo "<script>alert('Proses pemesanan selesai. Konfirmasi Pembayaran akan Dikirimkan Melalui WhatsApp.')</script>";
                echo "<meta http-equiv='refresh' content='0; url=userPage.php'>";
                $_SESSION['success_message'] = "Pemesanan berhasil!";
                exit();
            } else {
                $_SESSION['error_message'] = "Pemesanan gagal: " . mysqli_error($conn);
            }
        }
    }

    echo "<meta http-equiv='refresh' content='0; url=FormPesan.php'>";
    exit();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <title>Form Pemesanan Kendaraan | DriveNow</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand text-white m-0 fw-bold">DriveNow</h1>
            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item mx-3"><a class="nav-link text-white" href="LandingPage.php">Home</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="DaftarKendaraan.php">Daftar Kendaraan</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="userPage.php"><?php echo $_SESSION['username'] ?></a></li>
            </ul>
        </div>
    </nav>
    <header class="judul-form text-left my-10">
        <h2>Form Pemesanan Kendaraan</h2>
        <p>Isi data berikut untuk memesan kendaraan pilihanmu.</p>
    </header>
    <form class="form-container" method="post" style="max-width: 600px;" action="FormPesan.php">
            <?php if (!empty($success_message)): ?>
                <p style="text-align: center; font-weight: bold;"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; font-weight: bold;"><?php echo $error_message; ?></p>
            <?php endif; ?>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Lengkap">
        </div>
        <div class="mb-3">
            <label for="telpon" class="form-label">Nomor Telepon (WA):</label>
            <input type="tel" id="telpon" name="telpon" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}" class="form-control" placeholder="0812-3456-7890">
        </div>
        <div class="mb-3">
            <label for="vehicle" class="form-label">Pilih Kendaraan:</label>
            <select id="vehicle" name="vehicle" class="form-select">
                <option value="" disabled selected>Pilih Kendaraan</option>
                <option value="Toyota-Alphard">Toyota Alphard</option>
                <option value="Toyota-Avanza">Toyota Avanza</option>
                <option value="Yamaha-NMAX">Yamaha NMAX</option>
                <option value="Honda-Vario">Honda Vario</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="rental-period" class="form-label">Lama Sewa (hari):</label>
            <input type="number" id="rental-period" name="rental-period" class="form-control" min="1" placeholder="0">
        </div>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-warning text-white px-4">Pesan</button>
            <button type="reset" class="btn btn-secondary px-4">Reset</button>
        </div>
    </form>
    <footer>
        <p class="m-0">&copy; 2025 DriveNow. All rights reserved.</p>
    </footer>
</body>
</html>
