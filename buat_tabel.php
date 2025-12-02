<?php

session_start();
ob_start();


if(empty($_SESSION['username'])){
    echo "<script>alert('Anda harus login terlebih dahulu')</script>";
    echo "<meta http-equiv='refresh' content='0; url=Login.php'>";
} else {
    
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "db_driveNow";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if(mysqli_connect_errno()){
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $sql_create_tables = "
    CREATE TABLE IF NOT EXISTS pengguna (
        id_pengguna INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        nama_lengkap VARCHAR(100)
    );
    CREATE TABLE IF NOT EXISTS pemesanan (
        id_pemesanan INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
        id_pengguna INT(11) NOT NULL,
        nama_lengkap_pemesan VARCHAR(100) NOT NULL,
        nomor_telepon VARCHAR(20) NOT NULL,
        kendaraan_dipilih VARCHAR(50) NOT NULL,
        lama_sewa INT(5) NOT NULL,
        tanggal_pemesanan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_pengguna) REFERENCES pengguna(id_pengguna)
    );
    ";

    if (mysqli_multi_query($conn, $sql_create_tables)) {
        do {
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_more_results($conn) && mysqli_next_result($conn));

        echo "Tabel pengguna dan pemesanan berhasil dibuat atau sudah ada.";
    } else {
        echo "Gagal membuat tabel: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>