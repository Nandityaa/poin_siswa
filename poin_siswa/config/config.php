<?php
// Mengecek apakah server dijalankan secara lokal (localhost)
if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {

    // Jika dijalankan di komputer lokal, gunakan konfigurasi lokal
    $host = "localhost";        // Alamat server database lokal
    $user = "root";             // Username MySQL lokal
    $password = ""; // Password MySQL lokal secara default password kosong
    $database = "poin_pelanggaran_siswa"; // Nama database lokal

} else {
    // Jika dijalankan di jaringan (bukan localhost), gunakan konfigurasi server
    $host = "192.168.0.249";    // IP server database di jaringan
    $user = "root";             // Username MySQL di server
    $password = "password";     // Password MySQL di server
    $database = "Poin_Pelanggaran_Siswa"; // Nama database di server
}

// Membuat koneksi ke database menggunakan konfigurasi di atas
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

?>