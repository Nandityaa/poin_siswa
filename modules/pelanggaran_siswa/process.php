<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    // Proses tambah data pelanggaran
    if ($action == 'add') {
        $nis = mysqli_real_escape_string($conn, $_POST['nis']);
        $id_jenis_pelanggaran = mysqli_real_escape_string($conn, $_POST['id_jenis_pelanggaran']);
        $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
        $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

        $query = mysqli_query($conn, "INSERT INTO pelanggaran_siswa (tanggal, nis, id_jenis_pelanggaran, keterangan) VALUES ('$tanggal', '$nis', '$id_jenis_pelanggaran', '$keterangan')");
        if ($query) {
            header("Location: /poin_siswa/modules/laporan/index.php");
            exit;
        } else {
            echo "Gagal Menambah Data Pelanggaran: " . mysqli_error($conn);
        }
    }

    // Proses hapus data pelanggaran
    if ($action == 'delete') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        $query = mysqli_query($conn, "DELETE FROM pelanggaran_siswa WHERE id_pelanggaran_siswa='$id'");
        if ($query) {
            header("Location: /poin_siswa/modules/laporan/index.php");
            exit;
        } else {
            echo "Gagal Menghapus Data Pelanggaran: " . mysqli_error($conn);
        }
    }
}
?>
