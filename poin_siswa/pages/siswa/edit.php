<?php
include('../../config/config.php');
include('../../includes/header.php');

// Get student ID from URL
// User's list.php uses ?id=NIS.
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Fetch student data
// User's list.php uses logic joining multiple tables.
// For edit, we ideally want the same data.
// Simple query for now:
$query = "SELECT * FROM Siswa WHERE NIS = '$id' LIMIT 1";
// Note: If data is split across tables (Ortu_Wali), this simple query won't get Parents data if they are in Ortu_Wali table linked by Id_Ortu_Wali.
// But process.php UPDATEs 'Siswa' table with 'Ayah', 'Ibu' columns directly.
// This suggests 'Siswa' table DOES have these columns in the user's schema version for process.php?
// I will assume Siswa table has them as per process.php update query.
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "<div class='alert alert-danger text-center mt-4'>Data siswa tidak ditemukan!</div>";
    // Optional: include footer and exit
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
                    <!-- process.php uses WHERE NIS=$NIS from POST -->
                    <!-- Ideally we should not change NIS if it's the key, or handle it carefully. Form will post NIS. -->

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NIS <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="NIS"
                                value="<?php echo htmlspecialchars($data['NIS'] ?? ''); ?>" readonly>
                            <!-- Readonly typical for ID editing to prevent breaking PK references -->
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Siswa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="Nama_Siswa"
                                value="<?php echo htmlspecialchars($data['Nama_Siswa'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" name="Jenis_Kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" <?php echo (($data['Jenis_Kelamin'] ?? '') == 'Laki-Laki') ? 'selected' : ''; ?>>Laki-Laki</option>
                                <option value="Perempuan" <?php echo (($data['Jenis_Kelamin'] ?? '') == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kelas</label>
                            <input type="text" class="form-control" name="Kelas"
                                value="<?php echo htmlspecialchars($data['Kelas'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Siswa</label>
                        <textarea class="form-control" name="Alamat"
                            rows="2"><?php echo htmlspecialchars($data['Alamat'] ?? ''); ?></textarea>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Data Orang Tua / Wali</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" class="form-control" name="Ayah"
                                value="<?php echo htmlspecialchars($data['Ayah'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <input type="text" class="form-control" name="Ibu"
                                value="<?php echo htmlspecialchars($data['Ibu'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nama Wali</label>
                            <input type="text" class="form-control" name="Wali"
                                value="<?php echo htmlspecialchars($data['Wali'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pekerjaan Ayah</label>
                            <input type="text" class="form-control" name="Pekerjaan_Ayah"
                                value="<?php echo htmlspecialchars($data['Pekerjaan_Ayah'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pekerjaan Ibu</label>
                            <input type="text" class="form-control" name="Pekerjaan_Ibu"
                                value="<?php echo htmlspecialchars($data['Pekerjaan_Ibu'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pekerjaan Wali</label>
                            <input type="text" class="form-control" name="Pekerjaan_Wali"
                                value="<?php echo htmlspecialchars($data['Pekerjaan_Wali'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Alamat Ayah</label>
                            <textarea class="form-control" name="Alamat_Ayah"
                                rows="2"><?php echo htmlspecialchars($data['Alamat_Ayah'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Alamat Ibu</label>
                            <textarea class="form-control" name="Alamat_Ibu"
                                rows="2"><?php echo htmlspecialchars($data['Alamat_Ibu'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Alamat Wali</label>
                            <textarea class="form-control" name="Alamat_Wali"
                                rows="2"><?php echo htmlspecialchars($data['Alamat_Wali'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Wali Kelas</label>
                        <input type="text" class="form-control" name="Wali_Kelas"
                            value="<?php echo htmlspecialchars($data['Wali_Kelas'] ?? ''); ?>">
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
} // End else
include('../../includes/footer.php');
?>