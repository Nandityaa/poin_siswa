<?php
// Menentukan lokasi folder utama proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan file header
include ROOTPATH . "/layouts/header.php";

// RBAC: Data master untuk admin_bk, wakasek, kepsek
$allowed_master = ['admin_bk', 'wakasek', 'kepsek'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_master)) {
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/index.php';</script>";
    exit;
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// Fetch data jenis pelanggaran
$query = "SELECT * FROM jenis_pelanggaran WHERE id_jenis_pelanggaran = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div style='text-align:center; margin-top:40px;'><h3>Data jenis pelanggaran tidak ditemukan!</h3><a href='index.php'>Kembali</a></div>";
} else {
?>

<!-- Membuat tampilan form untuk edit data jenis pelanggaran -->
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
            <i class="fa-solid fa-triangle-exclamation"></i>
            <h2>Edit Data Jenis Pelanggaran</h2>
        </div>
        
        <div class="form-body">
            <form action="/poin_siswa/modules/jenis_pelanggaran/process.php" method="POST">
                <input type="hidden" name="action" value="edit" />
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id_jenis_pelanggaran']); ?>" />

                <div class="form-group">
                    <label class="form-label">Nama Pelanggaran <span style="color:red;">*</span></label>
                    <input type="text" name="nama_pelanggaran" class="form-control" value="<?php echo htmlspecialchars($data['jenis']); ?>" required />
                </div>

                <div class="form-group">
                    <label class="form-label">Poin Pelanggaran <span style="color:red;">*</span></label>
                    <input type="number" name="poin" class="form-control" value="<?php echo htmlspecialchars($data['poin']); ?>" required />
                </div>

                <div class="form-actions">
                    <a href="index.php" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
}

// Menyertakan file footer
include ROOTPATH . "/layouts/footer.php";
?>
