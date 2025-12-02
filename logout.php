<?php
    session_start();
    setcookie('user_login', '', time() - 3600, '/');
    setcookie('user_id', '', time() - 3600, '/');
    session_destroy();
    echo "<script>alert('Anda telah logout')</script>";
    echo "<meta http-equiv='refresh' content='0;url=Login.php'>";
?>