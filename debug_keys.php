<?php
// Handle CLI vs Web path
if (php_sapi_name() == "cli") {
    $root = __DIR__;
} else {
    $root = $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa/poin_siswa';
}

// Simple logic: we know where config is relative to this file
include __DIR__ . "/config/config.php";

$query = "SELECT * FROM siswa LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);
echo "\n--- RECORD START ---\n";
if ($row) {
    print_r($array_keys = array_keys($row));
} else {
    echo "No records found.";
}
echo "\n--- RECORD END ---\n";
?>