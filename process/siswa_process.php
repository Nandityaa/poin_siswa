<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST (bukan GET)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $action = $_POST['action'];

    // Jika aksi adalah "delete", maka hapus data siswa berdasarkan NIS
    if ($action == 'delete') {
        $query = "DELETE FROM siswa WHERE nis='$nis'";
        mysqli_query($conn, $query) or die(mysqli_error($conn));

        // Jika aksi adalah "add", maka tambahkan data siswa beserta orang tua
    } elseif ($action == 'add') {
        // Ambil Data Siswa
        $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
        $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $id_kelas = !empty($_POST['id_kelas']) ? mysqli_real_escape_string($conn, $_POST['id_kelas']) : 'NULL';
        $password = password_hash('Siswa12345*!', PASSWORD_DEFAULT); // Default password

        // Ambil Data Ortu
        $nama_ayah = !empty($_POST['nama_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['nama_ayah']) . "'" : "NULL";
        $pekerjaan_ayah = !empty($_POST['pekerjaan_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_ayah']) . "'" : "NULL";
        $alamat_ayah = !empty($_POST['alamat_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_ayah']) . "'" : "NULL";

        $nama_ibu = !empty($_POST['nama_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['nama_ibu']) . "'" : "NULL";
        $pekerjaan_ibu = !empty($_POST['pekerjaan_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_ibu']) . "'" : "NULL";
        $alamat_ibu = !empty($_POST['alamat_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_ibu']) . "'" : "NULL";

        $nama_wali = !empty($_POST['nama_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['nama_wali']) . "'" : "NULL";
        $pekerjaan_wali = !empty($_POST['pekerjaan_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_wali']) . "'" : "NULL";
        $alamat_wali = !empty($_POST['alamat_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_wali']) . "'" : "NULL";

        // Query Insert Ortu_Wali
        $query_ortu = "INSERT INTO ortu_wali (ayah, ibu, wali, pekerjaan_ayah, pekerjaan_ibu, pekerjaan_wali, alamat_ayah, alamat_ibu, alamat_wali) 
                       VALUES ($nama_ayah, $nama_ibu, $nama_wali, $pekerjaan_ayah, $pekerjaan_ibu, $pekerjaan_wali, $alamat_ayah, $alamat_ibu, $alamat_wali)";

        if (mysqli_query($conn, $query_ortu)) {
            // Ambil ID Ortu yang baru saja dibuat
            $id_ortu_wali = mysqli_insert_id($conn);

            // Query Insert Siswa
            $query_siswa = "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, alamat, password, status, id_ortu_wali, id_kelas) 
                            VALUES ('$nis', '$nama_siswa', '$jenis_kelamin', '$alamat', '$password', 'aktif', $id_ortu_wali, $id_kelas)";

            mysqli_query($conn, $query_siswa) or die("Error Insert Siswa: " . mysqli_error($conn));
        } else {
            die("Error Insert Ortu: " . mysqli_error($conn));
        }

        // Jika aksi adalah "edit", maka ubah data siswa berdasarkan NIS
    } elseif ($action == 'edit') {
        $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
        $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $id_kelas = !empty($_POST['id_kelas']) ? mysqli_real_escape_string($conn, $_POST['id_kelas']) : 'NULL';
        $id_ortu_wali = !empty($_POST['id_ortu_wali']) ? mysqli_real_escape_string($conn, $_POST['id_ortu_wali']) : 'NULL';

        $query = "UPDATE siswa SET 
                    nama_siswa='$nama_siswa', 
                    jenis_kelamin='$jenis_kelamin', 
                    alamat='$alamat', 
                    id_kelas=$id_kelas, 
                    id_ortu_wali=$id_ortu_wali 
                  WHERE nis='$nis'";
        mysqli_query($conn, $query) or die(mysqli_error($conn));
    }

    // Setelah selesai, arahkan kembali ke halaman daftar siswa
    header("Location: ../pages/siswa/list.php");
    exit;
}
?>

<!-- 
🧠 Penjelasan Singkat:
Kode ini menangani proses CRUD Siswa.
Update terbaru: Memproses Insert data orang tua (ortu_wali) terlebih dahulu sebelum memasukkan data siswa, 
agar relasi foreign key `id_ortu_wali` terbentuk otomatis.
-->