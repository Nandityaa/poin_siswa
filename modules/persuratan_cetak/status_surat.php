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
.page-wrapper { max-width: 1150px; margin: 36px auto; padding: 0 20px; }
.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
.page-header h2 { margin: 0; font-size: 26px; color: #1a2a3a; font-weight: 700; }
.section-card { background: white; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); overflow: hidden; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th {
    background: #1a2a3a; color: #fff;
    padding: 13px 16px; font-size: 12px; font-weight: 600;
    text-align: center; text-transform: uppercase; letter-spacing: 0.5px;
    border-right: 1px solid rgba(255,255,255,0.1);
}
.data-table th:first-child, .data-table th:nth-child(2), .data-table th:nth-child(3) { text-align: left; }
.data-table td {
    padding: 12px 16px; border-bottom: 1px solid #f0f2f5;
    font-size: 13px; color: #2c3e50; vertical-align: middle;
    text-align: center;
}
.data-table td:first-child, .data-table td:nth-child(2), .data-table td:nth-child(3) { text-align: left; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tr:hover td { background: #f8fafc; }

.student-name { font-weight: 600; color: #1a2a3a; }
.student-nis { font-size: 11px; color: #94a3b8; }
.badge-kelas { background: #e3f0ff; color: #0056b3; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }

/* Status Badges */
.badge-selesai {
    background: #dcfce7; color: #166534;
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    white-space: nowrap; display: inline-block;
}
.badge-dicetak {
    background: #dbeafe; color: #1e40af;
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700;
    white-space: nowrap; display: inline-block;
}
.badge-belum {
    background: #f1f5f9; color: #94a3b8;
    padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
    white-space: nowrap; display: inline-block;
}

/* Legend */
.legend { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; align-items: center; }
.legend-item { display: flex; align-items: center; gap: 6px; font-size: 13px; color: #475569; }

.empty-row td { text-align: center; padding: 40px; color: #adb5bd; font-style: italic; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <h2>Status Surat</h2>
    </div>

    <div class="legend">
        <strong style="font-size:13px; color:#1a2a3a;">Keterangan:</strong>
        <span class="legend-item"><span class="badge-belum">Belum Dibuat</span> Belum pernah dicetak</span>
        <span class="legend-item"><span class="badge-dicetak">Sudah Dicetak</span> Surat sudah dicetak, proses berjalan</span>
        <span class="legend-item"><span class="badge-selesai">Sudah Ditandatangani</span> Proses selesai</span>
    </div>

    <div class="section-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Perjanjian Siswa</th>
                    <th>Perjanjian Orang Tua</th>
                    <th>Panggilan Orang Tua</th>
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

<?php include ROOTPATH . "/layouts/footer.php"; ?>
