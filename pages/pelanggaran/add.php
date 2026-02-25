<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil data siswa untuk datalist
$siswa_result = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa WHERE status = 'aktif' ORDER BY nama_siswa ASC");

// Mengambil data jenis pelanggaran
$jenis_result = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY id_jenis_pelanggaran ASC");
?>

<center>
    <h2>Entri Pelanggaran Siswa</h2>

    <!-- Form untuk mencatat pelanggaran -->
    <form action="/poin_siswa/process/pelanggaran_process.php" method="POST">
        <input type="hidden" name="action" value="add" />

        <table cellpadding="10">
            <tr>
                <td><label><b>NIS Siswa <span style="color:red;">*</span></b></label></td>
                <td>
                    <input type="text" name="nis" list="nis_list" placeholder="Pilih / Ketik NIS" autocomplete="off" required style="width: 100%;" />
                    <datalist id="nis_list">
                        <?php while ($s = mysqli_fetch_assoc($siswa_result)) { ?>
                            <option value="<?= $s['nis'] ?>"><?= $s['nis'] ?> - <?= htmlspecialchars($s['nama_siswa']) ?></option>
                        <?php } ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td><label><b>Jenis Pelanggaran <span style="color:red;">*</span></b></label></td>
                <td>
                    <select name="id_jenis_pelanggaran" style="width: 100%;" required>
                        <option value="">Pilih Jenis Pelanggaran</option>
                        <?php while ($j = mysqli_fetch_assoc($jenis_result)) { ?>
                            <option value="<?= $j['id_jenis_pelanggaran'] ?>"><?= htmlspecialchars($j['jenis']) ?> (Poin: <?= $j['poin'] ?>)</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label><b>Tanggal <span style="color:red;">*</span></b></label></td>
                <td>
                    <input type="datetime-local" name="tanggal" value="<?= date('Y-m-d\TH:i') ?>" required style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <td><label><b>Keterangan <span style="color:red;">*</span></b></label></td>
                <td>
                    <textarea name="keterangan" rows="3" required placeholder="Keterangan pelanggaran" style="width: 100%;"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <button type="submit">Simpan Pelanggaran</button>
                </td>
            </tr>
        </table>
    </form>
</center>

<?php
// Menyertakan bagian footer (penutup halaman)
include ROOTPATH . "/includes/footer.php";
?>
