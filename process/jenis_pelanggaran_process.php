<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST (bukan GET)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];
    
    // Proses tambah data jenis pelanggaran
    if($action == 'add'){
        $pelanggaran = mysqli_real_escape_string($conn, $_POST['nama_pelanggaran']);
        $poin = mysqli_real_escape_string($conn, $_POST['poin']);
        
        $query = mysqli_query($conn, "INSERT INTO jenis_pelanggaran (jenis, poin) VALUES ('$pelanggaran', '$poin')");
        if($query){
            echo "<script>alert('Berhasil Menambah Data Jenis Pelanggaran'); window.location.href = '../pages/jenis_pelanggaran/list.php';</script>";
        }else{
            echo "<script>alert('Gagal Menambah Data Jenis Pelanggaran'); window.location.href = '../pages/jenis_pelanggaran/add.php';</script>";
        }
    }

    // Proses edit data jenis pelanggaran
    if($action == 'edit'){
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $pelanggaran = mysqli_real_escape_string($conn, $_POST['nama_pelanggaran']);
        $poin = mysqli_real_escape_string($conn, $_POST['poin']);
        
        $query = mysqli_query($conn, "UPDATE jenis_pelanggaran SET jenis='$pelanggaran', poin='$poin' WHERE id_jenis_pelanggaran='$id'");
        if($query){
            echo "<script>alert('Berhasil Mengubah Data Jenis Pelanggaran'); window.location.href = '../pages/jenis_pelanggaran/list.php';</script>";
        }else{
            echo "<script>alert('Gagal Mengubah Data Jenis Pelanggaran'); window.location.href = '../pages/jenis_pelanggaran/list.php';</script>";
        }
    }

    // Proses hapus data jenis pelanggaran
    if($action == 'delete'){
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        
        $query = mysqli_query($conn, "DELETE FROM jenis_pelanggaran WHERE id_jenis_pelanggaran='$id'");
        if($query){
            echo "<script>alert('Berhasil Menghapus Data Jenis Pelanggaran'); window.location.href = '../pages/jenis_pelanggaran/list.php';</script>";
        }else{
            echo "<script>alert('Gagal Menghapus Data Jenis Pelanggaran'); window.location.href = '../pages/jenis_pelanggaran/list.php';</script>";
        }
    }

}
?>
