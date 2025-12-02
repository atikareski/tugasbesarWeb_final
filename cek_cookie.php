<?php

$user_session_active = isset($_SESSION['id_pengguna']); 
$cookie_active = isset($_COOKIE['user_id']); 

if ($user_session_active && !$cookie_active) {

    session_unset(); 
    session_destroy();

    echo "<script>alert('Waktu sesi login 5 menit Anda telah habis. Silakan login kembali.');</script>";

    echo "<meta http-equiv='refresh' content='0; url=Login.php'>";
    exit();
}

if (empty($_SESSION['id_pengguna']) && isset($_COOKIE['user_id']) && isset($_COOKIE['user_login'])) {
    
    $user_id_cookie = $_COOKIE['user_id'];
    $username_cookie = $_COOKIE['user_login'];

    if (isset($conn)) {
        $user_id_safe = mysqli_real_escape_string($conn, $user_id_cookie);
        $username_safe = mysqli_real_escape_string($conn, $username_cookie);
        
        $sql_check = "SELECT id_pengguna, username FROM pengguna WHERE id_pengguna = '$user_id_safe' AND username = '$username_safe'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) == 1) {
            $user = mysqli_fetch_assoc($result_check);

            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['username'] = $user['username'];

            header('location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
             setcookie('user_login', '', time() - 3600, '/');
             setcookie('user_id', '', time() - 3600, '/');
        }
    }
}
?>