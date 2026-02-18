<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil semua data guru dari tabel 'guru' 
$result = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'Y'");
$result_nonaktif = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'N'");
?>

<!-- Bagian tampilan daftar guru --> 
<center>
    <h2>List Guru</h2>

    <!-- Tombol untuk menuju halaman tambah guru -->
    <button><a href="add.php">+ Tambah Data Guru</a></button><br><br>

    <fieldset>
    <div class="scroll">
        <!-- Membuat tabel untuk menampilkan daftar guru -->
        <table border="1" cellpadding="10" cellspacing="0" width="200%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Guru</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th colspan="2">Aksi</th>
                </tr>
                </thead>
            <tbody>
                <?php 
                // Variabel untuk nomor urut
                $no = 1;

                // Menampilkan semua data guru dari hasil query
                while ($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <!-- Menampilkan nomor urut -->
                    <td><?= $no++?></td>
                    <!-- Menampilkan data guru, fungsi dari htmlspecialchars() untuk memfilter data agar aman dari XSS -->
                    <td><?= htmlspecialchars($row['kode_guru']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['jabatan']) ?></td>
                    <td><?= htmlspecialchars($row['telp']) ?></td>
                    <!-- Tombol edit untuk ubah data guru -->
                    <td>
                        <button><a href="edit.php?id=<?= $row['kode_guru'] ?>">Edit</a></button>
                    </td>

                    <!-- Tombol hapus dengan pengecekan apakah guru sudah punya transaksi -->
                    <td>
                        <form action="/poin_siswa/process/guru_process.php" method="post"
                            onsubmit="return confirm('Ingin Menghapus data <?= $row['nama_pengguna'] ?>?')">
                            <!-- Kirim id dan action ke file proses -->
                            <input type="hidden" name="kode_guru" value="<?= $row['kode_guru'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    </fieldset>






    <br>
    <fieldset>
    <h2>List Guru Non-Aktif</h2>

    <div class="scroll">
        <!-- Membuat tabel untuk menampilkan daftar guru Non-Aktif -->
        <table border="1" cellpadding="10" cellspacing="0" width="200%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Guru</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th colspan="2">Aksi</th>
                </tr>
                </thead>
            <tbody>
                <?php 
                // Variabel untuk nomor urut
                $no = 1;

                // Menampilkan semua data guru dari hasil query
                while ($row = mysqli_fetch_assoc($result_nonaktif)){ ?>
                <tr>
                    <!-- Menampilkan nomor urut -->
                    <td><?= $no++?></td>
                    <!-- Menampilkan data guru, fungsi dari htmlspecialchars() untuk memfilter data agar aman dari XSS -->
                    <td><?= htmlspecialchars($row['kode_guru']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['jabatan']) ?></td>
                    <td><?= htmlspecialchars($row['telp']) ?></td>
                    <!-- Tombol edit untuk ubah data guru -->
                    <td>
                        <button><a href="edit.php?id=<?= $row['kode_guru'] ?>">Edit</a></button>
                    </td>

                    <!-- Tombol hapus dengan pengecekan apakah guru sudah punya transaksi -->
                    <td>
                        <form action="/poin_siswa/process/guru_process.php" method="post"
                            onsubmit="return confirm('Ingin Menghapus data <?= $row['nama_pengguna'] ?>?')">
                            <!-- Kirim id dan action ke file proses -->
                            <input type="hidden" name="kode_guru" value="<?= $row['kode_guru'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    </fieldset>
</center>

<?php 
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php"; 
?>