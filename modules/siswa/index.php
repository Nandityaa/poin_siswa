<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$query = "SELECT
            siswa.nis AS nis,
            siswa.nama_siswa AS nama_siswa,
            siswa.jenis_kelamin AS jenis_kelamin,
            siswa.alamat AS alamat,
            ortu_wali.ayah AS ayah,
            ortu_wali.ibu AS ibu,
            ortu_wali.wali AS wali,
            tingkat.tingkat AS tingkat,
            program_keahlian.program_keahlian AS program_keahlian,
            kelas.rombel AS rombel,
            guru.nama_pengguna AS nama_pengguna
          FROM siswa
          LEFT JOIN ortu_wali ON siswa.id_ortu_wali = ortu_wali.id_ortu_wali
          LEFT JOIN kelas ON siswa.id_kelas = kelas.id_kelas
          LEFT JOIN tingkat ON kelas.id_tingkat = tingkat.id_tingkat
          LEFT JOIN program_keahlian ON kelas.id_program_keahlian = program_keahlian.id_program_keahlian
          LEFT JOIN guru ON kelas.kode_guru = guru.kode_guru
          ORDER BY tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel, siswa.nama_siswa";
$result = mysqli_query($conn, $query);
if (!$result) die("Query Error: " . mysqli_error($conn));
?>

<style>
.page-wrapper { max-width: 1100px; margin: 36px auto; padding: 0 20px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.page-header h2 { margin: 0; font-size: 26px; color: #1a2a3a; font-weight: 700; }
.btn-add {
    display: inline-flex; align-items: center; gap: 8px;
    background: #007bff; color: white; text-decoration: none;
    padding: 10px 20px; border-radius: 8px; font-weight: 600;
    font-size: 14px; transition: background 0.2s, transform 0.2s; border: none; cursor: pointer;
}
.btn-add:hover { background: #0056b3; transform: translateY(-1px); }
.section-card { background: white; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 30px; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    background: #f1f3f5; color: #343a40;
    padding: 13px 18px; font-size: 13px; font-weight: 600;
    text-align: left; text-transform: uppercase; letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}
.data-table td { padding: 13px 18px; border-bottom: 1px solid #f0f2f5; color: #2c3e50; font-size: 14px; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #f8f9fa; }
.badge-kelas { background: #e3f0ff; color: #0056b3; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
.badge-lk { background: #d1ecf1; color: #0c5460; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.badge-pr { background: #fce4ec; color: #880e4f; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.btn-edit, .btn-delete { padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; transition: all 0.2s; }
.btn-edit { background: #e8f4fd; color: #0869c4; }
.btn-edit:hover { background: #007bff; color: white; }
.btn-delete { background: #fde8e8; color: #c40808; }
.btn-delete:hover { background: #dc3545; color: white; }
.action-cell { display: flex; gap: 8px; align-items: center; }
.empty-row td { text-align: center; padding: 40px; color: #adb5bd; font-style: italic; }
.student-name { font-weight: 600; color: #1a2a3a; }
.student-nis { font-size: 12px; color: #6c757d; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <h2>Data Siswa</h2>
        <a href="create.php" class="btn-add">+ Tambah Siswa</a>
    </div>

    <div class="section-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Orang Tua / Wali</th>
                    <th width="13%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($result)): $found = true;
                    $kelas = trim($row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel']);
                    $ortu = [];
                    if (!empty($row['ayah'])) $ortu[] = $row['ayah'];
                    elseif (!empty($row['ibu'])) $ortu[] = $row['ibu'];
                    elseif (!empty($row['wali'])) $ortu[] = $row['wali'];
                    $ortu_display = implode(' / ', $ortu) ?: '-';
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <div class="student-name"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                        <div class="student-nis">NIS: <?= htmlspecialchars($row['nis']) ?></div>
                    </td>
                    <td>
                        <?php if ($row['jenis_kelamin'] == 'Laki - Laki'): ?>
                            <span class="badge-lk">Laki-Laki</span>
                        <?php else: ?>
                            <span class="badge-pr">Perempuan</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge-kelas"><?= htmlspecialchars($kelas) ?: '-' ?></span></td>
                    <td><?= htmlspecialchars($row['nama_pengguna'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($ortu_display) ?></td>
                    <td>
                        <div class="action-cell">
                            <a href="edit.php?id=<?= $row['nis'] ?>" class="btn-edit">Edit</a>
                            <form action="process.php" method="post" onsubmit="return confirm('Hapus data <?= htmlspecialchars($row['nama_siswa']) ?>?')">
                                <input type="hidden" name="nis" value="<?= $row['nis'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; if (!$found): ?>
                <tr class="empty-row"><td colspan="7">Belum ada data siswa.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>