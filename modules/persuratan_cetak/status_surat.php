<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

// Fetch all students with at least 1 violation, and their letter status
$query = mysqli_query($conn, "
    SELECT 
        s.nis, s.nama_siswa,
        t.tingkat, pk.program_keahlian, k.rombel,
        -- Perjanjian Siswa
        MAX(CASE WHEN ps.status = 'Selesai' THEN 'selesai'
                 WHEN ps.id_perjanjian_siswa IS NOT NULL THEN 'dicetak'
                 ELSE NULL END) AS status_perj_siswa,
        -- Perjanjian Ortu
        MAX(CASE WHEN po.status = 'Selesai' THEN 'selesai'
                 WHEN po.id_perjanjian_ortu IS NOT NULL THEN 'dicetak'
                 ELSE NULL END) AS status_perj_ortu,
        -- Panggilan Ortu (from surat_keluar)
        MAX(CASE WHEN sk_panggilan.id_surat_keluar IS NOT NULL THEN 'dicetak' ELSE NULL END) AS status_panggilan,
        -- Pindah Sekolah (from surat_keluar)
        MAX(CASE WHEN sk_pindah.id_surat_keluar IS NOT NULL THEN 'dicetak' ELSE NULL END) AS status_pindah
    FROM siswa s
    JOIN pelanggaran_siswa pel ON s.nis = pel.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    LEFT JOIN perjanjian_siswa ps ON ps.id_pelanggaran_siswa = pel.id_pelanggaran_siswa
    LEFT JOIN perjanjian_orang_tua po ON po.id_pelanggaran_siswa = pel.id_pelanggaran_siswa
    LEFT JOIN surat_keluar sk_panggilan ON sk_panggilan.nis = s.nis AND sk_panggilan.jenis_surat = 'panggilan_ortu'
    LEFT JOIN surat_keluar sk_pindah ON sk_pindah.nis = s.nis AND sk_pindah.jenis_surat = 'pindah_sekolah'
    GROUP BY s.nis, s.nama_siswa, t.tingkat, pk.program_keahlian, k.rombel
    ORDER BY t.tingkat, pk.program_keahlian, k.rombel, s.nama_siswa
");

function statusBadge($status) {
    if ($status === 'selesai') {
        return '<span class="badge-selesai">Sudah Ditandatangani</span>';
    } elseif ($status === 'dicetak') {
        return '<span class="badge-dicetak">Sudah Dicetak</span>';
    } else {
        return '<span class="badge-belum">Belum Dibuat</span>';
    }
}
?>

<style>
.page-wrapper { font-family: 'Inter', sans-serif; max-width: 1200px; margin: 40px auto; padding: 0 20px; animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.page-title { display: flex; align-items: center; gap: 12px; }
.page-title i { font-size: 28px; color: var(--primary); }
.page-title h2 { margin: 0; font-size: 24px; font-weight: 700; color: #1e293b; letter-spacing: -0.5px; }

.legend { background: white; padding: 16px 20px; border-radius: 12px; display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.05); }
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #475569; }

.data-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; }
.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
.data-table th { background: #f8fafc; padding: 16px 20px; font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 1px solid #e2e8f0; }
.data-table td { padding: 16px 20px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover { background: #f8fafc; }

.student-name { font-weight: 600; color: #1e293b; }
.student-nis { font-size: 12px; color: #64748b; margin-top: 2px; }
.badge-kelas { background: #eff6ff; color: #2563eb; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }

/* Status Badges */
.badge-selesai { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; }
.badge-dicetak { background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-block; }
.badge-belum { background: #f1f5f9; color: #64748b; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }

.empty-row td { text-align: center; padding: 40px !important; color: #94a3b8; font-style: italic; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <div class="page-title">
            <i class="fa-solid fa-list-check"></i>
            <h2>Status Surat Siswa</h2>
        </div>
    </div>

    <div class="legend">
        <strong style="color: #1e293b; font-size: 14px;">Keterangan Status:</strong>
        <span class="legend-item"><span class="badge-belum">Belum Dibuat</span> Belum pernah dicetak</span>
        <span class="legend-item"><span class="badge-dicetak">Sudah Dicetak</span> Menunggu tanda tangan</span>
        <span class="legend-item"><span class="badge-selesai">Sudah Ditandatangani</span> Proses selesai</span>
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Perjanjian Siswa</th>
                        <th>Perjanjian Ortu</th>
                        <th>Panggilan Ortu</th>
                        <th>Surat Pindah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; $found = false; while ($row = mysqli_fetch_assoc($query)): $found = true;
                        $kelas = $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'];
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <div class="student-name"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                            <div class="student-nis">NIS: <?= htmlspecialchars($row['nis']) ?></div>
                        </td>
                        <td><span class="badge-kelas"><?= htmlspecialchars($kelas) ?></span></td>
                        <td><?= statusBadge($row['status_perj_siswa']) ?></td>
                        <td><?= statusBadge($row['status_perj_ortu']) ?></td>
                        <td><?= statusBadge($row['status_panggilan']) ?></td>
                        <td><?= statusBadge($row['status_pindah']) ?></td>
                    </tr>
                    <?php endwhile; if (!$found): ?>
                    <tr class="empty-row"><td colspan="7">Belum ada siswa dengan data pelanggaran tercatat.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
