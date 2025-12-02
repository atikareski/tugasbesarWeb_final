<?php
    session_start();
    ob_start();

    if(empty($_SESSION['id_pengguna'])){
    echo "<script>alert('Anda harus login terlebih dahulu')</script>";
    echo "<meta http-equiv='refresh' content='0; url=login.php'>";
    } else {
        include 'config.php';
        include 'cek_cookie.php';

        $success_message_userpage = $_SESSION['success_message'] ?? '';
        $error_message_userpage = $_SESSION['error_message'] ?? '';

        unset($_SESSION['success_message']);
        unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <title>User | DriveNow</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <h1 class="navbar-brand text-white m-0 fw-bold">DriveNow</h1>
            <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item mx-3"><a class="nav-link text-white" href="LandingPage.php">Home</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="DaftarKendaraan.php">Daftar Kendaraan</a></li>
                <li class="nav-item mx-3"><a class="nav-link text-white" href="#"><?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
            </ul>
        </div>
    </nav>
    <div class="judul-daftar container mt-65 text-left">
        <?php echo "<h2>Hello, " . $_SESSION['username'] . "</h2>"; ?>
    </div>
    <div class="judul-daftar container mt-65 text-left">
        <h4>Daftar Pesanan</h4>
    </div>
    <div class="tabel-daftar">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pemesan</th>
                        <th>Nomor Telepon</th>
                        <th>Kendaraan</th>
                        <th>Lama Sewa (Hari)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        
                            $sql = "SELECT * FROM pemesanan WHERE id_pengguna = '".$_SESSION['id_pengguna']."'";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id_pemesanan'] . "</td>";
                                    echo "<td>" . $row['nama_lengkap_pemesan'] . "</td>";
                                    echo "<td>" . $row['nomor_telepon'] . "</td>";
                                    echo "<td>" . $row['kendaraan_dipilih'] . "</td>";
                                    echo "<td>" . $row['lama_sewa'] . "</td>";
                                    echo "<td><a style=\"background-color: red;\" href='hapus.php?id=" . $row['id_pemesanan'] . "' onclick=\"return confirm('Yakin ingin menghapus pesanan ".$row['id_pemesanan']."?')\">Delete</a>
                                    <a href='edit.php?id=" . $row['id_pemesanan'] . "'>Edit</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>Tidak ada data pemesanan.</td></tr>";
                            }
                        ?>
                </tbody>
            </table>
    </div>
    <div class="pesan-sekarang text-center py-4">
            <a href="FormPesan.php" class="btn btn-warning text-white px-4 py-3 rounded-pill">Pesan Sekarang</a>
    </div>
    <div class="userPage-button ">
            <a href="logout.php" class="btn btn-danger text-white px-4 py-2 rounded-pill">Logout</a>
    </div>
    <footer>
        <p class="m-0">&copy; 2025 DriveNow. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
    }
?>