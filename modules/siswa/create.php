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

<!-- Membuat tampilan form untuk menambah data siswa -->
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
textarea.form-control { resize: vertical; min-height: 100px; }

.radio-group { display: flex; gap: 20px; align-items: center; padding: 12px 0; }
.radio-label { display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; }

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
    <form action="/poin_siswa/modules/siswa/process.php" method="POST">
        <input type="hidden" name="action" value="add" />
        
        <div class="form-card">
            <div class="form-header">
                <div class="form-header-title">
                    <i class="fa-solid fa-user-graduate"></i>
                    <h2>Tambah Data Siswa</h2>
                </div>
            </div>
            
            <div class="form-body">
                <div class="form-section-title">Informasi Pribadi</div>
                
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Nomor Induk Siswa (NIS) <span style="color:red;">*</span></label>
                        <input type="number" name="nis" class="form-control" autocomplete="off" required placeholder="Contoh: 12345"/>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap Siswa <span style="color:red;">*</span></label>
                        <input type="text" name="nama_siswa" class="form-control" autocomplete="off" required placeholder="Masukkan nama siswa"/>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span style="color:red;">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Laki - Laki" required /> Laki - Laki
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="jenis_kelamin" value="Perempuan" required /> Perempuan
                            </label>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="form-label">Kelas <span style="color:red;">*</span></label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php
                                $query_kelas = mysqli_query($conn, "SELECT k.id_kelas, t.tingkat, pk.program_keahlian, k.rombel FROM kelas k JOIN program_keahlian pk USING(id_program_keahlian) JOIN tingkat t USING(id_tingkat) ORDER BY t.tingkat ASC, pk.program_keahlian ASC, k.rombel ASC");
                                while ($kelas = mysqli_fetch_assoc($query_kelas)) { 
                                    echo "<option value='" . $kelas['id_kelas'] . "'>" . htmlspecialchars($kelas['tingkat'] . ' ' . $kelas['program_keahlian'] . ' ' . $kelas['rombel']) . "</option>"; 
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span style="color:red;">*</span></label>
                    <textarea name="alamat_siswa" class="form-control" autocomplete="off" required placeholder="Masukkan alamat lengkap tempat tinggal siswa"></textarea>
                </div>

                <div class="form-section-title">Data Orang Tua / Wali (Opsional)</div>
                
                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Nama Ayah</label>
                        <input type="text" name="ayah" class="form-control" autocomplete="off" placeholder="Nama Ayah"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" class="form-control" autocomplete="off" placeholder="Pekerjaan"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telp Ayah</label>
                        <input type="number" name="telp_ayah" class="form-control" autocomplete="off" placeholder="08..."/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Ayah</label>
                        <textarea name="alamat_ayah" class="form-control" style="min-height:48px;" placeholder="Alamat"></textarea>
                    </div>
                </div>

                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Nama Ibu</label>
                        <input type="text" name="ibu" class="form-control" autocomplete="off" placeholder="Nama Ibu"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" class="form-control" autocomplete="off" placeholder="Pekerjaan"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telp Ibu</label>
                        <input type="number" name="telp_ibu" class="form-control" autocomplete="off" placeholder="08..."/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Ibu</label>
                        <textarea name="alamat_ibu" class="form-control" style="min-height:48px;" placeholder="Alamat"></textarea>
                    </div>
                </div>

                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Nama Wali</label>
                        <input type="text" name="wali" class="form-control" autocomplete="off" placeholder="Nama Wali"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pekerjaan Wali</label>
                        <input type="text" name="pekerjaan_wali" class="form-control" autocomplete="off" placeholder="Pekerjaan"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telp Wali</label>
                        <input type="number" name="telp_wali" class="form-control" autocomplete="off" placeholder="08..."/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Wali</label>
                        <textarea name="alamat_wali" class="form-control" style="min-height:48px;" placeholder="Alamat"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="/poin_siswa/modules/siswa/index.php" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-save"><i class="fa-solid fa-floppy-disk"></i> Simpan Siswa</button>
            </div>
        </div>
    </form>
</div>

<?php
// Menyertakan file footer (biasanya berisi bagian bawah halaman)
include ROOTPATH . "/layouts/footer.php";
?>

<!-- 
    🧠 Penjelasan Singkat:
	•	File ini digunakan untuk menampilkan form tambah siswa.
	•	Setelah pengguna mengisi data siswa, data akan dikirim ke /poin_siswa/modules/siswa/process.php menggunakan metode POST.
	•	File header dan footer dipakai agar tampilan halaman tetap konsisten di seluruh situs. 
-->