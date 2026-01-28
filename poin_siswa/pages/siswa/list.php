<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/Poin_Pelanggaran_Siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil semua data siswa dari tabel 'Siswa' JOIN 'Ortu_Wali', 'Kelas', 'Tingkat', 'Program_Keahlian', 'Guru'
$result = mysqli_query($conn, "SELECT * FROM Siswa 
JOIN Ortu_Wali ON Siswa.Id_Ortu_Wali = Ortu_Wali.Id 
JOIN Kelas ON Siswa.Id_Kelas = Kelas.Id 
JOIN Tingkat ON Kelas.Tingkat = Tingkat.Id 
JOIN Program_Keahlian ON Kelas.Program = Program_Keahlian.Id 
JOIN Guru ON Kelas.Kode_Guru = Guru.Kode_Guru");
?>

<!-- Bagian tampilan daftar siswa --> 
<center>
    <h2>List Siswa</h2>

    <!-- Tombol untuk menuju halaman tambah siswa -->
    <a href="add.php">+ Tambah Data Siswa</a><br><br>

    <!-- Membuat tabel untuk menampilkan daftar siswa -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>Ayah</th>
                <th>Ibu</th>
                <th>Wali</th>
                <th>Pekerjaan Ayah</th>
                <th>Pekerjaan Ibu</th>
                <th>Pekerjaan Wali</th>
                <th>Alamat Ayah</th>
                <th>Alamat Ibu</th>
                <th>Alamat Wali</th>
                <th>Kelas</th>
                <th>Wali Kelas</th>
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
                <td><?= htmlspecialchars($row['NIS']) ?></td>
                <td><?= htmlspecialchars($row['Nama_Siswa']) ?></td>
                <td><?= htmlspecialchars($row['Jenis_Kelamin']) ?></td>
                <td><?= htmlspecialchars($row['Alamat']) ?></td>
                <td><?= (empty($row['Ayah']) || $row['Ayah'] == 'NULL') ? '-' : htmlspecialchars($row['Ayah']) ?></td>
                <td><?= (empty($row['Ibu']) || $row['Ibu'] == 'NULL') ? '-' : htmlspecialchars($row['Ibu']) ?></td>
                <td><?= (empty($row['Wali']) || $row['Wali'] == 'NULL') ? '-' : htmlspecialchars($row['Wali']) ?></td>
                <td><?= (empty($row['Pekerjaan_Ayah']) || $row['Pekerjaan_Ayah'] == 'NULL') ? '-' : htmlspecialchars($row['Pekerjaan_Ayah']) ?></td>
                <td><?= (empty($row['Pekerjaan_Ibu']) || $row['Pekerjaan_Ibu'] == 'NULL') ? '-' : htmlspecialchars($row['Pekerjaan_Ibu']) ?></td>
                <td><?= (empty($row['Pekerjaan_Wali']) || $row['Pekerjaan_Wali'] == 'NULL') ? '-' : htmlspecialchars($row['Pekerjaan_Wali']) ?></td>
                <td><?= (empty($row['Alamat_Ayah']) || $row['Alamat_Ayah'] == 'NULL') ? '-' : htmlspecialchars($row['Alamat_Ayah']) ?></td>
                <td><?= (empty($row['Alamat_Ibu']) || $row['Alamat_Ibu'] == 'NULL') ? '-' : htmlspecialchars($row['Alamat_Ibu']) ?></td>
                <td><?= (empty($row['Alamat_Wali']) || $row['Alamat_Wali'] == 'NULL') ? '-' : htmlspecialchars($row['Alamat_Wali']) ?></td>
                <td><?= htmlspecialchars($row['Nama_Tingkat'] . ' ' . $row['Program_Keahlian'] . ' ' . $row['Rombel']) ?></td>
                <td><?= htmlspecialchars($row['Nama_Pengguna']) ?></td>

                <!-- Tombol edit untuk ubah data siswa -->
                <td>
                    <a href="edit.php?id=<?= $row['NIS'] ?>">Edit</a>
                </td>

                <!-- Tombol hapus dengan pengecekan apakah siswa sudah punya transaksi -->
                <td>
                    <form action="/Poin_Pelanggaran_Siswa/process/siswa_process.php" method="post"
                        onsubmit="return confirm('Ingin Menghapus data <?= $row['Nama_Siswa'] ?>?')">
                        <!-- Kirim id dan action ke file proses -->
                        <input type="hidden" name="id" value="<?= $row['NIS'] ?>">
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
?><?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/Poin_Pelanggaran_Siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil semua data siswa dari tabel 'Siswa' JOIN 'Ortu_Wali', 'Kelas', 'Tingkat', 'Program_Keahlian', 'Guru'
$result = mysqli_query($conn, "SELECT * FROM Siswa 
JOIN Ortu_Wali ON Siswa.Id_Ortu_Wali = Ortu_Wali.Id 
JOIN Kelas ON Siswa.Id_Kelas = Kelas.Id 
JOIN Tingkat ON Kelas.Tingkat = Tingkat.Id 
JOIN Program_Keahlian ON Kelas.Program = Program_Keahlian.Id 
JOIN Guru ON Kelas.Kode_Guru = Guru.Kode_Guru");
?>

<!-- Bagian tampilan daftar siswa --> 
<center>
    <h2>List Siswa</h2>

    <!-- Tombol untuk menuju halaman tambah siswa -->
    <a href="add.php">+ Tambah Data Siswa</a><br><br>

    <!-- Membuat tabel untuk menampilkan daftar siswa -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>Ayah</th>
                <th>Ibu</th>
                <th>Wali</th>
                <th>Pekerjaan Ayah</th>
                <th>Pekerjaan Ibu</th>
                <th>Pekerjaan Wali</th>
                <th>Alamat Ayah</th>
                <th>Alamat Ibu</th>
                <th>Alamat Wali</th>
                <th>Kelas</th>
                <th>Wali Kelas</th>
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
                <td><?= htmlspecialchars($row['NIS']) ?></td>
                <td><?= htmlspecialchars($row['Nama_Siswa']) ?></td>
                <td><?= htmlspecialchars($row['Jenis_Kelamin']) ?></td>
                <td><?= htmlspecialchars($row['Alamat']) ?></td>
                <td><?= (empty($row['Ayah']) || $row['Ayah'] == 'NULL') ? '-' : htmlspecialchars($row['Ayah']) ?></td>
                <td><?= (empty($row['Ibu']) || $row['Ibu'] == 'NULL') ? '-' : htmlspecialchars($row['Ibu']) ?></td>
                <td><?= (empty($row['Wali']) || $row['Wali'] == 'NULL') ? '-' : htmlspecialchars($row['Wali']) ?></td>
                <td><?= (empty($row['Pekerjaan_Ayah']) || $row['Pekerjaan_Ayah'] == 'NULL') ? '-' : htmlspecialchars($row['Pekerjaan_Ayah']) ?></td>
                <td><?= (empty($row['Pekerjaan_Ibu']) || $row['Pekerjaan_Ibu'] == 'NULL') ? '-' : htmlspecialchars($row['Pekerjaan_Ibu']) ?></td>
                <td><?= (empty($row['Pekerjaan_Wali']) || $row['Pekerjaan_Wali'] == 'NULL') ? '-' : htmlspecialchars($row['Pekerjaan_Wali']) ?></td>
                <td><?= (empty($row['Alamat_Ayah']) || $row['Alamat_Ayah'] == 'NULL') ? '-' : htmlspecialchars($row['Alamat_Ayah']) ?></td>
                <td><?= (empty($row['Alamat_Ibu']) || $row['Alamat_Ibu'] == 'NULL') ? '-' : htmlspecialchars($row['Alamat_Ibu']) ?></td>
                <td><?= (empty($row['Alamat_Wali']) || $row['Alamat_Wali'] == 'NULL') ? '-' : htmlspecialchars($row['Alamat_Wali']) ?></td>
                <td><?= htmlspecialchars($row['Nama_Tingkat'] . ' ' . $row['Program_Keahlian'] . ' ' . $row['Rombel']) ?></td>
                <td><?= htmlspecialchars($row['Nama_Pengguna']) ?></td>

                <!-- Tombol edit untuk ubah data siswa -->
                <td>
                    <a href="edit.php?id=<?= $row['NIS'] ?>">Edit</a>
                </td>

                <!-- Tombol hapus dengan pengecekan apakah siswa sudah punya transaksi -->
                <td>
                    <form action="/Poin_Pelanggaran_Siswa/process/siswa_process.php" method="post"
                        onsubmit="return confirm('Ingin Menghapus data <?= $row['Nama_Siswa'] ?>?')">
                        <!-- Kirim id dan action ke file proses -->
                        <input type="hidden" name="id" value="<?= $row['NIS'] ?>">
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