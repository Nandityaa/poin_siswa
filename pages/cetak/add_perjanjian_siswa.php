<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";
?>

<center>
    <h2>Surat Perjanjian Siswa</h2>

    <!-- Form Pilih NIS -->
    <form action="" method="post">
        <datalist id="nis" name="nis">
            <?php
            $result = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['nis'] . "'>" . $row['nis'] . " - " . $row['nama_siswa'] . "</option>";
            }
            ?>
        </datalist>
        <input type="text" name="nis" value="<?php if(isset($_POST['nis'])) { echo $_POST['nis']; } else { echo ""; } ?>" list="nis" placeholder="pilih NIS" autocomplete="off">
        <input class="btn-warning" style="color:#fff; font-weight:bold" type="submit" value="cek">
    </form>

    <br><br>

    <!-- Form Input Data Orang Tua -->
    <?php
    if(isset($_POST['nis'])) {
        $nis = $_POST['nis'];
        $result_ortu_wali = mysqli_query($conn, "SELECT * FROM siswa JOIN ortu_wali USING(id_ortu_wali) WHERE nis = '$nis'");
        $row_ortu_wali = mysqli_fetch_assoc($result_ortu_wali);
    ?>

    <!-- Form Input Data Ayah -->
    <?php
    if(!empty($row_ortu_wali["ayah"])) {
    ?>
    <form action="surat_perjanjian_siswa.php" method="post">
        <fieldset style="width:20%">
            <legend>Data Ayah</legend>
            <input type="hidden" name="nis" value="<?php echo $nis; ?>">
            <table cellspacing="10">
                <tr>
                    <td>Nama Ayah</td>
                    <td>:</td>
                    <td><input type="text" name="nama_ortu" value="<?php echo $row_ortu_wali['ayah']; ?>" required></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><input type="text" name="pekerjaan" value="<?php echo $row_ortu_wali['pekerjaan_ayah']; ?>" required></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><textarea name="alamat" id="" required><?php echo $row_ortu_wali['alamat_ayah']; ?></textarea></td>
                </tr>
                <tr>
                    <td>No. Hp/Telp</td>
                    <td>:</td>
                    <td><input type="number" name="no_telp" value="<?php echo $row_ortu_wali['no_telp_ayah']; ?>" required></td>
                </tr>
            </table>
            <br>
            <input type="submit" value="cetak surat">
        </fieldset>
    </form>
    <?php
    }
    ?>

    <!-- Form Input Data Ibu -->
    <?php
    if(!empty($row_ortu_wali["ibu"])) {
    ?>
    <form action="surat_perjanjian_siswa.php" method="post">
        <fieldset style="width:20%">
            <legend>Data Ibu</legend>
            <input type="hidden" name="nis" value="<?php echo $nis; ?>">
            <table cellspacing="10">
                <tr>
                    <td>Nama Ibu</td>
                    <td>:</td>
                    <td><input type="text" name="nama_ortu" value="<?php echo $row_ortu_wali['ibu']; ?>" required></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><input type="text" name="pekerjaan" value="<?php echo $row_ortu_wali['pekerjaan_ibu']; ?>" required></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><textarea name="alamat" id="" required><?php echo $row_ortu_wali['alamat_ibu']; ?></textarea></td>
                </tr>
                <tr>
                    <td>No. Hp/Telp</td>
                    <td>:</td>
                    <td><input type="number" name="no_telp" value="<?php echo $row_ortu_wali['no_telp_ibu']; ?>" required></td>
                </tr>
            </table>
            <br>
            <input type="submit" value="cetak surat">
        </fieldset>
    </form>
    <?php
    }
    ?>

    <!-- Form Input Data Wali -->
    <?php
    if(!empty($row_ortu_wali["wali"])) {
    ?>
    <form action="surat_perjanjian_siswa.php" method="post">
        <fieldset style="width:20%">
            <legend>Data Wali</legend>
            <input type="hidden" name="nis" value="<?php echo $nis; ?>">
            <table cellspacing="10">
                <tr>
                    <td>Nama Wali</td>
                    <td>:</td>
                    <td><input type="text" name="nama_ortu" value="<?php echo $row_ortu_wali['wali']; ?>" required></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><input type="text" name="pekerjaan" value="<?php echo $row_ortu_wali['pekerjaan_wali']; ?>" required></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><textarea name="alamat" id="" required><?php echo $row_ortu_wali['alamat_wali']; ?></textarea></td>
                </tr>
                <tr>
                    <td>No. Hp/Telp</td>
                    <td>:</td>
                    <td><input type="number" name="no_telp" value="<?php echo $row_ortu_wali['no_telp_wali']; ?>" required></td>
                </tr>
            </table>
            <br>
            <input type="submit" value="cetak surat">
        </fieldset>
    </form>
    <?php
    }
    ?>

    <!-- Form Input Data Jika Tidak Ada Data Orang Tua -->
    <?php
    if(empty($row_ortu_wali["ayah"]) && empty($row_ortu_wali["ibu"]) && empty($row_ortu_wali["wali"])) {
    ?>
    <form action="surat_perjanjian_siswa.php" method="post">
        <fieldset style="width:20%">
            <legend>Data Orang Tua / Wali</legend>
            <input type="hidden" name="nis" value="<?php echo $nis; ?>">
            <table cellspacing="10">
                <tr>
                    <td>Nama Orang Tua / Wali</td>
                    <td>:</td>
                    <td><input type="text" name="nama_ortu" required></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><input type="text" name="pekerjaan" required></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><textarea name="alamat" id="" required></textarea></td>
                </tr>
                <tr>
                    <td>No. Hp/Telp</td>
                    <td>:</td>
                    <td><input type="number" name="no_telp" required></td>
                </tr>
            </table>
            <br>
            <input type="submit" value="cetak surat">
        </fieldset>
    </form>
    <?php
    }
    ?>

    <?php
    }
    ?>
</center>

<?php
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php";
?>
