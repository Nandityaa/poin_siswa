<?php
include('../../config/config.php');
include('../../includes/header.php');

// Fetch Classes for Dropdown
$query_kelas = "SELECT kelas.id_kelas, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel 
                FROM kelas 
                JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat 
                JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian 
                ORDER BY tingkat.tingkat ASC, program_keahlian.program_keahlian ASC, kelas.rombel ASC";
$result_kelas = mysqli_query($conn, $query_kelas);
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
                        <input type="number" class="form-control" name="nis" required placeholder="Masukkan NIS">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Siswa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_siswa" required
                            placeholder="Masukkan Nama Lengkap">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select" name="jenis_kelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki - Laki">Laki - Laki</option> <!-- Matches ENUM values exactly -->
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select" name="id_kelas" required>
                            <option value="">Pilih Kelas</option>
                            <?php
                            if ($result_kelas && mysqli_num_rows($result_kelas) > 0) {
                                while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
                                    $nama_kelas = $row_kelas['tingkat'] . " " . $row_kelas['program_keahlian'] . " " . $row_kelas['rombel'];
                                    echo '<option value="' . $row_kelas['id_kelas'] . '">' . $nama_kelas . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Siswa</label>
                    <textarea class="form-control" name="alamat" rows="2" placeholder="Alamat lengkap siswa"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Password Default</label>
                    <input type="text" class="form-control" name="password" value="Siswa12345*!" readonly>
                    <small class="text-muted">Password default siswa.</small>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Data Orang Tua / Wali</h5>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Ayah</label>
                        <input type="text" class="form-control" name="ayah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Ibu</label>
                        <input type="text" class="form-control" name="ibu">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Nama Wali</label>
                        <input type="text" class="form-control" name="wali">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pekerjaan Ayah</label>
                        <input type="text" class="form-control" name="pekerjaan_ayah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pekerjaan Ibu</label>
                        <input type="text" class="form-control" name="pekerjaan_ibu">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pekerjaan Wali</label>
                        <input type="text" class="form-control" name="pekerjaan_wali">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alamat Ayah</label>
                        <textarea class="form-control" name="alamat_ayah" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alamat Ibu</label>
                        <textarea class="form-control" name="alamat_ibu" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alamat Wali</label>
                        <textarea class="form-control" name="alamat_wali" rows="2"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">No Telp Ayah</label>
                        <input type="text" class="form-control" name="no_telp_ayah">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">No Telp Ibu</label>
                        <input type="text" class="form-control" name="no_telp_ibu">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">No Telp Wali</label>
                        <input type="text" class="form-control" name="no_telp_wali">
                    </div>
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