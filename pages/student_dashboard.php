<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>

<div style="text-align: center; padding: 20px;">
    <h2>Selamat Datang, Siswa!</h2>
    <?php
    if(isset($_COOKIE['nama'])){
       echo "<p>Halo, <strong>" . htmlspecialchars($_COOKIE['nama']) . "</strong></p>";
       echo "<p>NIS: " . htmlspecialchars($_COOKIE['username']) . "</p>";
    }
    ?>
    <p>Ini adalah halaman khusus untuk siswa.</p>
</div>

<?php
include ROOTPATH . "/includes/footer.php";
?>
