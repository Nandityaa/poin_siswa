<?php
// Menentukan path utama proyek
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Include header dan config database
include ROOTPATH . '/includes/header.php';
include ROOTPATH . '/config/config.php';

// Fetch classes for the datalist
$query_kelas = "SELECT 
                    k.id_kelas, 
                    CONCAT(t.tingkat, ' ', pk.program_keahlian, ' ', k.rombel) AS nama_kelas 
                FROM kelas k 
                JOIN tingkat t ON k.id_tingkat = t.id_tingkat 
                JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian 
                ORDER BY t.id_tingkat, pk.program_keahlian, k.rombel";
$result_kelas = mysqli_query($conn, $query_kelas);
?>

<style>
    .container {
        width: 90%;
        margin: 20px auto;
        font-family: sans-serif;
    }

    h2 {
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        margin-bottom: 20px;
        color: #333;
    }

    .form-section {
        margin-bottom: 30px;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .section-title {
        font-weight: bold;
        font-size: 1.1em;
        margin-bottom: 15px;
        color: #555;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }

    /* Layout Flex for Data Siswa */
    .data-siswa-container {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .photo-box {
        width: 150px;
        height: 180px;
        border: 2px solid #ccc;
        background-color: #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #777;
        font-weight: bold;
        flex-shrink: 0;
    }

    .inputs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 20px;
        row-gap: 15px;
        flex-grow: 1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 5px;
        font-weight: bold;
        font-size: 0.9em;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-group .full-width {
        grid-column: span 2;
    }

    /* Table layout for Data Ortu */
    .ortu-table {
        width: 100%;
        border-collapse: collapse;
    }

    .ortu-table th,
    .ortu-table td {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .ortu-table th {
        background-color: #eaeaea;
        font-size: 0.9em;
    }

    .ortu-table input {
        width: 95%;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .btn-submit {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        cursor: pointer;
        display: block;
        margin-top: 20px;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    /* Visual alignment specific to drawing */
    .row-label {
        font-weight: bold;
        background-color: #f2f2f2;
        width: 80px;
    }
</style>

<div class="container">
    <h2>Tambah Data Siswa</h2>

    <form action="../../process/siswa_process.php" method="POST">
        <input type="hidden" name="action" value="add">

        <!-- DATA SISWA SECTION -->
        <div class="form-section">
            <div class="section-title">Data Siswa</div>

            <div class="data-siswa-container">
                <!-- PHOTO (Left) -->
                <div class="photo-box">
                    FOTO
                </div>

                <!-- INPUTS (Right: Name/Alamat & Gender/Class) -->
                <div class="inputs-grid">
                    <!-- Row 1 Left -->
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="number" id="nis" name="nis" required placeholder="Contoh: 8312">
                    </div>

                    <!-- Row 1 Right -->
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih JK</option>
                            <option value="Laki - Laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <!-- Row 2 Left -->
                    <div class="form-group">
                        <label for="nama_siswa">Nama Siswa</label>
                        <input type="text" id="nama_siswa" name="nama_siswa" required placeholder="Nama Lengkap">
                    </div>

                    <!-- Row 2 Right (Datalist Implementation) -->
                    <div class="form-group">
                        <label for="kelas_display">Kelas</label>
                        <input list="kelas_list" id="kelas_display" name="kelas_display"
                            placeholder="Ketik atau pilih kelas..." autocomplete="off" oninput="updateKelasId()">
                        <datalist id="kelas_list">
                            <?php while ($row = mysqli_fetch_assoc($result_kelas)): ?>
                                <option value="<?php echo htmlspecialchars($row['nama_kelas']); ?>"
                                    data-id="<?php echo $row['id_kelas']; ?>">
                                <?php endwhile; ?>
                        </datalist>
                        <input type="hidden" name="id_kelas" id="id_kelas" required>
                        <small style="font-size: 0.8em; color: gray;">*Wajib dipilih dari daftar</small>
                    </div>

                    <!-- Row 3 Full Width -->
                    <div class="form-group" style="grid-column: span 2;">
                        <label for="alamat">Alamat Siswa</label>
                        <textarea id="alamat" name="alamat" rows="2" placeholder="Alamat Lengkap"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA ORTU/WALI SECTION -->
        <div class="form-section">
            <div class="section-title">Data Ortu/Wali</div>

            <table class="ortu-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Nama</th>
                        <th>Pekerjaan</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="row-label">Ayah</td>
                        <td><input type="text" name="nama_ayah" placeholder="Nama Ayah"></td>
                        <td><input type="text" name="pekerjaan_ayah" placeholder="Pekerjaan Ayah"></td>
                        <td><input type="text" name="alamat_ayah" placeholder="Alamat Ayah"></td>
                    </tr>
                    <tr>
                        <td class="row-label">Ibu</td>
                        <td><input type="text" name="nama_ibu" placeholder="Nama Ibu"></td>
                        <td><input type="text" name="pekerjaan_ibu" placeholder="Pekerjaan Ibu"></td>
                        <td><input type="text" name="alamat_ibu" placeholder="Alamat Ibu"></td>
                    </tr>
                    <tr>
                        <td class="row-label">Wali</td>
                        <td><input type="text" name="nama_wali" placeholder="Nama Wali"></td>
                        <td><input type="text" name="pekerjaan_wali" placeholder="Pekerjaan Wali"></td>
                        <td><input type="text" name="alamat_wali" placeholder="Alamat Wali"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn-submit">Simpan Data Siswa</button>
    </form>
</div>

<script>
    // Logic to handle datalist ID selection
    function updateKelasId() {
        var input = document.getElementById('kelas_display');
        var list = document.getElementById('kelas_list');
        var hiddenId = document.getElementById('id_kelas');

        var options = list.options;
        hiddenId.value = ""; // Reset ID

        for (var i = 0; i < options.length; i++) {
            if (options[i].value === input.value) {
                // In standard datalist, attributes aren't directly linked to the 'option' element during input event easily
                // But we can find the matching option by value in the datalist text
                hiddenId.value = options[i].getAttribute('data-id');
                break;
            }
        }
    }
</script>

<?php
include ROOTPATH . '/includes/footer.php';
?>