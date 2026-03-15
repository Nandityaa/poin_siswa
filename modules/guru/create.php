<?php
// Menentukan lokasi folder utama proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan file header (biasanya berisi tampilan atas halaman dan koneksi dasar)
include ROOTPATH . "/layouts/header.php";

// RBAC: Data master untuk admin_bk, wakasek, kepsek
$allowed_master = ['admin_bk', 'wakasek', 'kepsek'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_master)) {
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/index.php';</script>";
    exit;
}
?>

<style>
.form-page { font-family: 'Inter', sans-serif; max-width: 600px; margin: 40px auto; padding: 0 20px; animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.form-card {
    background: white; border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05); overflow: hidden;
}
.form-header {
    background: #1a1a1a;
    padding: 24px 30px; color: white; display:flex; align-items:center; gap: 12px;
}
.form-header h2 { margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.5px; }
.form-header i { font-size: 22px; opacity: 0.8; }

.form-body { padding: 30px; }
.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text); font-size: 14px; }
.form-control {
    width: 100%; padding: 12px 16px; font-size: 14px;
    border: 1px solid #d1d5db; border-radius: 10px;
    background: #f9fafb; color: var(--text); transition: all 0.2s; box-sizing: border-box;
}
.form-control:focus { outline: none; border-color: #000; background: white; box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05); }
.form-control:read-only { cursor: not-allowed; opacity: 0.8; }

.form-actions { display: flex; gap: 12px; margin-top: 30px; }
.btn {
    padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; transition: all 0.2s; border: none; text-decoration: none; display:inline-flex; align-items:center; justify-content:center; gap:8px; width: 100%;
}
.btn-save { background: #1a1a1a; color: white; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
.btn-save:hover { background: #000; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15); }
.btn-cancel { background: #f1f5f9; color: #475569; }
.btn-cancel:hover { background: #e2e8f0; color: #1e293b; }
</style>

<div class="form-page">
    <div class="form-card">
        <div class="form-header">
            <i class="fa-solid fa-user-plus"></i>
            <h2>Tambah Data Guru</h2>
        </div>
        
        <div class="form-body">
            <form action="/poin_siswa/modules/guru/process.php" method="POST">
                <input type="hidden" name="action" value="add" />

                <?php
                // Mengambil kode guru terakhir
                $result = mysqli_query($conn, "SELECT kode_guru FROM guru ORDER BY kode_guru DESC LIMIT 1");
                $row = mysqli_fetch_assoc($result);
                $kode_guru = $row['kode_guru'];
                $kode_guru = explode(".", $kode_guru);
                $kode_guru = str_pad($kode_guru[1] + 1, 3, "0", STR_PAD_LEFT);
                ?>
                
                <div class="form-group">
                    <label class="form-label">Kode Guru</label>
                    <input type="text" name="kode_guru" class="form-control" autocomplete="off" value="0021.<?=$kode_guru?>" required readonly/>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_guru" class="form-control" autocomplete="off" required placeholder="Masukkan nama lengkap beserta gelar"/>
                </div>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" autocomplete="off" required placeholder="Masukkan username untuk login"/>
                </div>

                <div class="form-group">
                    <label class="form-label">Jabatan</label>
                    <select name="jabatan" class="form-control" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Guru Mapel">Guru Mapel</option>
                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                        <option value="Waka Kurikulum">Waka Kurikulum</option>
                        <option value="Waka Kesiswaan">Waka Kesiswaan</option>
                        <option value="Waka Sarana Prasarana">Waka Sarana Prasarana</option>
                        <option value="Waka Humas">Waka Humas</option>
                        <option value="Komka AN">Komka AN</option>
                        <option value="Komka RPL">Komka RPL</option>
                        <option value="Komka DKV">Komka DKV</option>
                        <option value="Komka TKJ">Komka TKJ</option>
                        <option value="Komka BD">Komka BD</option>
                        <option value="Guru BK XII">Guru BK XII</option>
                        <option value="Guru BK XI">Guru BK XI</option>
                        <option value="Guru BK X">Guru BK X</option>                            
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">No. Telepon</label>
                    <input type="number" name="telp" class="form-control" autocomplete="off" required placeholder="Contoh: 081234567890"/>
                </div>

                <div class="form-group">
                    <label class="form-label">Role Akses <i>(Abaikan jika bukan RBAC)</i></label>
                    <select name="role" class="form-control" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="guru">Guru Biasa (Default)</option>
                        <option value="bk">Bimbingan Konseling (BK)</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="/poin_siswa/modules/guru/index.php" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Menyertakan file footer (biasanya berisi bagian bawah halaman)
include ROOTPATH . "/layouts/footer.php";
?>

<!-- 
    🧠 Penjelasan Singkat:
	•	File ini digunakan untuk menampilkan form tambah guru.
	•	Setelah pengguna mengisi data guru, data akan dikirim ke /poin_siswa/modules/guru/process.php menggunakan metode POST.
	•	File header dan footer dipakai agar tampilan halaman tetap konsisten di seluruh situs. 
-->