<?php
// Menentukan lokasi folder utama proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan file header (biasanya berisi tampilan atas halaman dan koneksi dasar)
include ROOTPATH . "/layouts/header.php";
?>

<!-- Membuat tampilan form untuk menambah data jenis pelanggaran -->
<center>
    <h2>Tambah Data Jenis Pelanggaran</h2>

    <!-- Form untuk mengirim data jenis pelanggaran baru ke file proses -->
    <form action="/poin_siswa/modules/jenis_pelanggaran/process.php" method="POST">
        <table cellpadding="10">
            <!-- Menyembunyikan input action agar file proses tahu ini adalah aksi 'add' -->
            <input type="hidden" name="action" value="add" />

            <tr>
                <td><input type="text" name="nama_pelanggaran" autocomplete="off" required placeholder="Nama Pelanggaran"/></td>
            </tr>
            <tr>
                <td><input type="number" name="poin" autocomplete="off" required placeholder="Poin"/></td>
            </tr>
        </table>
        <button type="submit">Tambah Data</button>
    </form>
</center>

<?php
// Menyertakan file footer (biasanya berisi bagian bawah halaman)
include ROOTPATH . "/layouts/footer.php";
?>
