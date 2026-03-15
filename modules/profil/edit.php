<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header
include ROOTPATH . "/layouts/header.php";

// Ambil username dan role dari cookie
$username = isset($_COOKIE['username']) ? mysqli_real_escape_string($conn, $_COOKIE['username']) : '';
$role = isset($_COOKIE['role']) ? $_COOKIE['role'] : '';

$is_siswa = ($role === 'siswa');
$data = null;

if ($is_siswa) {
    $query = "SELECT * FROM siswa WHERE nis = '$username'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
} else {
    $query = "SELECT * FROM guru WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
}

// Cek apakah ada pesan sukses
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<style>
.page-wrapper { font-family: 'Inter', sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.page-header { display: flex; align-items: center; gap: 12px; margin-bottom: 30px; }
.page-header i { font-size: 28px; color: var(--primary); }
.page-header h2 { margin: 0; font-size: 24px; font-weight: 700; color: #1e293b; letter-spacing: -0.5px; }
.data-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); padding: 30px; margin-bottom: 30px; }
.card-title { font-size: 18px; font-weight: 600; color: #1e293b; margin: 0 0 20px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; }

.form-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 8px; }
.form-group label { font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.form-control { padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 14px; font-family: 'Inter', sans-serif; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: #f8fafc; color: #334155; }
.form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); background: white; }
.form-control[readonly] { background: #f1f5f9; cursor: not-allowed; color: #94a3b8; }

.btn-primary { background: linear-gradient(135deg, var(--primary), #8b5cf6); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 8px; }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(79, 70, 229, 0.3); }

.alert { border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 10px; }
.alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.alert i { font-size: 18px; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <i class="fa-solid fa-user-pen"></i>
        <h2>Pengaturan Profil</h2>
    </div>

    <?php if (!$data): ?>
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            Data profil tidak ditemukan! <a href="/poin_siswa/modules/dashboard/<?= $is_siswa ? 'siswa.php' : 'index.php' ?>" style="margin-left:auto; font-weight:600; color:inherit;">Kembali ke Dashboard</a>
        </div>
    <?php else: ?>

        <?php if ($success == 'profil'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Profil berhasil diperbarui!</div>
        <?php elseif ($success == 'password'): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Password berhasil diubah!</div>
        <?php elseif ($error == 'password_lama'): ?>
            <div class="alert alert-error"><i class="fa-solid fa-circle-xmark"></i> Password lama salah!</div>
        <?php elseif ($error == 'password_beda'): ?>
            <div class="alert alert-error"><i class="fa-solid fa-circle-xmark"></i> Password baru dan konfirmasi tidak cocok!</div>
        <?php endif; ?>

        <?php if (!$is_siswa): ?>
        <div class="data-card">
            <h3 class="card-title">Informasi Pribadi</h3>
            <form method="POST" action="/poin_siswa/modules/profil/process.php">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="kode_guru" value="<?= htmlspecialchars($data['kode_guru']) ?>">
                <input type="hidden" name="is_siswa" value="0">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Kode Guru</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['kode_guru']) ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Nama Pengguna <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="nama_pengguna" class="form-control" value="<?= htmlspecialchars($data['nama_pengguna']) ?>" required />
                    </div>
                    <div class="form-group">
                        <label>Username <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required />
                    </div>
                    <div class="form-group">
                        <label>No. Telp</label>
                        <input type="text" name="telp" class="form-control" value="<?= htmlspecialchars($data['telp']) ?>" />
                    </div>
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['jabatan']) ?>" readonly />
                    </div>
                </div>

                <div style="margin-top: 24px; text-align: right;">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="data-card">
            <h3 class="card-title">Informasi Siswa (Read Only)</h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>NIS</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($data['nis']) ?>" readonly />
                </div>
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama_siswa']) ?>" readonly />
                </div>
            </div>
            <div style="margin-top:20px; font-size:13px; color:#64748b; background:#f8fafc; padding:15px; border-radius:10px;">
                <i class="fa-solid fa-circle-info"></i> Jika terdapat kesalahan data diri, harap hubungi administrator atau guru wali.
            </div>
        </div>
        <?php endif; ?>

        <div class="data-card">
            <h3 class="card-title">Ganti Password</h3>
            <form method="POST" action="/poin_siswa/modules/profil/process.php">
                <input type="hidden" name="action" value="change_password">
                <input type="hidden" name="identifier" value="<?= htmlspecialchars($is_siswa ? $data['nis'] : $data['kode_guru']) ?>">
                <input type="hidden" name="is_siswa" value="<?= $is_siswa ? '1' : '0' ?>">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Password Lama <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password_lama" class="form-control" required placeholder="Masukkan password saat ini" />
                    </div>
                    <div class="form-group">
                        <label>Password Baru <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password_baru" class="form-control" required placeholder="Minimal 6 karakter" />
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span style="color:#ef4444;">*</span></label>
                        <input type="password" name="password_konfirmasi" class="form-control" required placeholder="Ulangi password baru" />
                    </div>
                </div>

                <div style="margin-top: 24px; text-align: right;">
                    <button type="submit" class="btn-primary"><i class="fa-solid fa-key"></i> Perbarui Password</button>
                </div>
            </form>
        </div>

    <?php endif; ?>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
