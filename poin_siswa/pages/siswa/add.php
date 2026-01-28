<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa'); // Adjust if needed
// Include config might be needed if we want to show dropdowns, but for now just form?
// User's include is relative usually.
include '../../config/config.php';
include '../../includes/header.php';
?>

<div class="text-center mb-4">
    <h2>Tambah Data Siswa</h2>
</div>

<div class="container">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="../../process/siswa_process.php">
                <input type="hidden" name="action" value="add">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">NIS <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="NIS" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Siswa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="Nama_Siswa" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select" name="Jenis_Kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kelas</label>
                        <input type="text" class="form-control" name="Kelas" placeholder="Contoh: XII RPL 1">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Siswa</label>
                    <textarea class="form-control" name="Alamat" rows="2"></textarea>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Data Orang Tua / Wali</h5>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Ayah</label>
                        <input type="text" class="form-control" name="Ayah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Ibu</label>
                        <input type="text" class="form-control" name="Ibu">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Wali</label>
                        <input type="text" class="form-control" name="Wali">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pekerjaan Ayah</label>
                        <input type="text" class="form-control" name="Pekerjaan_Ayah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pekerjaan Ibu</label>
                        <input type="text" class="form-control" name="Pekerjaan_Ibu">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pekerjaan Wali</label>
                        <input type="text" class="form-control" name="Pekerjaan_Wali">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alamat Ayah</label>
                        <textarea class="form-control" name="Alamat_Ayah" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alamat Ibu</label>
                        <textarea class="form-control" name="Alamat_Ibu" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alamat Wali</label>
                        <textarea class="form-control" name="Alamat_Wali" rows="2"></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Wali Kelas</label>
                    <input type="text" class="form-control" name="Wali_Kelas">
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                    <a href="list.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include('../../includes/footer.php');
?>