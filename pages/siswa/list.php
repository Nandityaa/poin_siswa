<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil semua data siswa dari tabel 'Siswa' JOIN 'Ortu_Wali', 'Kelas', 'Tingkat', 'Program_Keahlian', 'Guru'
// ALIASING columns to ensure keys are always lowercase regardless of DB schema case
$query = "SELECT 
            siswa.nis AS nis, 
            siswa.nama_siswa AS nama_siswa, 
            siswa.jenis_kelamin AS jenis_kelamin, 
            siswa.alamat AS alamat,
            ortu_wali.ayah AS ayah, 
            ortu_wali.ibu AS ibu, 
            ortu_wali.wali AS wali, 
            ortu_wali.pekerjaan_ayah AS pekerjaan_ayah, 
            ortu_wali.pekerjaan_ibu AS pekerjaan_ibu, 
            ortu_wali.pekerjaan_wali AS pekerjaan_wali,
            ortu_wali.alamat_ayah AS alamat_ayah, 
            ortu_wali.alamat_ibu AS alamat_ibu, 
            ortu_wali.alamat_wali AS alamat_wali,
            tingkat.tingkat AS tingkat, 
            program_keahlian.program_keahlian AS program_keahlian, 
            kelas.rombel AS rombel,
            guru.nama_pengguna AS nama_pengguna
          FROM siswa 
          JOIN ortu_wali ON siswa.id_ortu_wali = ortu_wali.id_ortu_wali 
          JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
          JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
          JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian 
          JOIN guru ON kelas.kode_guru = guru.kode_guru";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($conn) . " - " . mysqli_errno($conn));
}
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
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <!-- Menampilkan nomor urut -->
                    <td><?= $no++ ?></td>
                    <!-- Menampilkan data siswa, fungsi dari htmlspecialchars() untuk memfilter data agar aman dari XSS -->
                    <td><?= htmlspecialchars($row['nis']) ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                    <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= (empty($row['ayah']) || $row['ayah'] == 'NULL') ? '-' : htmlspecialchars($row['ayah']) ?></td>
                    <td><?= (empty($row['ibu']) || $row['ibu'] == 'NULL') ? '-' : htmlspecialchars($row['ibu']) ?></td>
                    <td><?= (empty($row['wali']) || $row['wali'] == 'NULL') ? '-' : htmlspecialchars($row['wali']) ?></td>
                    <td><?= (empty($row['pekerjaan_ayah']) || $row['pekerjaan_ayah'] == 'NULL') ? '-' : htmlspecialchars($row['pekerjaan_ayah']) ?>
                    </td>
                    <td><?= (empty($row['pekerjaan_ibu']) || $row['pekerjaan_ibu'] == 'NULL') ? '-' : htmlspecialchars($row['pekerjaan_ibu']) ?>
                    </td>
                    <td><?= (empty($row['pekerjaan_wali']) || $row['pekerjaan_wali'] == 'NULL') ? '-' : htmlspecialchars($row['pekerjaan_wali']) ?>
                    </td>
                    <td><?= (empty($row['alamat_ayah']) || $row['alamat_ayah'] == 'NULL') ? '-' : htmlspecialchars($row['alamat_ayah']) ?>
                    </td>
                    <td><?= (empty($row['alamat_ibu']) || $row['alamat_ibu'] == 'NULL') ? '-' : htmlspecialchars($row['alamat_ibu']) ?>
                    </td>
                    <td><?= (empty($row['alamat_wali']) || $row['alamat_wali'] == 'NULL') ? '-' : htmlspecialchars($row['alamat_wali']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel']) ?>
                    </td>
                    <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>

                    <!-- Tombol edit untuk ubah data siswa -->
                    <td>
                        <a href="edit.php?id=<?= $row['NIS'] ?>">Edit</a>
                    </td>

                    <!-- Tombol hapus dengan pengecekan apakah siswa sudah punya transaksi -->
                    <td>
                        <form action="../../process/siswa_process.php" method="post"
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