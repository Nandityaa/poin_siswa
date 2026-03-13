<?php
include('../../config/config.php');
include ROOTPATH . '/layouts/header.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch Classes for Dropdown
$query_kelas = "SELECT kelas.id_kelas, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel 
                FROM kelas 
                JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
                JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian 
                ORDER BY tingkat.tingkat ASC, program_keahlian.program_keahlian ASC, kelas.rombel ASC";
$result_kelas = mysqli_query($conn, $query_kelas);

// Fetch Student Data with correct JOINs and ALIASES
$query = "SELECT 
            siswa.nis AS nis, siswa.nama_siswa AS nama_siswa, siswa.jenis_kelamin AS jenis_kelamin, siswa.alamat AS alamat, siswa.id_kelas AS id_kelas,
            ortu_wali.ayah AS ayah, ortu_wali.ibu AS ibu, ortu_wali.wali AS wali,
            ortu_wali.pekerjaan_ayah AS pekerjaan_ayah, ortu_wali.pekerjaan_ibu AS pekerjaan_ibu, ortu_wali.pekerjaan_wali AS pekerjaan_wali,
            ortu_wali.alamat_ayah AS alamat_ayah, ortu_wali.alamat_ibu AS alamat_ibu, ortu_wali.alamat_wali AS alamat_wali,
            ortu_wali.no_telp_ayah AS no_telp_ayah, ortu_wali.no_telp_ibu AS no_telp_ibu, ortu_wali.no_telp_wali AS no_telp_wali,
            kelas.id_kelas AS current_id_kelas
          FROM siswa 
          LEFT JOIN ortu_wali ON siswa.id_ortu_wali = ortu_wali.id_ortu_wali 
          LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas
          WHERE siswa.nis = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='alert alert-danger text-center mt-4'>Data siswa tidak ditemukan!</div>";
} else {
    ?>

<center>
    <h2>Edit Data Siswa</h2>

    <form method="POST" action="process.php">
        <input type="hidden" name="action" value="edit">

        <h3>Data Siswa</h3>
        <table cellpadding="10">
            <tr>
                <td><label><b>NIS</b></label></td>
                <td><input type="number" name="nis" value="<?= htmlspecialchars($data['nis']) ?>" readonly style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Nama Siswa <span style="color:red;">*</span></b></label></td>
                <td><input type="text" name="nama_siswa" value="<?= htmlspecialchars($data['nama_siswa']) ?>" required style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Jenis Kelamin <span style="color:red;">*</span></b></label></td>
                <td>
                    <select name="jenis_kelamin" required style="width:100%;">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki - Laki" <?= ($data['jenis_kelamin'] == 'Laki - Laki') ? 'selected' : '' ?>>Laki - Laki</option>
                        <option value="Perempuan" <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label><b>Kelas <span style="color:red;">*</span></b></label></td>
                <td>
                    <select name="id_kelas" required style="width:100%;">
                        <option value="">Pilih Kelas</option>
                        <?php
                        if ($result_kelas && mysqli_num_rows($result_kelas) > 0) {
                            mysqli_data_seek($result_kelas, 0);
                            while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
                                $nama_kelas = $row_kelas['tingkat'] . " " . $row_kelas['program_keahlian'] . " " . $row_kelas['rombel'];
                                $selected = ($data['id_kelas'] == $row_kelas['id_kelas']) ? 'selected' : '';
                                echo '<option value="' . $row_kelas['id_kelas'] . '" ' . $selected . '>' . $nama_kelas . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label><b>Alamat Siswa</b></label></td>
                <td><textarea name="alamat" rows="2" style="width:100%;"><?= htmlspecialchars($data['alamat']) ?></textarea></td>
            </tr>
        </table>

        <br>
        <h3>Data Orang Tua / Wali</h3>
        <table cellpadding="10">
            <tr>
                <td><label><b>Nama Ayah</b></label></td>
                <td><input type="text" name="ayah" value="<?= htmlspecialchars($data['ayah'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Pekerjaan Ayah</b></label></td>
                <td><input type="text" name="pekerjaan_ayah" value="<?= htmlspecialchars($data['pekerjaan_ayah'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>No Telp Ayah</b></label></td>
                <td><input type="number" name="no_telp_ayah" value="<?= htmlspecialchars($data['no_telp_ayah'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Alamat Ayah</b></label></td>
                <td><textarea name="alamat_ayah" rows="2" style="width:100%;"><?= htmlspecialchars($data['alamat_ayah'] ?? '') ?></textarea></td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td><label><b>Nama Ibu</b></label></td>
                <td><input type="text" name="ibu" value="<?= htmlspecialchars($data['ibu'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Pekerjaan Ibu</b></label></td>
                <td><input type="text" name="pekerjaan_ibu" value="<?= htmlspecialchars($data['pekerjaan_ibu'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>No Telp Ibu</b></label></td>
                <td><input type="number" name="no_telp_ibu" value="<?= htmlspecialchars($data['no_telp_ibu'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Alamat Ibu</b></label></td>
                <td><textarea name="alamat_ibu" rows="2" style="width:100%;"><?= htmlspecialchars($data['alamat_ibu'] ?? '') ?></textarea></td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td><label><b>Nama Wali</b></label></td>
                <td><input type="text" name="wali" value="<?= htmlspecialchars($data['wali'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Pekerjaan Wali</b></label></td>
                <td><input type="text" name="pekerjaan_wali" value="<?= htmlspecialchars($data['pekerjaan_wali'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>No Telp Wali</b></label></td>
                <td><input type="number" name="no_telp_wali" value="<?= htmlspecialchars($data['no_telp_wali'] ?? '') ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Alamat Wali</b></label></td>
                <td><textarea name="alamat_wali" rows="2" style="width:100%;"><?= htmlspecialchars($data['alamat_wali'] ?? '') ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <a href="index.php"><button type="button">Kembali</button></a>
                    <button type="submit">Update Data Siswa</button>
                </td>
            </tr>
        </table>
    </form>
</center>

    <?php
}
include ROOTPATH . '/layouts/footer.php';
?>