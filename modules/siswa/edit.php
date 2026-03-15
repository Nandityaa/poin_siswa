<?php
include('../../config/config.php');
include ROOTPATH . '/layouts/header.php';

// RBAC: Data master untuk admin_bk, wakasek, kepsek
$allowed_master = ['admin_bk', 'wakasek', 'kepsek'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_master)) {
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/index.php';</script>";
    exit;
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch Classes for Dropdown
$query_kelas = "SELECT kelas.id_kelas, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel 
                FROM kelas 
                JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
                JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian 
                ORDER BY tingkat.tingkat ASC, program_keahlian.program_keahlian ASC, kelas.rombel ASC";
$result_kelas = mysqli_query($conn, $query_kelas);

// Fetch Student Data with correct JOINs and ALIASES
$query = "SELECT 
            siswa.nis AS nis, siswa.nama_siswa AS nama_siswa, siswa.jenis_kelamin AS jenis_kelamin, siswa.alamat AS alamat, siswa.id_kelas AS id_kelas,
            ortu_wali.ayah AS ayah, ortu_wali.ibu AS ibu, ortu_wali.wali AS wali,
            ortu_wali.pekerjaan_ayah AS pekerjaan_ayah, ortu_wali.pekerjaan_ibu AS pekerjaan_ibu, ortu_wali.pekerjaan_wali AS pekerjaan_wali,
            ortu_wali.alamat_ayah AS alamat_ayah, ortu_wali.alamat_ibu AS alamat_ibu, ortu_wali.alamat_wali AS alamat_wali,
            ortu_wali.no_telp_ayah AS no_telp_ayah, ortu_wali.no_telp_ibu AS no_telp_ibu, ortu_wali.no_telp_wali AS no_telp_wali,
            kelas.id_kelas AS current_id_kelas
          FROM siswa 
          LEFT JOIN ortu_wali ON siswa.id_ortu_wali = ortu_wali.id_ortu_wali 
          LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas
          WHERE siswa.nis = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='alert alert-danger text-center mt-4'>Data siswa tidak ditemukan!</div>";
} else {
    ?>

<style>
.form-page { font-family: 'Inter', sans-serif; max-width: 800px; margin: 40px auto; padding: 0 20px; animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.form-card {
    background: white; border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05); overflow: hidden;
    margin-bottom: 30px;
}
.form-header {
    background: #1a1a1a;
    padding: 24px 30px; color: white; display:flex; align-items:center; justify-content: space-between;
}
.form-header-title { display:flex; align-items:center; gap: 12px; }
.form-header h2 { margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.5px; }
.form-header i { font-size: 22px; opacity: 0.8; }

.form-body { padding: 30px; }
.form-section-title { font-size: 16px; font-weight: 700; color: var(--text); border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px; margin-top: 30px; }
.form-section-title:first-child { margin-top: 0; }

.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 15px; }

.form-group { margin-bottom: 20px; }
.form-label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text); font-size: 14px; }
.form-control {
    width: 100%; padding: 12px 16px; font-size: 14px;
    border: 1px solid #d1d5db; border-radius: 10px;
    background: #f9fafb; color: var(--text); transition: all 0.2s; box-sizing: border-box;
}
.form-control:focus { outline: none; border-color: #000; background: white; box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05); }
.form-control:read-only { cursor: not-allowed; opacity: 0.8; }
textarea.form-control { resize: vertical; min-height: 100px; }

.form-actions { display: flex; gap: 12px; justify-content: flex-end; padding: 24px 30px; background: #f8fafc; border-top: 1px solid #f1f5f9; }
.btn {
    padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px;
    cursor: pointer; transition: all 0.2s; border: none; text-decoration: none; display:inline-flex; align-items:center; justify-content:center; gap:8px;
}
.btn-save { background: #1a1a1a; color: white; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); }
.btn-save:hover { background: #000; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15); }
.btn-cancel { background: white; color: #475569; border: 1px solid #cbd5e1; }
.btn-cancel:hover { background: #f1f5f9; color: #1e293b; }

@media (max-width: 768px) {
    .grid-2, .grid-4 { grid-template-columns: 1fr; }
}
</style>

<div class="form-page">
    <form method="POST" action="process.php">
        <input type="hidden" name="action" value="edit">
        
        <div class="form-card">
            <div class="form-header">
                <div class="form-header-title">
                    <i class="fa-solid fa-user-pen"></i>
                    <h2>Edit Data Siswa</h2>
                </div>
            </div>
            
            <div class="form-body">
                <div class="form-section-title">Informasi Pribadi</div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Nomor Induk Siswa (NIS)</label>
                        <input type="number" name="nis" class="form-control" value="<?= htmlspecialchars($data['nis']) ?>" readonly />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap Siswa <span style="color:red;">*</span></label>
                        <input type="text" name="nama_siswa" class="form-control" value="<?= htmlspecialchars($data['nama_siswa']) ?>" required />
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span style="color:red;">*</span></label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki - Laki" <?= ($data['jenis_kelamin'] == 'Laki - Laki') ? 'selected' : '' ?>>Laki - Laki</option>
                            <option value="Perempuan" <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    
                     <div class="form-group">
                        <label class="form-label">Kelas <span style="color:red;">*</span></label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            <?php
                            if ($result_kelas && mysqli_num_rows($result_kelas) > 0) {
                                mysqli_data_seek($result_kelas, 0);
                                while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
                                    $nama_kelas = htmlspecialchars($row_kelas['tingkat'] . " " . $row_kelas['program_keahlian'] . " " . $row_kelas['rombel']);
                                    $selected = ($data['current_id_kelas'] == $row_kelas['id_kelas']) ? 'selected' : '';
                                    echo '<option value="' . $row_kelas['id_kelas'] . '" ' . $selected . '>' . $nama_kelas . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($data['alamat']) ?></textarea>
                </div>

                <div class="form-section-title">Data Orang Tua / Wali (Opsional)</div>
                
                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Nama Ayah</label>
                        <input type="text" name="ayah" class="form-control" value="<?= htmlspecialchars($data['ayah'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" class="form-control" value="<?= htmlspecialchars($data['pekerjaan_ayah'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telp Ayah</label>
                        <input type="number" name="no_telp_ayah" class="form-control" value="<?= htmlspecialchars($data['no_telp_ayah'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Ayah</label>
                        <textarea name="alamat_ayah" class="form-control" style="min-height:48px;"><?= htmlspecialchars($data['alamat_ayah'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Nama Ibu</label>
                        <input type="text" name="ibu" class="form-control" value="<?= htmlspecialchars($data['ibu'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" class="form-control" value="<?= htmlspecialchars($data['pekerjaan_ibu'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telp Ibu</label>
                        <input type="number" name="no_telp_ibu" class="form-control" value="<?= htmlspecialchars($data['no_telp_ibu'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Ibu</label>
                        <textarea name="alamat_ibu" class="form-control" style="min-height:48px;"><?= htmlspecialchars($data['alamat_ibu'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Nama Wali</label>
                        <input type="text" name="wali" class="form-control" value="<?= htmlspecialchars($data['wali'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan Wali</label>
                        <input type="text" name="pekerjaan_wali" class="form-control" value="<?= htmlspecialchars($data['pekerjaan_wali'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telp Wali</label>
                        <input type="number" name="no_telp_wali" class="form-control" value="<?= htmlspecialchars($data['no_telp_wali'] ?? '') ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Wali</label>
                        <textarea name="alamat_wali" class="form-control" style="min-height:48px;"><?= htmlspecialchars($data['alamat_wali'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="index.php" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> Update Siswa</button>
            </div>
        </div>
    </form>
</div>

    <?php
}
include ROOTPATH . '/layouts/footer.php';
?>