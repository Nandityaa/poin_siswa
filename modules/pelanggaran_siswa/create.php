<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/layouts/header.php";

// RBAC: Entri pelanggaran untuk semua role staf
$allowed_roles = ['admin_bk', 'wakasek', 'kepsek', 'guru'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_roles)) {
    $redirect = (isset($_COOKIE['role']) && $_COOKIE['role'] === 'siswa') ? 'siswa.php' : 'index.php';
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/$redirect';</script>";
    exit;
}

// Mengambil data siswa untuk datalist
$siswa_result = mysqli_query($conn, "SELECT nis, nama_siswa FROM siswa WHERE status = 'aktif' ORDER BY nama_siswa ASC");

// Mengambil data jenis pelanggaran
$jenis_result = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY id_jenis_pelanggaran ASC");
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
textarea.form-control { resize: vertical; min-height: 80px; }

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
            <i class="fa-solid fa-file-pen"></i>
            <h2>Entri Pelanggaran Siswa</h2>
        </div>
        
        <div class="form-body">
            <form action="/poin_siswa/modules/pelanggaran_siswa/process.php" method="POST">
                <input type="hidden" name="action" value="add" />

                <div class="form-group">
                    <label class="form-label">NIS Siswa <span style="color:red;">*</span></label>
                    <input type="text" name="nis" list="nis_list" class="form-control" placeholder="Pilih / Ketik NIS" autocomplete="off" required />
                    <datalist id="nis_list">
                        <?php while ($s = mysqli_fetch_assoc($siswa_result)) { ?>
                            <option value="<?= $s['nis'] ?>"><?= $s['nis'] ?> - <?= htmlspecialchars($s['nama_siswa']) ?></option>
                        <?php } ?>
                    </datalist>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Pelanggaran <span style="color:red;">*</span></label>
                    <select name="id_jenis_pelanggaran" class="form-control" required>
                        <option value="">-- Pilih Jenis Pelanggaran --</option>
                        <?php while ($j = mysqli_fetch_assoc($jenis_result)) { ?>
                            <option value="<?= $j['id_jenis_pelanggaran'] ?>"><?= htmlspecialchars($j['jenis']) ?> (Poin: <?= $j['poin'] ?>)</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal <span style="color:red;">*</span></label>
                    <input type="datetime-local" name="tanggal" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" required />
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan Tambahan <span style="color:red;">*</span></label>
                    <textarea name="keterangan" class="form-control" rows="3" required placeholder="Tulis keterangan detail mengenai pelanggaran"></textarea>
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> Simpan Pelanggaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Menyertakan bagian footer (penutup halaman)
include ROOTPATH . "/layouts/footer.php";
?>
