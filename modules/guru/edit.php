<?php
include('../../config/config.php');
include ROOTPATH . '/layouts/header.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// Fetch Guru Data
$query = "SELECT * FROM guru WHERE kode_guru = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div style='text-align:center; margin-top:40px;'><h3>Data guru tidak ditemukan!</h3><a href='index.php'>Kembali</a></div>";
} else {
    ?>

    <div style="text-align:center; margin-bottom:20px;">
        <h2>Edit Data Guru</h2>
    </div>

    <center>
        <form method="POST" action="process.php">
            <input type="hidden" name="action" value="edit">

            <table cellpadding="10">
                <tr>
                    <td><label><b>Kode Guru</b></label></td>
                    <td><input type="text" name="kode_guru" value="<?php echo htmlspecialchars($data['kode_guru']); ?>" readonly /></td>
                </tr>
                <tr>
                    <td><label><b>Nama Guru <span style="color:red;">*</span></b></label></td>
                    <td><input type="text" name="nama_guru" value="<?php echo htmlspecialchars($data['nama_pengguna']); ?>" required /></td>
                </tr>
                <tr>
                    <td><label><b>Username <span style="color:red;">*</span></b></label></td>
                    <td><input type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required /></td>
                </tr>
                <tr>
                    <td><label><b>Jabatan</b></label></td>
                    <td>
                        <select name="jabatan" style="width: 100%;">
                            <option value="">Pilih Jabatan</option>
                            <?php
                            $jabatan_list = [
                                'Guru Mapel', 'Kepala Sekolah', 'Waka Kurikulum', 'Waka Kesiswaan',
                                'Waka Sarana Prasarana', 'Waka Humas', 'Komka AN', 'Komka RPL',
                                'Komka DKV', 'Komka TKJ', 'Komka BD', 'Guru BK XII', 'Guru BK XI',
                                'Guru BK X', 'Ketua Lab'
                            ];
                            foreach ($jabatan_list as $jbt) {
                                $selected = ($data['jabatan'] == $jbt) ? 'selected' : '';
                                echo "<option value=\"$jbt\" $selected>$jbt</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label><b>No. Telepon</b></label></td>
                    <td><input type="number" name="telp" value="<?php echo htmlspecialchars($data['telp']); ?>" /></td>
                </tr>
                <tr>
                    <td><label><b>Role</b></label></td>
                    <td>
                        <select name="role" style="width: 100%;">
                            <option value="">Pilih Role</option>
                            <option value="Guru" <?php echo ($data['role'] == 'Guru') ? 'selected' : ''; ?>>Guru</option>
                            <option value="bk" <?php echo ($data['role'] == 'bk') ? 'selected' : ''; ?>>BK</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label><b>Status Aktif</b></label></td>
                    <td>
                        <select name="aktif" style="width: 100%;">
                            <option value="Y" <?php echo ($data['aktif'] == 'Y') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="N" <?php echo ($data['aktif'] == 'N') ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="right">
                        <a href="index.php" style="margin-right:10px;">Kembali</a>
                        <button type="submit">Update Data</button>
                    </td>
                </tr>
            </table>
        </form>
    </center>

    <?php
}
include ROOTPATH . '/layouts/footer.php';
?>
