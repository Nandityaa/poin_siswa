<?php
include('../../config/config.php');
include ROOTPATH . '/layouts/header.php';

// RBAC: Data master untuk admin_bk, wakasek, kepsek
$allowed_master = ['admin_bk', 'wakasek', 'kepsek'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_master)) {
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/index.php';</script>";
    exit;
}

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// Fetch Guru Data
$query = "SELECT * FROM guru WHERE kode_guru = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div style='text-align:center; margin-top:40px;'><h3>Data guru tidak ditemukan!</h3><a href='index.php'>Kembali</a></div>";
} else {
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
                <i class="fa-solid fa-user-pen"></i>
                <h2>Edit Data Guru</h2>
            </div>
            
            <div class="form-body">
                <form method="POST" action="process.php">
                    <input type="hidden" name="action" value="edit">

                    <div class="form-group">
                        <label class="form-label">Kode Guru</label>
                        <input type="text" name="kode_guru" class="form-control" value="<?php echo htmlspecialchars($data['kode_guru']); ?>" readonly />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span style="color:red;">*</span></label>
                        <input type="text" name="nama_guru" class="form-control" value="<?php echo htmlspecialchars($data['nama_pengguna']); ?>" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username <span style="color:red;">*</span></label>
                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data['username']); ?>" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan" class="form-control">
                            <option value="">-- Pilih Jabatan --</option>
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
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="number" name="telp" class="form-control" value="<?php echo htmlspecialchars($data['telp']); ?>" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role Akses <i>(Abaikan jika bukan RBAC)</i></label>
                        <select name="role" class="form-control">
                            <option value="">-- Pilih Role --</option>
                            <option value="Guru" <?php echo ($data['role'] == 'Guru') ? 'selected' : ''; ?>>Guru</option>
                            <option value="bk" <?php echo ($data['role'] == 'bk') ? 'selected' : ''; ?>>BK</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Aktif</label>
                        <select name="aktif" class="form-control">
                            <option value="Y" <?php echo ($data['aktif'] == 'Y') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="N" <?php echo ($data['aktif'] == 'N') ? 'selected' : ''; ?>>Tidak Aktif</option>
                        </select>
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
include ROOTPATH . '/layouts/footer.php';
?>
