<?php
if(isset($_COOKIE['username'])) {
    if(isset($_COOKIE['role']) && $_COOKIE['role'] == 'siswa') {
        header("Location: modules/dashboard/siswa.php");
    } else {
        header("Location: modules/dashboard/index.php");
    }
} else {
    header("Location: modules/auth/login.php");
}
exit();
?>
