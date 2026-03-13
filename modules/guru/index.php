<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$result = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'Y'");
$result_nonaktif = mysqli_query($conn, "SELECT * FROM guru WHERE aktif = 'N'");
?>

<style>
.page-wrapper { max-width: 1100px; margin: 36px auto; padding: 0 20px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.page-header h2 { margin: 0; font-size: 26px; color: #1a2a3a; font-weight: 700; }
.btn-add {
    display: inline-flex; align-items: center; gap: 8px;
    background: #007bff; color: white; text-decoration: none;
    padding: 10px 20px; border-radius: 8px; font-weight: 600;
    font-size: 14px; transition: background 0.2s, transform 0.2s;
    border: none; cursor: pointer;
}
.btn-add:hover { background: #0056b3; transform: translateY(-1px); }
.section-card {
    background: white; border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    overflow: hidden; margin-bottom: 30px;
}
.section-title {
    padding: 16px 24px; font-size: 16px; font-weight: 600;
    color: #495057; background: #f8f9fa;
    border-bottom: 1px solid #e9ecef; margin: 0;
}
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    background: #f1f3f5; color: #343a40;
    padding: 13px 18px; font-size: 13px; font-weight: 600;
    text-align: left; text-transform: uppercase; letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}
.data-table td {
    padding: 13px 18px; border-bottom: 1px solid #f0f2f5;
    color: #2c3e50; font-size: 14px; vertical-align: middle;
}
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #f8f9fa; }
.badge-jabatan {
    background: #e3f0ff; color: #0056b3;
    padding: 4px 10px; border-radius: 20px;
    font-size: 12px; font-weight: 600;
}
.btn-edit, .btn-delete {
    padding: 6px 14px; border-radius: 6px; font-size: 13px;
    font-weight: 600; cursor: pointer; border: none; text-decoration: none;
    display: inline-block; transition: all 0.2s;
}
.btn-edit { background: #e8f4fd; color: #0869c4; }
.btn-edit:hover { background: #007bff; color: white; }
.btn-delete { background: #fde8e8; color: #c40808; }
.btn-delete:hover { background: #dc3545; color: white; }
.action-cell { display: flex; gap: 8px; align-items: center; }
.empty-row td { text-align: center; padding: 40px; color: #adb5bd; font-style: italic; }
.nonaktif-badge { background: #f8d7da; color: #721c24; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <h2>Data Guru</h2>
        <a href="create.php" class="btn-add">+ Tambah Guru</a>
    </div>

    <div class="section-card">
        <p class="section-title">Guru Aktif</p>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($result)): $found = true; ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kode_guru']) ?></td>
                    <td><b><?= htmlspecialchars($row['nama_pengguna']) ?></b></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><span class="badge-jabatan"><?= htmlspecialchars($row['jabatan']) ?></span></td>
                    <td><?= htmlspecialchars($row['telp']) ?></td>
                    <td>
                        <div class="action-cell">
                            <a href="edit.php?id=<?= $row['kode_guru'] ?>" class="btn-edit">Edit</a>
                            <form action="/poin_siswa/modules/guru/process.php" method="post" onsubmit="return confirm('Hapus data <?= $row['nama_pengguna'] ?>?')">
                                <input type="hidden" name="kode_guru" value="<?= $row['kode_guru'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-delete">Hapus</button>
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

    <div class="section-card">
        <p class="section-title">Guru Non-Aktif</p>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Jabatan</th>
                    <th>Telepon</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $found2 = false; while ($row = mysqli_fetch_assoc($result_nonaktif)): $found2 = true; ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['kode_guru']) ?></td>
                    <td><b><?= htmlspecialchars($row['nama_pengguna']) ?></b></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><span class="nonaktif-badge"><?= htmlspecialchars($row['jabatan']) ?></span></td>
                    <td><?= htmlspecialchars($row['telp']) ?></td>
                    <td>
                        <div class="action-cell">
                            <a href="edit.php?id=<?= $row['kode_guru'] ?>" class="btn-edit">Edit</a>
                            <form action="/poin_siswa/modules/guru/process.php" method="post" onsubmit="return confirm('Hapus data <?= $row['nama_pengguna'] ?>?')">
                                <input type="hidden" name="kode_guru" value="<?= $row['kode_guru'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn-delete">Hapus</button>
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