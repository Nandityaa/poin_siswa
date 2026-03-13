<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$result = mysqli_query($conn, "SELECT ps.id_pelanggaran_siswa, ps.tanggal, ps.nis, s.nama_siswa,
    t.tingkat, pk.program_keahlian, k.rombel,
    jp.jenis, jp.poin, ps.keterangan
    FROM pelanggaran_siswa ps
    JOIN siswa s ON ps.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    ORDER BY ps.tanggal DESC");
?>

<style>
.page-wrapper { max-width: 1150px; margin: 36px auto; padding: 0 20px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.page-header h2 { margin: 0; font-size: 26px; color: #1a2a3a; font-weight: 700; }
.btn-add { display: inline-flex; align-items: center; gap: 8px; background: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: background 0.2s, transform 0.2s; border: none; cursor: pointer; }
.btn-add:hover { background: #0056b3; transform: translateY(-1px); }
.section-card { background: white; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th { background: #f1f3f5; color: #343a40; padding: 13px 18px; font-size: 13px; font-weight: 600; text-align: left; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #dee2e6; }
.data-table td { padding: 12px 18px; border-bottom: 1px solid #f0f2f5; color: #2c3e50; font-size: 14px; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #f8f9fa; }
.poin-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
.poin-low { background: #d4edda; color: #155724; }
.poin-mid { background: #fff3cd; color: #856404; }
.poin-high { background: #f8d7da; color: #721c24; }
.jenis-badge { background: #e9ecef; color: #495057; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.student-name { font-weight: 600; }
.student-nis { font-size: 12px; color: #6c757d; }
.kelas-text { font-size: 13px; color: #495057; }
.datetime-text { font-size: 13px; color: #6c757d; }
.btn-delete { padding: 6px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; transition: all 0.2s; background: #fde8e8; color: #c40808; }
.btn-delete:hover { background: #dc3545; color: white; }
.empty-row td { text-align: center; padding: 40px; color: #adb5bd; font-style: italic; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <h2>Laporan Detail Pelanggaran</h2>
        <a href="/poin_siswa/modules/pelanggaran_siswa/create.php" class="btn-add">+ Entri Pelanggaran</a>
    </div>

    <div class="section-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="12%">Tanggal</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Jenis Pelanggaran</th>
                    <th width="8%">Poin</th>
                    <th>Keterangan</th>
                    <th width="8%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($result)): $found = true;
                    $poin = (int)$row['poin'];
                    $poin_class = ($poin >= 50) ? 'poin-high' : (($poin >= 20) ? 'poin-mid' : 'poin-low');
                    $kelas = $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'];
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td class="datetime-text"><?= date("d M Y", strtotime($row['tanggal'])) ?><br><small><?= date("H:i", strtotime($row['tanggal'])) ?></small></td>
                    <td>
                        <div class="student-name"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                        <div class="student-nis">NIS: <?= $row['nis'] ?></div>
                    </td>
                    <td class="kelas-text"><?= htmlspecialchars($kelas) ?></td>
                    <td><span class="jenis-badge"><?= htmlspecialchars($row['jenis']) ?></span></td>
                    <td><span class="poin-badge <?= $poin_class ?>"><?= $poin ?></span></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                    <td>
                        <form action="/poin_siswa/modules/pelanggaran_siswa/process.php" method="post" onsubmit="return confirm('Hapus data pelanggaran ini?')">
                            <input type="hidden" name="id" value="<?= $row['id_pelanggaran_siswa'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn-delete">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; if (!$found): ?>
                <tr class="empty-row"><td colspan="8">Belum ada data pelanggaran tercatat.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
