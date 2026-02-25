<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan file header
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// Fetch data kelas
$query = "SELECT * FROM kelas WHERE id_kelas = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div style='text-align:center; margin-top:40px;'><h3>Data kelas tidak ditemukan!</h3><a href='list.php'>Kembali</a></div>";
} else {
    // Mengambil data tingkat
    $tingkat_result = mysqli_query($conn, "SELECT * FROM tingkat ORDER BY id_tingkat ASC");

    // Mengambil data program keahlian
    $prodi_result = mysqli_query($conn, "SELECT * FROM program_keahlian ORDER BY id_program_keahlian ASC");

    // Mengambil data guru aktif (untuk wali kelas)
    $guru_result = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'Y' ORDER BY nama_pengguna ASC");
    ?>

    <div style="text-align:center; margin-bottom:20px;">
        <h2>Edit Data Kelas</h2>
    </div>

    <center>
        <form method="POST" action="../../process/kelas_process.php">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id_kelas" value="<?= $data['id_kelas'] ?>">

            <table cellpadding="10">
                <tr>
                    <td><label><b>Tingkat <span style="color:red;">*</span></b></label></td>
                    <td>
                        <select name="id_tingkat" style="width: 100%;" required>
                            <option value="">Pilih Tingkat</option>
                            <?php while ($t = mysqli_fetch_assoc($tingkat_result)) {
                                $selected = ($data['id_tingkat'] == $t['id_tingkat']) ? 'selected' : '';
                                echo "<option value=\"{$t['id_tingkat']}\" $selected>" . htmlspecialchars($t['tingkat']) . "</option>";
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label><b>Program Keahlian <span style="color:red;">*</span></b></label></td>
                    <td>
                        <select name="id_program_keahlian" style="width: 100%;" required>
                            <option value="">Pilih Program Keahlian</option>
                            <?php while ($p = mysqli_fetch_assoc($prodi_result)) {
                                $selected = ($data['id_program_keahlian'] == $p['id_program_keahlian']) ? 'selected' : '';
                                echo "<option value=\"{$p['id_program_keahlian']}\" $selected>" . htmlspecialchars($p['program_keahlian'] . ' - ' . $p['deskripsi']) . "</option>";
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label><b>Rombel <span style="color:red;">*</span></b></label></td>
                    <td><input type="number" name="rombel" min="1" max="10" value="<?= htmlspecialchars($data['rombel']) ?>" required style="width: 100%;" /></td>
                </tr>
                <tr>
                    <td><label><b>Wali Kelas <span style="color:red;">*</span></b></label></td>
                    <td>
                        <select name="kode_guru" style="width: 100%;" required>
                            <option value="">Pilih Wali Kelas</option>
                            <?php while ($g = mysqli_fetch_assoc($guru_result)) {
                                $selected = ($data['kode_guru'] == $g['kode_guru']) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($g['kode_guru']) . "\" $selected>" . htmlspecialchars($g['nama_pengguna']) . " (" . htmlspecialchars($g['jabatan']) . ")</option>";
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        <a href="list.php" style="margin-right:10px;">Kembali</a>
                        <button type="submit">Update Data</button>
                    </td>
                </tr>
            </table>
        </form>
    </center>

    <?php
}
include ROOTPATH . "/includes/footer.php";
?>
