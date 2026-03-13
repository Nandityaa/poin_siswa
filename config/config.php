<?php
// Definisi path root aplikasi agar bisa digunakan di seluruh aplikasi
if (!defined('ROOTPATH')) {
    define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
}

// Mengecek apakah server dijalankan secara lokal (localhost)
if (php_sapi_name() == 'cli' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1') {

    // Jika dijalankan di komputer lokal, gunakan konfigurasi lokal
    $host = "localhost";        // Alamat server database lokal
    $user = "root";             // Username MySQL lokal
    $password = ""; // Password MySQL lokal secara default password kosong
    $database = "poin_pelanggaran_siswa"; // Nama database lokal

} else {
    // Jika dijalankan di jaringan (bukan localhost), gunakan konfigurasi server
    $host = "192.168.0.249";    // IP server database di jaringan
    $user = "root";             // Username MySQL di server1
    $password = "password";     // Password MySQL di server
    $database = "Poin_Pelanggaran_Siswa"; // Nama database di server
}

// Membuat koneksi ke database menggunakan konfigurasi di atas
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Fungsi format tanggal ke bahasa Indonesia
// Contoh: "Senin, 02 Februari 2025"
function tanggal_indonesia($tanggal) {
    $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );

    $timestamp = strtotime($tanggal);
    $nama_hari = $hari[date('w', $timestamp)];
    $tgl = date('d', $timestamp);
    $bln = $bulan[(int)date('m', $timestamp)];
    $thn = date('Y', $timestamp);

    return "$nama_hari, $tgl $bln $thn";
}

?>