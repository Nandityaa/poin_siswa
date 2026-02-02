<?php
// Menentukan lokasi folder utama proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan file header (biasanya berisi tampilan atas halaman dan koneksi dasar)
include ROOTPATH . "/includes/header.php";
?>

<!-- Membuat tampilan form untuk menambah data siswa -->
<center>
    <h2>Tambah Data Siswa</h2>

    <!-- Form untuk mengirim data siswa baru ke file proses -->
    <form action="/poin_siswa/process/siswa_process.php" method="POST">
        <fieldset>
            <legend>Data Siswa</legend>
            <table cellpadding="10">

                <!-- Menyembunyikan input action agar file proses tahu ini adalah aksi 'add' dan akan di kirim ke siswa_process.php -->
                <input type="hidden" name="action" value="add" />

                <tr>
                    <td><input type="text" name="nis" autocomplete="off" required placeholder="NIS"/><br><br><br>
                    <input type="text" name="nama_siswa" autocomplete="off" required placeholder="Nama Siswa"/></td>
                    <!-- untuk memberikan jarak antara kolom -->
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td><textarea name="alamat_siswa" id="" cols="20" rows="5" autocomplete="off" required placeholder="Alamat Siswa"></textarea></td>
                </tr>

                <tr>
                    <td>Jenis Kelamin :</td>
                </tr>
                <tr>
                    <td><input type="radio" name="jenis_kelamin" value="Laki - Laki" required />Laki - Laki
                    <input type="radio" name="jenis_kelamin" value="Perempuan" required />Perempuan</td>
                    <!-- untuk memberikan jarak antara kolom -->
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <datalist id="kelas">
                        <?php
                                // Mengambil semua data kelas dari tabel 'kelas' JOIN 'program_keahlian', 'tingkat'
                                $query_kelas = mysqli_query($conn, "SELECT * FROM kelas JOIN program_keahlian USING(id_program_keahlian) JOIN tingkat USING(id_tingkat)");
                                while ($kelas = mysqli_fetch_assoc($query_kelas)) { 
                                    echo "<option value='" . $kelas['tingkat'] . ' ' . $kelas['program_keahlian'] . ' ' . $kelas['rombel'] . "'></option>"; 
                                }
                            ?>
                        </datalist>
                        <input list="kelas" id="kelas" name="kelas" placeholder="Kelas" autocomplete="off" required/>
                    </td>
                </tr>
            </table>
        </fieldset>






        <fieldset>
            <legend>Data Orang Tua / Wali (Opsional)</legend>
            <table cellpadding="10">
                <tr>
                    <td><input type="text" name="ayah" autocomplete="off" placeholder="Nama Ayah"/></td>
                    <td><input type="text" name="pekerjaan_ayah" autocomplete="off" placeholder="Pekerjaan Ayah"/></td>
                    <td><input type="number" name="telp_ayah" autocomplete="off" placeholder="No Telp Ayah"/></td>
                    <td><textarea name="alamat_ayah" id="" cols="20" rows="5" placeholder="Alamat Ayah"></textarea></td>
                </tr>

                <tr>
                    <td><input type="text" name="ibu" autocomplete="off" placeholder="Nama Ibu"/></td>
                    <td><input type="text" name="pekerjaan_ibu" autocomplete="off" placeholder="Pekerjaan Ibu"/></td>
                    <td><input type="number" name="telp_ibu" autocomplete="off" placeholder="No Telp Ibu"/></td>
                    <td><textarea name="alamat_ibu" id="" cols="20" rows="5" placeholder="Alamat Ibu"></textarea></td>
                </tr>

                <tr>
                    <td><input type="text" name="wali" autocomplete="off" placeholder="Nama Wali"/></td>
                    <td><input type="text" name="pekerjaan_wali" autocomplete="off" placeholder="Pekerjaan Wali"/></td>
                    <td><input type="number" name="telp_wali" autocomplete="off" placeholder="No Telp Wali"/></td>
                    <td><textarea name="alamat_wali" id="" cols="20" rows="5" placeholder="Alamat Wali"></textarea></td>
                </tr>

                <!-- Tombol untuk menyimpan data -->
                <tr>
                    <td colspan="4" align="right">
                        <button type="submit" style="float:right">Simpan</button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</center>

<?php
// Menyertakan file footer (biasanya berisi bagian bawah halaman)
include ROOTPATH . "/includes/footer.php";
?>

<!-- 
    🧠 Penjelasan Singkat:
	•	File ini digunakan untuk menampilkan form tambah siswa.
	•	Setelah pengguna mengisi data siswa, data akan dikirim ke /Poin_Pelanggaran_Siswa_XIIRPL4/process/siswa_process.php menggunakan metode POST.
	•	File header dan footer dipakai agar tampilan halaman tetap konsisten di seluruh situs. 
-->