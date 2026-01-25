<?php
// config/config.php

// Database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "poin_pelanggaran_siswa1";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Koneksi DB gagal: " . $conn->connect_error);
}

$base_url = "/poin_siswa";

// timezone
date_default_timezone_set("Asia/Makassar");
?>