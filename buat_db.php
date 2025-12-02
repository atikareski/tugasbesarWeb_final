<?php

session_start();
ob_start();
include 'cek_cookie.php';

if(empty($_SESSION['id_pengguna'])){
echo "<script>alert('Anda harus login terlebih dahulu')</script>";
echo "<meta http-equiv='refresh' content='0; url=login.php'>";
} else {

 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $dbname = "db_driveNow";
 //Membuat koneksi
 $conn = mysqli_connect($dbhost,$dbuser,$dbpass);

 if(mysqli_connect_errno()){
 echo "Koneksi gagal: ".mysqli_connect_error();
 }
 //query membuat database
 $sql = "CREATE DATABASE $dbname";
 if (mysqli_query($conn, $sql)) {
 echo "Database $dbname berhasil dibuat";
 } else {
 echo "Gagal membuat database: " . mysqli_error($conn);
 }
 mysqli_close($conn);

    }
 ?>

 