<?php
include('../../config/config.php');
include('../../includes/header.php');

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

    <div class="text-center mb-4">
        <h2>Edit Data Siswa</h2>
    </div>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="../../process/siswa_process.php">
                    <input type="hidden" name="action" value="edit">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIS <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nis"
                                value="<?php echo htmlspecialchars($data['nis']); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Siswa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_siswa"
                                value="<?php echo htmlspecialchars($data['nama_siswa']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki - Laki" <?php echo ($data['jenis_kelamin'] == 'Laki - Laki') ? 'selected' : ''; ?>>Laki - Laki</option>
                                <option value="Perempuan" <?php echo ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kelas <span class="text-danger">*</span></label>
                            <select class="form-select" name="id_kelas" required>
                                <option value="">Pilih Kelas</option>
                                <?php
                                if ($result_kelas && mysqli_num_rows($result_kelas) > 0) {
                                    mysqli_data_seek($result_kelas, 0); // Reset pointer
                                    while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
                                        $nama_kelas = $row_kelas['tingkat'] . " " . $row_kelas['program_keahlian'] . " " . $row_kelas['rombel'];
                                        $selected = ($data['id_kelas'] == $row_kelas['id_kelas']) ? 'selected' : '';
                                        echo '<option value="' . $row_kelas['id_kelas'] . '" ' . $selected . '>' . $nama_kelas . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Siswa</label>
                        <textarea class="form-control" name="alamat"
                            rows="2"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Data Orang Tua / Wali</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" class="form-control" name="ayah"
                                value="<?php echo htmlspecialchars($data['ayah'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <input type="text" class="form-control" name="ibu"
                                value="<?php echo htmlspecialchars($data['ibu'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Wali</label>
                            <input type="text" class="form-control" name="wali"
                                value="<?php echo htmlspecialchars($data['wali'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pekerjaan Ayah</label>
                            <input type="text" class="form-control" name="pekerjaan_ayah"
                                value="<?php echo htmlspecialchars($data['pekerjaan_ayah'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pekerjaan Ibu</label>
                            <input type="text" class="form-control" name="pekerjaan_ibu"
                                value="<?php echo htmlspecialchars($data['pekerjaan_ibu'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pekerjaan Wali</label>
                            <input type="text" class="form-control" name="pekerjaan_wali"
                                value="<?php echo htmlspecialchars($data['pekerjaan_wali'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Alamat Ayah</label>
                            <textarea class="form-control" name="alamat_ayah"
                                rows="2"><?php echo htmlspecialchars($data['alamat_ayah'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Alamat Ibu</label>
                            <textarea class="form-control" name="alamat_ibu"
                                rows="2"><?php echo htmlspecialchars($data['alamat_ibu'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Alamat Wali</label>
                            <textarea class="form-control" name="alamat_wali"
                                rows="2"><?php echo htmlspecialchars($data['alamat_wali'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">No Telp Ayah</label>
                            <input type="text" class="form-control" name="no_telp_ayah"
                                value="<?php echo htmlspecialchars($data['no_telp_ayah'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">No Telp Ibu</label>
                            <input type="text" class="form-control" name="no_telp_ibu"
                                value="<?php echo htmlspecialchars($data['no_telp_ibu'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">No Telp Wali</label>
                            <input type="text" class="form-control" name="no_telp_wali"
                                value="<?php echo htmlspecialchars($data['no_telp_wali'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update Data</button>
                        <a href="list.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
}
include('../../includes/footer.php');
?>