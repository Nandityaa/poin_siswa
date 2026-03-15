<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$result = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'Y'");
$result_nonaktif = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'N'");
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
.section-title {
    padding: 24px 28px; font-size: 16px; font-weight: 700;
    color: #1a1a1a; background: white;
    border-bottom: 2px solid #f1f5f9; margin: 0; display:flex; align-items:center; gap:12px;
}
.section-title i { color: #1a1a1a; font-size: 18px; }

.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; min-width:800px; }
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

.badge-jabatan {
    background: #f1f5f9; color: #1a1a1a;
    padding: 6px 14px; border-radius: 8px;
    font-size: 13px; font-weight: 600;
    display: inline-block;
}
.nonaktif-badge { background: #f8f9fa; color: #94a3b8; border: 1px solid #e2e8f0; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; display: inline-block; }
.n-pengguna { font-weight: 600; color: #0f172a; font-size: 15px; }

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
        <h2>Data Guru</h2>
        <a href="create.php" class="btn-add">+ Tambah Guru</a>
    </div>

    <div class="section-card">
        <p class="section-title"><i class="fa-solid fa-users"></i> Guru Aktif</p>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Jabatan</th>
                        <th>Telepon</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($result)): $found = true; ?>
                    <tr>
                        <td style="color:#64748b;"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kode_guru']) ?></td>
                        <td class="n-pengguna"><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                        <td style="color:#64748b;"><?= htmlspecialchars($row['username']) ?></td>
                        <td><span class="badge-jabatan"><?= htmlspecialchars($row['jabatan']) ?></span></td>
                        <td><?= htmlspecialchars($row['telp'] ?: '-') ?></td>
                        <td>
                            <div class="action-cell">
                                <a href="edit.php?id=<?= $row['kode_guru'] ?>" class="btn-action btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="/poin_siswa/modules/guru/process.php" method="post" onsubmit="return confirm('Hapus data <?= $row['nama_pengguna'] ?>?')" style="margin:0;">
                                    <input type="hidden" name="kode_guru" value="<?= $row['kode_guru'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn-action btn-delete" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; if (!$found): ?>
                    <tr class="empty-row"><td colspan="7">Belum ada data guru aktif.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section-card">
        <p class="section-title" style="border-bottom-color:#fef2f2;"><i class="fa-solid fa-users-slash" style="color:#ef4444;"></i> Guru Non-Aktif</p>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Jabatan</th>
                        <th>Telepon</th>
                        <th width="12%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; $found2 = false; while ($row = mysqli_fetch_assoc($result_nonaktif)): $found2 = true; ?>
                    <tr>
                        <td style="color:#64748b;"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kode_guru']) ?></td>
                        <td class="n-pengguna" style="opacity:0.7;"><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                        <td style="color:#64748b;"><?= htmlspecialchars($row['username']) ?></td>
                        <td><span class="nonaktif-badge"><?= htmlspecialchars($row['jabatan']) ?></span></td>
                        <td><?= htmlspecialchars($row['telp'] ?: '-') ?></td>
                        <td>
                            <div class="action-cell">
                                <a href="edit.php?id=<?= $row['kode_guru'] ?>" class="btn-action btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <form action="/poin_siswa/modules/guru/process.php" method="post" onsubmit="return confirm('Hapus data <?= $row['nama_pengguna'] ?>?')" style="margin:0;">
                                    <input type="hidden" name="kode_guru" value="<?= $row['kode_guru'] ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn-action btn-delete" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; if (!$found2): ?>
                <tr class="empty-row"><td colspan="7">Tidak ada guru non-aktif.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>