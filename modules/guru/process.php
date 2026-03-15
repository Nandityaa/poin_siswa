<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST (bukan GET)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];
    $kode_guru = $_POST['kode_guru'];
    
    // Proses tambah data guru
    if($action == 'add'){
        $nama_guru = $_POST['nama_guru'];
        $username = $_POST['username'];
        $jabatan = $_POST['jabatan'];
        $telp = $_POST['telp'];
        $password_input = password_hash("Guru12345*!", PASSWORD_DEFAULT);
        $role = $_POST["role"];
        
        $query = mysqli_query($conn, "INSERT INTO guru (kode_guru, nama_pengguna, role, username, password, aktif, jabatan, telp) VALUES ('$kode_guru', '$nama_guru', '$role', '$username', '$password_input', 'Y', '$jabatan', '$telp')");
        if($query){
            header("Location: index.php");
            exit;
        }else{
            echo "Gagal Menambah Data Guru";
        }
    }

    // Proses edit data guru
    if($action == 'edit'){
        $nama_guru = mysqli_real_escape_string($conn, $_POST['nama_guru']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
        $telp = mysqli_real_escape_string($conn, $_POST['telp']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $aktif = mysqli_real_escape_string($conn, $_POST['aktif']);
        $kode_guru = mysqli_real_escape_string($conn, $kode_guru);

        $query = mysqli_query($conn, "UPDATE guru SET nama_pengguna='$nama_guru', username='$username', jabatan='$jabatan', telp='$telp', role='$role', aktif='$aktif' WHERE kode_guru='$kode_guru'");
        if($query){
            header("Location: index.php");
            exit;
        }else{
            echo "Gagal Mengubah Data Guru: " . mysqli_error($conn);
        }
    }

    // Proses hapus data guru
    if($action == 'delete'){
        $kode_guru = mysqli_real_escape_string($conn, $kode_guru);
        $query = mysqli_query($conn, "DELETE FROM guru WHERE kode_guru='$kode_guru'");
        if($query){
            header("Location: index.php");
            exit;
        }else{
            echo "Gagal Menghapus Data Guru: " . mysqli_error($conn);
        }
    }

}
?>