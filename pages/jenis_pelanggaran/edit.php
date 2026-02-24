<?php
// Menentukan lokasi folder utama proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan file header
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// Fetch data jenis pelanggaran
$query = "SELECT * FROM jenis_pelanggaran WHERE id_jenis_pelanggaran = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div style='text-align:center; margin-top:40px;'><h3>Data jenis pelanggaran tidak ditemukan!</h3><a href='list.php'>Kembali</a></div>";
} else {
?>

<!-- Membuat tampilan form untuk edit data jenis pelanggaran -->
<center>
    <h2>Edit Data Jenis Pelanggaran</h2>

    <form action="/poin_siswa/process/jenis_pelanggaran_process.php" method="POST">
        <table cellpadding="10">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id_jenis_pelanggaran']); ?>" />

            <tr>
                <td><label><b>Nama Pelanggaran</b></label></td>
                <td><input type="text" name="nama_pelanggaran" value="<?php echo htmlspecialchars($data['jenis']); ?>" required /></td>
            </tr>
            <tr>
                <td><label><b>Poin</b></label></td>
                <td><input type="number" name="poin" value="<?php echo htmlspecialchars($data['poin']); ?>" required /></td>
            </tr>
        </table>
        <a href="list.php" style="margin-right:10px;">Kembali</a>
        <button type="submit">Update Data</button>
    </form>
</center>

<?php
}

// Menyertakan file footer
include ROOTPATH . "/includes/footer.php";
?>
