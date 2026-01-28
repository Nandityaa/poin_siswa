<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/Poin_Pelanggaran_Siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST (bukan GET)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Mengambil data dari form
    $NIS = $_POST['NIS'];         // NIS siswa
    $action = $_POST['action']; // Jenis aksi (add, edit, delete)
    $name = $_POST['Nama_Siswa'];     // Nama siswa
    $jenis_kelamin = $_POST['Jenis_Kelamin']; // Jenis kelamin siswa
    $alamat = $_POST['Alamat']; // Alamat siswa
    $ayah = $_POST['Ayah']; // Ayah siswa
    $ibu = $_POST['Ibu']; // Ibu siswa
    $wali = $_POST['Wali']; // Wali siswa
    $pekerjaan_ayah = $_POST['Pekerjaan_Ayah']; // Pekerjaan ayah
    $pekerjaan_ibu = $_POST['Pekerjaan_Ibu']; // Pekerjaan ibu
    $pekerjaan_wali = $_POST['Pekerjaan_Wali']; // Pekerjaan wali
    $alamat_ayah = $_POST['Alamat_Ayah']; // Alamat ayah
    $alamat_ibu = $_POST['Alamat_Ibu']; // Alamat ibu
    $alamat_wali = $_POST['Alamat_Wali']; // Alamat wali
    $kelas = $_POST['Kelas']; // Kelas siswa
    $wali_kelas = $_POST['Wali_Kelas']; // Wali kelas siswa
    
    // Jika aksi adalah "add", maka tambahkan data siswa baru ke tabel
    if ($action == 'add') {
        $query = "INSERT INTO Siswa VALUES ('$NIS', '$name', '$jenis_kelamin', '$alamat', '$ayah', '$ibu', '$wali', '$pekerjaan_ayah', '$pekerjaan_ibu', '$pekerjaan_wali', '$alamat_ayah', '$alamat_ibu', '$alamat_wali', '$kelas', '$wali_kelas')";
        mysqli_query($conn, $query);

    // Jika aksi adalah "edit", maka ubah data siswa berdasarkan NIS
    } elseif ($action == 'edit') {
        $query = "UPDATE Siswa SET Nama_Siswa='$name', Jenis_Kelamin='$jenis_kelamin', Alamat='$alamat', Ayah='$ayah', Ibu='$ibu', Wali='$wali', Pekerjaan_Ayah='$pekerjaan_ayah', Pekerjaan_Ibu='$pekerjaan_ibu', Pekerjaan_Wali='$pekerjaan_wali', Alamat_Ayah='$alamat_ayah', Alamat_Ibu='$alamat_ibu', Alamat_Wali='$alamat_wali', Kelas='$kelas', Wali_Kelas='$wali_kelas' WHERE NIS=$NIS";
        mysqli_query($conn, $query);

    // Jika aksi adalah "delete", maka hapus data siswa berdasarkan NIS
    } elseif ($action == 'delete') {
        $query = "DELETE FROM Siswa WHERE NIS=$NIS";
        mysqli_query($conn, $query);
    }

    // Setelah selesai, arahkan kembali ke halaman daftar siswa
    header("Location: ../pages/siswa/list.php");
    exit;
}
?>

<!-- 
🧠 Penjelasan Singkat:

Kode ini berfungsi sebagai file proses (process file) untuk tabel siswa — menangani semua aksi dari form seperti:
	•	Tambah data (add)
	•	Edit data (edit)
	•	Hapus data (delete)

Setelah aksi dijalankan, pengguna akan otomatis diarahkan kembali ke halaman daftar siswa (list.php).

👉 File ini dipakai dari form add.php(insert), edit.php(update), dan list(delete).php 
-->