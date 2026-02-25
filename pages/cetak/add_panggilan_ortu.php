<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

?>


<center>
    <h2>Surat Panggilan Orang Tua</h2>


    <!-- Form Pilih NIS -->
    <form action="" method="post">
        <!-- datalist ini berfungsi untuk menampilkan data nis dan nama siswa yang akan dipilih -->
        <datalist id="nis" name="nis">
            <?php 
            $result = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['nis'] . "'>" . $row['nis'] . " - " . $row['nama_siswa'] . "</option>";
            }
            ?>
        </datalist>
        <!-- input ini berfungsi untuk menampilkan data nis dan nama siswa yang akan dipilih -->
        <input type="text" name="nis" value="<?php if(isset($_POST['nis'])) { echo $_POST['nis']; } else { echo ""; } ?>" list="nis" placeholder="pilih NIS" autocomplete="off">

        <input class="btn-warning" style="color:#000; font-weight:bold" type="submit" value="cek">
    </form>


    <br><br>
    
    

    <?php
    // jika nis sudah diinput
    if(isset($_POST['nis'])) {
        $nis = $_POST['nis'];

        // query untuk menampilkan data siswa dan orang tua
        $result_ortu_wali = mysqli_query($conn, "SELECT * FROM siswa JOIN ortu_wali USING(id_ortu_wali) WHERE nis = '$nis'");
        $row_ortu_wali = mysqli_fetch_assoc($result_ortu_wali);
        ?>

        <!-- form input data orang tua -->
        <form action="surat_panggilan_ortu.php" method="post">
            <fieldset style="width:20%">
            <legend>Input</legend>
            <!-- input ini berfungsi untuk menyimpan data nis -->
            <input type="hidden" name="nis" value="<?php echo $nis; ?>">

            <table cellspacing="10">
                <tr>
                    <td>No Surat</td>
                    <td>:</td>
                    <td><input type="number" name="no_surat" required></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><input type="datetime-local" name="tanggal" required></td>
                </tr>
                <tr>
                    <td>Keperluan</td>
                    <td>:</td>
                    <td><textarea name="keperluan" id="" required></textarea></td>
                </tr>
            </table>
            <br>
            <!-- tombol ini berfungsi untuk mencetak surat akan di kirim ke surat_panggilan_ortu.php-->
            <input type="submit" value="cetak surat">
            </fieldset>
        </form>


    <?php
    }
    ?>
    
</center>


<?php 
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php"; 
?>
