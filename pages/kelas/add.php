<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil data tingkat
$tingkat_result = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY id_tingkat ASC");

// Mengambil data program keahlian
$prodi_result = mysqli_query($conn, "SELECT * FROM program_keahlian ORDER BY id_program_keahlian ASC");

// Mengambil data guru aktif (untuk wali kelas)
$guru_result = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'Y' ORDER BY nama_pengguna ASC");
?>

<!-- Membuat tampilan form untuk menambah data kelas -->
<center>
    <h2>Tambah Data Kelas</h2>

    <!-- Form untuk mengirim data kelas baru ke file proses -->
    <form action="/poin_siswa/process/kelas_process.php" method="POST">
        <table cellpadding="10">

            <!-- Menyembunyikan input action agar file proses tahu ini adalah aksi 'add' -->
            <input type="hidden" name="action" value="add" />

            <tr>
                <td><label><b>Tingkat <span style="color:red;">*</span></b></label></td>
                <td>
                    <select name="id_tingkat" style="width: 100%;" required>
                        <option value="">Pilih Tingkat</option>
                        <?php while ($t = mysqli_fetch_assoc($tingkat_result)) { ?>
                            <option value="<?= $t['id_tingkat'] ?>"><?= htmlspecialchars($t['tingkat']) ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label><b>Program Keahlian <span style="color:red;">*</span></b></label></td>
                <td>
                    <select name="id_program_keahlian" style="width: 100%;" required>
                        <option value="">Pilih Program Keahlian</option>
                        <?php while ($p = mysqli_fetch_assoc($prodi_result)) { ?>
                            <option value="<?= $p['id_program_keahlian'] ?>"><?= htmlspecialchars($p['program_keahlian'] . ' - ' . $p['deskripsi']) ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label><b>Rombel <span style="color:red;">*</span></b></label></td>
                <td><input type="number" name="rombel" min="1" max="10" required placeholder="Nomor Rombel (1, 2, 3, ...)" style="width: 100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Wali Kelas <span style="color:red;">*</span></b></label></td>
                <td>
                    <select name="kode_guru" style="width: 100%;" required>
                        <option value="">Pilih Wali Kelas</option>
                        <?php while ($g = mysqli_fetch_assoc($guru_result)) { ?>
                            <option value="<?= htmlspecialchars($g['kode_guru']) ?>"><?= htmlspecialchars($g['nama_pengguna']) ?> (<?= htmlspecialchars($g['jabatan']) ?>)</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <a href="list.php" style="margin-right:10px;">Kembali</a>
                    <button type="submit">Tambah Data Kelas</button>
                </td>
            </tr>
        </table>
    </form>
</center>

<?php
// Menyertakan bagian footer (penutup halaman)
include ROOTPATH . "/includes/footer.php";
?>
