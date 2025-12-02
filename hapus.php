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

if (isset($_GET['id'])) {
    $id_pemesanan_to_delete = (int)$_GET['id'];
    $id_pengguna_saat_ini = $_SESSION['id_pengguna'];

    $sql_delete = "
        DELETE FROM pemesanan 
        WHERE id_pemesanan = '$id_pemesanan_to_delete' 
        AND id_pengguna = '$id_pengguna_saat_ini'
    ";

    if (mysqli_query($conn, $sql_delete)) {
        $_SESSION['success_message'] = "Pesanan berhasil dihapus.";
    } else {
        $_SESSION['error_message'] = "Gagal menghapus pesanan: " . mysqli_error($conn);
    }

} else {
    $_SESSION['error_message'] = "ID Pesanan tidak ditemukan.";
}

header('location: userPage.php');
exit();
}
?>