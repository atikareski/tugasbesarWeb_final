<?php
session_start();
ob_start();

if(empty($_SESSION['id_pengguna'])){
    echo "<script>alert('Anda harus login terlebih dahulu')</script>";
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
}else {

include 'config.php';
include 'cek_cookie.php';

$id_pengguna_session = $_SESSION['id_pengguna'];
$username_session = $_SESSION['username'] ?? 'Pengguna';
$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);
$success_message = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

$pesanan_data = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_pemesanan_update = $_POST['id_pemesanan'] ?? null; 

    if ($id_pemesanan_update) {

        $nama_lengkap_input = $_POST['nama'] ?? '';           
        $nomor_telepon_input = $_POST['telpon'] ?? '';        
        $kendaraan_dipilih_input = $_POST['vehicle'] ?? '';   
        $lama_sewa_input = $_POST['rental-period'] ?? '';

        if (empty($nama_lengkap_input) || empty($nomor_telepon_input) || empty($kendaraan_dipilih_input) || empty($lama_sewa_input) || $lama_sewa_input < 1) {
            $_SESSION['error_message'] = "Semua field wajib diisi.";
        } else {
            $nama_lengkap_safe = mysqli_real_escape_string($conn, $nama_lengkap_input);
            $nomor_telepon_safe = mysqli_real_escape_string($conn, $nomor_telepon_input);
            $kendaraan_dipilih_safe = mysqli_real_escape_string($conn, $kendaraan_dipilih_input);
            $lama_sewa_safe = (int)$lama_sewa_input;

            $sql_update = "
                UPDATE pemesanan SET
                    nama_lengkap_pemesan = '$nama_lengkap_safe',
                    nomor_telepon = '$nomor_telepon_safe',
                    kendaraan_dipilih = '$kendaraan_dipilih_safe',
                    lama_sewa = '$lama_sewa_safe'
                WHERE id_pemesanan = '$id_pemesanan_update' AND id_pengguna = '$id_pengguna_session'
            ";

            if (mysqli_query($conn, $sql_update)) {
                $_SESSION['success_message'] = "Pesanan berhasil diupdate!";
                header('location: userPage.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Gagal mengupdate pesanan: " . mysqli_error($conn);
            }
        }
    }
}

if (isset($_GET['id'])) {
    $id_pemesanan_edit = (int)$_GET['id'];
    
    $sql_select = "
        SELECT * FROM pemesanan 
        WHERE id_pemesanan = '$id_pemesanan_edit' AND id_pengguna = '$id_pengguna_session'
    ";
    $result = mysqli_query($conn, $sql_select);

    if (mysqli_num_rows($result) == 1) {
        $pesanan_data = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['error_message'] = "Pesanan tidak ditemukan atau Anda tidak memiliki akses.";
        header('location: userPage.php');
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] != "POST") {
    $_SESSION['error_message'] = "Akses tidak valid.";
    header('location: userPage.php');
    exit();
}

$id_pemesanan_form = $pesanan_data['id_pemesanan'] ?? $_POST['id_pemesanan'] ?? null;

$nama_default = $pesanan_data['nama_lengkap_pemesan'] ?? '';
$telpon_default = $pesanan_data['nomor_telepon'] ?? '';
$vehicle_default = $pesanan_data['kendaraan_dipilih'] ?? '';
$rental_period_default = $pesanan_data['lama_sewa'] ?? 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['error_message'])) {
    $nama_default = $_POST['nama'] ?? $nama_default;
    $telpon_default = $_POST['telpon'] ?? $telpon_default;
    $vehicle_default = $_POST['vehicle'] ?? $vehicle_default;
    $rental_period_default = $_POST['rental-period'] ?? $rental_period_default;
}

mysqli_close($conn);
}
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
        <h2>Form Edit Pesanan #<?php echo $id_pemesanan_form; ?></h2>
        <p>Ubah data pemesanan di bawah ini.</p>
    </header>
    <form class="form-container" method="post" style="max-width: 600px;" action="edit.php">
            <?php if (!empty($success_message)): ?>
                <p style="text-align: center; font-weight: bold;"><?php echo $success_message; ?></p>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center; font-weight: bold;"><?php echo $error_message; ?></p>
            <?php endif; ?>
        <input type="hidden" name="id_pemesanan" value="<?php echo htmlspecialchars($id_pemesanan_form); ?>">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" class="form-control" value="<?php echo htmlspecialchars($nama_default); ?>" placeholder="Masukkan Nama Lengkap" required>
        </div>
        <div class="mb-3">
            <label for="telpon" class="form-label">Nomor Telepon (WA):</label>
            <input type="tel" id="telpon" name="telpon" pattern="[0-9]{4}-[0-9]{4}-[0-9]{4}" class="form-control" value="<?php echo htmlspecialchars($telpon_default); ?>" placeholder="0812-3456-7890" required>
        </div>
        <div class="mb-3">
            <label for="vehicle" class="form-label">Pilih Kendaraan:</label>
            <select id="vehicle" name="vehicle" class="form-select" required>
                <option value="" disabled <?php if (empty($vehicle_default)) echo 'selected'; ?>>Pilih Kendaraan</option>
                <option value="Toyota-Alphard" <?php if ($vehicle_default == 'Toyota-Alphard') echo 'selected'; ?>>Toyota Alphard</option>
                <option value="Toyota-Avanza" <?php if ($vehicle_default == 'Toyota-Avanza') echo 'selected'; ?>>Toyota Avanza</option>
                <option value="Yamaha-NMAX" <?php if ($vehicle_default == 'Yamaha-NMAX') echo 'selected'; ?>>Yamaha NMAX</option>
                <option value="Honda-Vario" <?php if ($vehicle_default == 'Honda-Vario') echo 'selected'; ?>>Honda Vario</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="rental-period" class="form-label">Lama Sewa (hari):</label>
            <input type="number" id="rental-period" name="rental-period" class="form-control" min="1" value="<?php echo htmlspecialchars($rental_period_default); ?>" placeholder="0" required>
        </div>
        <div class="d-flex justify-content-center gap-3 mt-4">
            <button type="submit" class="btn btn-success text-white px-4">Save</button>
            <button type="button" id="cancel-button" class="btn btn-secondary px-4">Cancel</button>
        </div>
    </form>
    <footer>
        <p class="m-0">&copy; 2025 DriveNow. All rights reserved.</p>
    </footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelButton = document.getElementById('cancel-button');

        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                
                window.location.href = 'userPage.php';
            });
        }
    });
</script>
</body>
</html>
