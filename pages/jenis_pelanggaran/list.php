<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil semua data siswa dari tabel 'Siswa' JOIN 'Ortu_Wali', 'Kelas', 'Tingkat', 'Program_Keahlian', 'Guru'
$result = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran");
?>

<!-- Bagian tampilan daftar siswa --> 
<center>
    <h2>Data Jenis Pelanggaran</h2>

    <!-- Tombol untuk menuju halaman tambah siswa -->
    <button><a href="add.php">+ Tambah Data</a></button><br><br>

    <!-- Membuat tabel untuk menampilkan daftar siswa -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Pelanggaran</th>
                <th>Point</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Variabel untuk nomor urut
            $no = 1;

            // Menampilkan semua data kasir dari hasil query
            while ($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <!-- Menampilkan nomor urut -->
                <td><?= $no++?></td>
                <!-- Menampilkan data siswa, fungsi dari htmlspecialchars() untuk memfilter data agar aman dari XSS -->
                <td><?= htmlspecialchars($row['jenis']) ?></td>
                <td><?= htmlspecialchars($row['poin']) ?></td>
                <td>
                    <button><a href="edit.php?id=<?= $row['id_jenis_pelanggaran'] ?>">Edit</a></button>
                </td>
                <td>
                    <form action="/poin_siswa/process/jenis_pelanggaran_process.php" method="post"
                        onsubmit="return confirm('Ingin Menghapus data <?= $row['jenis'] ?>?')">
                        <!-- Kirim id dan action ke file proses -->
                        <input type="hidden" name="id" value="<?= $row['id_jenis_pelanggaran'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</center>

<?php 
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php"; 
?>