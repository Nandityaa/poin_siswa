<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    // Proses tambah data kelas
    if ($action == 'add') {
        $id_tingkat = mysqli_real_escape_string($conn, $_POST['id_tingkat']);
        $id_program_keahlian = mysqli_real_escape_string($conn, $_POST['id_program_keahlian']);
        $rombel = mysqli_real_escape_string($conn, $_POST['rombel']);
        $kode_guru = mysqli_real_escape_string($conn, $_POST['kode_guru']);

        $query = mysqli_query($conn, "INSERT INTO kelas (id_tingkat, id_program_keahlian, rombel, kode_guru) VALUES ('$id_tingkat', '$id_program_keahlian', '$rombel', '$kode_guru')");
        if ($query) {
            header("Location: ../pages/kelas/list.php");
            exit;
        } else {
            echo "Gagal Menambah Data Kelas: " . mysqli_error($conn);
        }
    }

    // Proses edit data kelas
    if ($action == 'edit') {
        $id_kelas = mysqli_real_escape_string($conn, $_POST['id_kelas']);
        $id_tingkat = mysqli_real_escape_string($conn, $_POST['id_tingkat']);
        $id_program_keahlian = mysqli_real_escape_string($conn, $_POST['id_program_keahlian']);
        $rombel = mysqli_real_escape_string($conn, $_POST['rombel']);
        $kode_guru = mysqli_real_escape_string($conn, $_POST['kode_guru']);

        $query = mysqli_query($conn, "UPDATE kelas SET id_tingkat='$id_tingkat', id_program_keahlian='$id_program_keahlian', rombel='$rombel', kode_guru='$kode_guru' WHERE id_kelas='$id_kelas'");
        if ($query) {
            header("Location: ../pages/kelas/list.php");
            exit;
        } else {
            echo "Gagal Mengubah Data Kelas: " . mysqli_error($conn);
        }
    }

    // Proses hapus data kelas
    if ($action == 'delete') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);

        $query = mysqli_query($conn, "DELETE FROM kelas WHERE id_kelas='$id'");
        if ($query) {
            header("Location: ../pages/kelas/list.php");
            exit;
        } else {
            echo "Gagal Menghapus Data Kelas: " . mysqli_error($conn);
        }
    }
}
?>
