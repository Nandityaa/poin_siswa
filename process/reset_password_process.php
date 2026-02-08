<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";

$username = mysqli_real_escape_string($conn, $_POST['username']);
$new_password_input = $_POST['new_password'];

if(empty($username) || empty($new_password_input)){
    echo "<script>alert('Masukkan Username/NIS dan Password Baru!'); window.history.back();</script>";
    exit;
}

$new_password_hash = password_hash($new_password_input, PASSWORD_DEFAULT);

// Cek di tabel guru
$query_guru = mysqli_query($conn, "SELECT username FROM guru WHERE username = '$username'");
if(mysqli_num_rows($query_guru) > 0){
    // Reset password guru ke password baru
    $update = mysqli_query($conn, "UPDATE guru SET password = '$new_password_hash' WHERE username = '$username'");
    if($update){
        echo "<script>alert('Password GURU berhasil diubah!'); window.location='../login.php';</script>";
    } else {
        echo "<script>alert('Gagal mereset password guru.'); window.history.back();</script>";
    }
    exit;
}

// Cek di tabel siswa
$query_siswa = mysqli_query($conn, "SELECT nis FROM siswa WHERE nis = '$username'");
if(mysqli_num_rows($query_siswa) > 0){
    // Reset password siswa ke password baru
    $update = mysqli_query($conn, "UPDATE siswa SET password = '$new_password_hash' WHERE nis = '$username'");
    if($update){
        echo "<script>alert('Password SISWA berhasil diubah!'); window.location='../login.php';</script>";
    } else {
        echo "<script>alert('Gagal mereset password siswa.'); window.history.back();</script>";
    }
    exit;
}

// Jika tidak ditemukan
echo "<script>alert('Username / NIS tidak ditemukan!'); window.history.back();</script>";
?>
