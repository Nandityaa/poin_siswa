<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";


if(isset($_COOKIE['username'])){
   echo "Hallo " . $_COOKIE['nama'];
   echo "<br>";
   echo "Username " . $_COOKIE['username'];
}

include ROOTPATH . "/includes/footer.php";
?>