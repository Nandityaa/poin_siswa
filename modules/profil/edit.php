<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header
include ROOTPATH . "/layouts/header.php";

// Ambil data guru berdasarkan username dari cookie
$username = isset($_COOKIE['username']) ? mysqli_real_escape_string($conn, $_COOKIE['username']) : '';
$query = "SELECT * FROM guru WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Cek apakah ada pesan sukses
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

if (!$data) {
    echo "<div style='text-align:center; margin-top:40px;'><h3>Data profil tidak ditemukan!</h3><a href='/poin_siswa/modules/dashboard/index.php'>Kembali ke Dashboard</a></div>";
} else {
?>

<center>
    <h2>Edit Profil</h2>

    <?php if ($success == 'profil') { ?>
        <p style="color: green; font-weight: bold;">✅ Profil berhasil diperbarui!</p>
    <?php } elseif ($success == 'password') { ?>
        <p style="color: green; font-weight: bold;">✅ Password berhasil diubah!</p>
    <?php } elseif ($error == 'password_lama') { ?>
        <p style="color: red; font-weight: bold;">❌ Password lama salah!</p>
    <?php } elseif ($error == 'password_beda') { ?>
        <p style="color: red; font-weight: bold;">❌ Password baru dan konfirmasi tidak cocok!</p>
    <?php } ?>

    <!-- Form Edit Data Profil -->
    <form method="POST" action="/poin_siswa/modules/profil/process.php">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="kode_guru" value="<?= htmlspecialchars($data['kode_guru']) ?>">

        <table cellpadding="10">
            <tr>
                <td><label><b>Kode Guru</b></label></td>
                <td><input type="text" value="<?= htmlspecialchars($data['kode_guru']) ?>" readonly style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Nama</b></label></td>
                <td><input type="text" name="nama_pengguna" value="<?= htmlspecialchars($data['nama_pengguna']) ?>" required style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Username</b></label></td>
                <td><input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>No. Telp</b></label></td>
                <td><input type="number" name="telp" value="<?= htmlspecialchars($data['telp']) ?>" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Jabatan</b></label></td>
                <td><input type="text" value="<?= htmlspecialchars($data['jabatan']) ?>" readonly style="width:100%;" /></td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <button type="submit">Simpan Perubahan</button>
                </td>
            </tr>
        </table>
    </form>

    <br>
    <hr style="width: 50%;">
    <h3>Ganti Password</h3>

    <!-- Form Ganti Password -->
    <form method="POST" action="/poin_siswa/modules/profil/process.php">
        <input type="hidden" name="action" value="change_password">
        <input type="hidden" name="kode_guru" value="<?= htmlspecialchars($data['kode_guru']) ?>">

        <table cellpadding="10">
            <tr>
                <td><label><b>Password Lama <span style="color:red;">*</span></b></label></td>
                <td><input type="password" name="password_lama" required placeholder="Masukkan password lama" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Password Baru <span style="color:red;">*</span></b></label></td>
                <td><input type="password" name="password_baru" required placeholder="Masukkan password baru" style="width:100%;" /></td>
            </tr>
            <tr>
                <td><label><b>Konfirmasi Password <span style="color:red;">*</span></b></label></td>
                <td><input type="password" name="password_konfirmasi" required placeholder="Ulangi password baru" style="width:100%;" /></td>
            </tr>
            <tr>
                <td colspan="2" align="right">
                    <button type="submit">Ubah Password</button>
                </td>
            </tr>
        </table>
    </form>
</center>

<?php
}
include ROOTPATH . "/layouts/footer.php";
?>
