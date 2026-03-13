<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$result = mysqli_query($conn, "SELECT kelas.id_kelas, tingkat.tingkat, program_keahlian.program_keahlian, kelas.rombel, guru.nama_pengguna, tingkat.tingkat AS lv
    FROM kelas
    JOIN tingkat USING(id_tingkat)
    JOIN program_keahlian USING(id_program_keahlian)
    JOIN guru USING(kode_guru)
    ORDER BY id_tingkat DESC, id_program_keahlian ASC, rombel ASC");

// Pre-fetch BK guru per level to avoid N+1 queries inside the loop
$bk = [];
foreach (['X' => 'Guru BK X', 'XI' => 'Guru BK XI', 'XII' => 'Guru BK XII'] as $lv => $jabatan) {
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = '$jabatan' AND aktif = 'Y' LIMIT 1"));
    $bk[$lv] = $r['nama_pengguna'] ?? '-';
}
?>

<style>
.page-wrapper { max-width: 900px; margin: 36px auto; padding: 0 20px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.page-header h2 { margin: 0; font-size: 26px; color: #1a2a3a; font-weight: 700; }
.btn-add { display: inline-flex; align-items: center; gap: 8px; background: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: background 0.2s, transform 0.2s; border: none; cursor: pointer; }
.btn-add:hover { background: #0056b3; transform: translateY(-1px); }
.section-card { background: white; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 30px; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { background: #f1f3f5; color: #343a40; padding: 13px 18px; font-size: 13px; font-weight: 600; text-align: left; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #dee2e6; }
.data-table td { padding: 13px 18px; border-bottom: 1px solid #f0f2f5; color: #2c3e50; font-size: 14px; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #f8f9fa; }
.badge-tingkat { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
.badge-x { background: #fff3cd; color: #856404; }
.badge-xi { background: #d1ecf1; color: #0c5460; }
.badge-xii { background: #d4edda; color: #155724; }
.kelas-name { font-weight: 600; color: #1a2a3a; }
.btn-edit, .btn-delete { padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; transition: all 0.2s; }
.btn-edit { background: #e8f4fd; color: #0869c4; }
.btn-edit:hover { background: #007bff; color: white; }
.btn-delete { background: #fde8e8; color: #c40808; }
.btn-delete:hover { background: #dc3545; color: white; }
.action-cell { display: flex; gap: 8px; align-items: center; }
.empty-row td { text-align: center; padding: 40px; color: #adb5bd; font-style: italic; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <h2>Data Kelas</h2>
        <a href="create.php" class="btn-add">+ Tambah Kelas</a>
    </div>

    <div class="section-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Tingkat</th>
                    <th>Nama Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Guru BK</th>
                    <th width="13%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($result)): $found = true;
                    $nama_kelas = $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'];
                    $lv = $row['lv'];
                    $badge_class = ($lv == 'XII') ? 'badge-xii' : (($lv == 'XI') ? 'badge-xi' : 'badge-x');
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><span class="badge-tingkat <?= $badge_class ?>"><?= htmlspecialchars($lv) ?></span></td>
                    <td class="kelas-name"><?= htmlspecialchars($nama_kelas) ?></td>
                    <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                    <td><?= htmlspecialchars($bk[$lv] ?? '-') ?></td>
                    <td>
                        <div class="action-cell">
                            <a href="edit.php?id=<?= $row['id_kelas'] ?>" class="btn-edit">Edit</a>
                            <form action="/poin_siswa/modules/kelas/process.php" method="post" onsubmit="return confirm('Hapus kelas <?= htmlspecialchars($nama_kelas) ?>?')">
                                <input type="hidden" name="id" value="<?= $row['id_kelas'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; if (!$found): ?>
                <tr class="empty-row"><td colspan="6">Belum ada data kelas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
