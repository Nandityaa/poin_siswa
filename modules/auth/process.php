<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

$username = mysqli_real_escape_string($conn, $_POST["username"]);
$password_hash = $_POST["password"];

$query_guru = mysqli_query($conn, "SELECT nama_pengguna, username, password, jabatan FROM guru WHERE username = '$username'");
$query_siswa = mysqli_query($conn, "SELECT nis, nama_siswa, password FROM siswa WHERE nis = '$username'");

if(mysqli_num_rows($query_guru) >= 1){
    $query_guru = mysqli_fetch_assoc($query_guru);
    if(password_verify($password_hash, $query_guru['password'])){
        setcookie("nama", $query_guru['nama_pengguna'], time() + 3600, '/');
        setcookie("username", $query_guru['username'], time() + 3600, '/');
        
        // RBAC Role Mapping based on Jabatan
        $jabatan = strtolower(trim($query_guru['jabatan']));
        $assigned_role = 'guru_mapel'; // default for teachers
        
        if (strpos($jabatan, 'admin bk') !== false || strpos($jabatan, 'guru bk') !== false) {
            $assigned_role = 'admin_bk';
        } elseif (strpos($jabatan, 'waka kesiswaan') !== false) {
            $assigned_role = 'wakasek';
        } elseif (strpos($jabatan, 'kepala sekolah') !== false) {
            $assigned_role = 'kepsek';
        }
        
        setcookie("role", $assigned_role, time() + 3600, '/');

        header('Location: /poin_siswa/modules/dashboard/index.php');
        exit;
    }else{
        echo "Password Salah";
    };
}elseif(mysqli_num_rows($query_siswa) >= 1){
    $query_siswa = mysqli_fetch_assoc($query_siswa);
    if(password_verify($password_hash, $query_siswa['password'])){
        setcookie("nama", $query_siswa['nama_siswa'], time() + 3600, '/');
        setcookie("username", $query_siswa['nis'], time() + 3600, '/');
        setcookie("role", "siswa", time() + 3600, '/');
        header('Location: /poin_siswa/modules/dashboard/siswa.php');
        exit;
    }else{
        echo "Password Salah";
    };
}else{
    echo "anda siapa????";
}
?>