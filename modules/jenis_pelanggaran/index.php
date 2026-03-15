<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$result = mysqli_query($conn, "SELECT * FROM jenis_pelanggaran ORDER BY poin DESC");
?>

<style>
.page-wrapper { font-family: 'Inter', sans-serif; max-width: 1200px; margin: 36px auto; padding: 0 20px; animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.page-header h2 { margin: 0; font-size: 26px; color: #0f172a; font-weight: 800; display:flex; align-items:center; gap:12px; letter-spacing:-0.5px; }
.btn-add {
    display: inline-flex; align-items: center; gap: 8px;
    background: #1a1a1a; color: white; text-decoration: none;
    padding: 12px 24px; border-radius: 12px; font-weight: 600;
    font-size: 14px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
.btn-add:hover { background: #000; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); }

.section-card {
    background: white; border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.04);
    overflow: hidden; margin-bottom: 40px;
}

.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; min-width:600px; }
.data-table th {
    background: #1a1a1a; color: #fff;
    padding: 16px 28px; font-size: 11px; font-weight: 600;
    text-align: left; text-transform: uppercase; letter-spacing: 1.5px;
    border-bottom: none;
}
.data-table td {
    padding: 16px 28px; border-bottom: 1px solid #f1f5f9;
    color: #334155; font-size: 14px; vertical-align: middle;
}
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #f8fafc; }

.poin-badge { padding: 6px 16px; border-radius: 8px; font-weight: 700; font-size: 13px; display:inline-block; border: 1px solid #e2e8f0; }
.poin-low { background: #f8f9fa; color: #64748b; }
.poin-mid { background: #f1f5f9; color: #1a1a1a; }
.poin-high { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
.jp-name { font-weight: 600; color: #0f172a; font-size:15px; }

.action-cell { display: flex; gap: 8px; align-items: center; }
.btn-action {
    width: 36px; height: 36px; border-radius: 10px; display:flex; justify-content:center; align-items:center;
    transition: all 0.2s; text-decoration: none; border:none; cursor:pointer; font-size: 15px;
}
.btn-edit { background: #f8f9fa; color: #1a1a1a; border: 1px solid #e2e8f0; }
.btn-edit:hover { background: #1a1a1a; color: white; border-color: #1a1a1a; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
.btn-delete { background: #f8f9fa; color: #1a1a1a; border: 1px solid #e2e8f0; }
.btn-delete:hover { background: #000; color: white; border-color: #000; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

.empty-row td { text-align: center; padding: 60px; color: #94a3b8; font-style: italic; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <h2>Jenis Pelanggaran</h2>
        <a href="create.php" class="btn-add">+ Tambah Jenis</a>
    </div>

    <div class="section-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Jenis Pelanggaran</th>
                        <th width="15%" style="text-align:center;">Poin</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($result)): $found = true;
                        $poin = (int)$row['poin'];
                        $poin_class = ($poin >= 50) ? 'poin-high' : (($poin >= 20) ? 'poin-mid' : 'poin-low');
                    ?>
                    <tr>
                        <td style="color:#64748b;"><?= $no++ ?></td>
                        <td class="jp-name"><?= htmlspecialchars($row['jenis']) ?></td>
                        <td style="text-align:center;"><span class="poin-badge <?= $poin_class ?>">+ <?= $poin ?></span></td>
                        <td>
                            <div class="action-cell">
                                <a href="edit.php?id=<?= $row['id_jenis_pelanggaran'] ?>" class="btn-action btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="/poin_siswa/modules/jenis_pelanggaran/process.php" method="post" onsubmit="return confirm('Hapus <?= htmlspecialchars($row['jenis']) ?>?')" style="margin:0;">
                                    <input type="hidden" name="id" value="<?= $row['id_jenis_pelanggaran'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn-action btn-delete" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
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
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>