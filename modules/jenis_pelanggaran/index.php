<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$result = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY poin DESC");
?>

<style>
.page-wrapper { max-width: 800px; margin: 36px auto; padding: 0 20px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.page-header h2 { margin: 0; font-size: 26px; color: #1a2a3a; font-weight: 700; }
.btn-add { display: inline-flex; align-items: center; gap: 8px; background: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: background 0.2s, transform 0.2s; border: none; cursor: pointer; }
.btn-add:hover { background: #0056b3; transform: translateY(-1px); }
.section-card { background: white; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { background: #f1f3f5; color: #343a40; padding: 13px 18px; font-size: 13px; font-weight: 600; text-align: left; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #dee2e6; }
.data-table td { padding: 13px 18px; border-bottom: 1px solid #f0f2f5; color: #2c3e50; font-size: 14px; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #f8f9fa; }
.poin-badge { padding: 5px 12px; border-radius: 20px; font-weight: 700; font-size: 13px; }
.poin-low { background: #d4edda; color: #155724; }
.poin-mid { background: #fff3cd; color: #856404; }
.poin-high { background: #f8d7da; color: #721c24; }
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
        <h2>Jenis Pelanggaran</h2>
        <a href="create.php" class="btn-add">+ Tambah Jenis</a>
    </div>

    <div class="section-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Jenis Pelanggaran</th>
                    <th width="15%">Poin</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($result)): $found = true;
                    $poin = (int)$row['poin'];
                    $poin_class = ($poin >= 50) ? 'poin-high' : (($poin >= 20) ? 'poin-mid' : 'poin-low');
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['jenis']) ?></td>
                    <td><span class="poin-badge <?= $poin_class ?>"><?= $poin ?> poin</span></td>
                    <td>
                        <div class="action-cell">
                            <a href="edit.php?id=<?= $row['id_jenis_pelanggaran'] ?>" class="btn-edit">Edit</a>
                            <form action="/poin_siswa/modules/jenis_pelanggaran/process.php" method="post" onsubmit="return confirm('Hapus <?= htmlspecialchars($row['jenis']) ?>?')">
                                <input type="hidden" name="id" value="<?= $row['id_jenis_pelanggaran'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; if (!$found): ?>
                <tr class="empty-row"><td colspan="4">Belum ada data jenis pelanggaran.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>