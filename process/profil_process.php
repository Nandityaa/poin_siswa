<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];
    $kode_guru = mysqli_real_escape_string($conn, $_POST['kode_guru']);

    // Proses update profil
    if ($action == 'update') {
        $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $telp = mysqli_real_escape_string($conn, $_POST['telp']);

        $query = mysqli_query($conn, "UPDATE guru SET nama_pengguna='$nama_pengguna', username='$username', telp='$telp' WHERE kode_guru='$kode_guru'");
        if ($query) {
            // Update cookie agar nama di navbar langsung berubah
            setcookie("nama", $nama_pengguna, time() + 3600, '/');
            setcookie("username", $username, time() + 3600, '/');
            header("Location: ../pages/profil/edit.php?success=profil");
            exit;
        } else {
            echo "Gagal Mengubah Data Profil: " . mysqli_error($conn);
        }
    }

    // Proses ganti password
    if ($action == 'change_password') {
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $password_konfirmasi = $_POST['password_konfirmasi'];

        // Cek password baru dan konfirmasi cocok
        if ($password_baru !== $password_konfirmasi) {
            header("Location: ../pages/profil/edit.php?error=password_beda");
            exit;
        }

        // Ambil password lama dari database
        $query_check = mysqli_query($conn, "SELECT password FROM guru WHERE kode_guru='$kode_guru'");
        $data = mysqli_fetch_assoc($query_check);

        // Verifikasi password lama
        if (password_verify($password_lama, $data['password'])) {
            $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $query = mysqli_query($conn, "UPDATE guru SET password='$password_hash' WHERE kode_guru='$kode_guru'");
            if ($query) {
                header("Location: ../pages/profil/edit.php?success=password");
                exit;
            } else {
                echo "Gagal Mengubah Password: " . mysqli_error($conn);
            }
        } else {
            header("Location: ../pages/profil/edit.php?error=password_lama");
            exit;
        }
    }
}

// Jika dipanggil via GET (dari header link lama), redirect ke halaman edit
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    header("Location: ../pages/profil/edit.php");
    exit;
}
?>
