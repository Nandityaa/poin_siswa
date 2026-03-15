<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";

$q_siswa = mysqli_query($conn, "SELECT COUNT(nis) AS total FROM siswa");
$tot_siswa = $q_siswa ? mysqli_fetch_assoc($q_siswa)['total'] : 0;

$q_pelanggaran = mysqli_query($conn, "SELECT COUNT(id_pelanggaran_siswa) AS total FROM pelanggaran_siswa WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())");
$tot_pelanggaran = $q_pelanggaran ? mysqli_fetch_assoc($q_pelanggaran)['total'] : 0;

$q_surat = mysqli_query($conn, "SELECT COUNT(id_surat_keluar) AS total FROM surat_keluar");
$tot_surat = $q_surat ? mysqli_fetch_assoc($q_surat)['total'] : 0;

$q_proses = mysqli_query($conn, "SELECT COUNT(*) AS total FROM perjanjian_siswa WHERE status = 'Masih Proses'");
$tot_proses = $q_proses ? mysqli_fetch_assoc($q_proses)['total'] : 0;

$q_latest = mysqli_query($conn, "SELECT ps.tanggal, s.nama_siswa, jp.jenis, jp.poin, t.tingkat, pk.program_keahlian, k.rombel
    FROM pelanggaran_siswa ps
    JOIN siswa s ON ps.nis = s.nis
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    ORDER BY ps.tanggal DESC LIMIT 7");

include ROOTPATH . "/layouts/header.php";
?>

<style>
/* DASHBOARD STYLES */
.db-page {
    font-family: 'Inter', sans-serif;
    padding: 10px 0;
}
.dashboard-container {
    animation: fadeIn 0.5s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.slim-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 4px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}
.slim-greeting h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
}
.slim-greeting p {
    margin: 0;
    font-size: 12px;
    color: #94a3b8;
}

.pill-stats {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding: 2px 0 20px 0;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.pill-stats::-webkit-scrollbar { display: none; }
.pill-item {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    padding: 8px 16px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
    transition: all 0.3s ease;
}
.pill-item:hover { background: #fff; border-color: #000; }
.pill-label { font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.pill-value { font-size: 15px; font-weight: 800; color: #1a1a1a; }

/* STATS DROPPED REPLACED BY PILLS */
.stats-grid { display: none; }

/* LAYOUT GRID */
.db-main-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 32px;
    align-items: start;
}

@media (max-width: 1200px) {
    .db-main-grid { grid-template-columns: 1fr; }
}

/* SIDEBAR CARD */
.db-side-card {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(0,0,0,0.03);
}
.activity-list { list-style: none; padding: 0; margin: 0; }
.activity-item {
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    gap: 12px;
}
.activity-item:last-child { border-bottom: none; }
.activity-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #e2e8f0; margin-top: 6px; flex-shrink: 0;
}
.activity-content p { margin: 0; font-size: 13px; color: #1a1a1a; line-height: 1.4; }
.activity-content small { color: #94a3b8; font-size: 11px; }

/* --- RECENT TABLE --- */
.db-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.db-section-title span {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    background: #f1f5f9;
    padding: 3px 10px;
    border-radius: 20px;
}
.db-table-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}
.db-table {
    width: 100%;
    border-collapse: collapse;
}
.db-table th {
    background: #1a1a1a;
    color: #fff;
    padding: 16px 20px;
    font-size: 11px;
    font-weight: 600;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    border-bottom: none;
}
.db-table td {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: #334155;
    vertical-align: middle;
}
.db-table tr:nth-child(even) td { background: #fcfcfc; }
.db-table tr:last-child td { border-bottom: none; }
.db-table tr:hover td { background: #f8fafc !important; }
.student-name-cell { font-weight: 600; color: #0f172a; }
.student-class { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.poin-chip {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}
.poin-low { background: #dcfce7; color: #166534; }
.poin-mid { background: #fef9c3; color: #854d0e; }
.poin-high { background: #fee2e2; color: #991b1b; }
.jenis-text { color: #475569; font-size: 13px; }
.date-cell { font-size: 13px; color: #94a3b8; }
.no-data { text-align:center; padding: 48px; color: #cbd5e1; font-style: italic; }

@media(max-width: 768px) {
    .db-page { padding: 8px 12px; background: #fff; }
    
    .slim-header {
        border: none;
        padding: 5px 0 10px 0;
        margin-bottom: 5px;
    }
    .slim-greeting h2 { font-size: 13px; font-weight: 800; }
    .slim-greeting p { font-size: 10px; }

    .pill-stats { padding-bottom: 12px; }
    .pill-item { padding: 6px 12px; }
    .pill-label { font-size: 9px; }
    .pill-value { font-size: 13px; }

    /* MOBILE SLIM STATS GRID */
    .stats-grid { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 6px; 
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px;
        margin-top: 5px;
        width: 100%;
        border: 1px solid #f1f5f9;
        margin-bottom: 24px;
    }
    .stat-card { 
        background: transparent !important; 
        box-shadow: none !important; 
        border: none !important;
        padding: 0 !important;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 2px;
    }
    .stat-card:not(:last-child) { border-right: 1px solid #e2e8f0 !important; border-bottom: none !important; border-radius: 0; }

    .stat-icon { display: none; }
    .stat-info h3 { font-size: 8px; opacity: 0.6; color: #64748b; font-weight: 700; margin-bottom: 0; }
    .stat-info .num { font-size: 16px; color: #1a1a1a; margin: 0; font-weight: 800; letter-spacing: -0.5px; }
    .stat-trend { display: none; }

    .db-content-left { padding: 0; }
    .db-section-title { 
        margin-bottom: 12px; 
        font-size: 14px; 
        padding: 0 4px; 
        border: none;
    }
    .db-section-title span { display: none; }
    
    /* MODERN LIST (ACTIVITY) */
    .db-content-right { padding: 24px 0 10px 0; }
    .db-side-card { 
        background: transparent; 
        box-shadow: none; 
        border: none; 
        padding: 0; 
    }
    .activity-item { padding: 10px 4px; border-bottom: 0.5px solid #f1f5f9; gap: 8px; }
    .activity-dot { background: #cbd5e1; width: 5px; height: 5px; margin-top: 5px; }
    .activity-content p { font-size: 12px; }
    .activity-content small { font-size: 10px; }
    
    /* TABLE POLISH - SLIM LIST LOOK */
    .table-responsive {
        border-radius: 12px;
        border: none;
        background: transparent;
    }
    .db-table th { display: none; }
    .db-table td { 
        padding: 12px 8px; 
        font-size: 12px; 
        border-bottom: 0.5px solid #f8fafc;
    }
    .stat-blue, .stat-green, .stat-yellow, .stat-red { border: none; }
    
    .btn-mobile-full { 
        background: #f8fafc !important; 
        border: 1px solid #f1f5f9 !important;
        margin-top: 10px !important;
        padding: 10px !important;
        font-size: 12px !important;
    }

    /* MODERN LIST TABLE TRANSFORMATION */
    .mobile-row { display: block !important; padding: 12px 4px !important; position: relative; }
    .mobile-row td { display: block !important; padding: 2px 0 !important; border: none !important; width: 100% !important; background: transparent !important; }
    .mobile-row .date-cell { font-size: 10px; color: #94a3b8; margin-bottom: 2px; order: 1; }
    .mobile-row .student-cell { order: 2; margin-bottom: 4px; }
    .mobile-row .type-cell { order: 3; display: inline-block !important; width: auto !important; margin-right: 8px; }
    .mobile-row .poin-cell { order: 4; display: inline-block !important; width: auto !important; position: absolute; right: 4px; top: 25px; }
    .m-lbl { display: none; }
}
</style>

<div class="db-page">
    <div class="dashboard-container">

        <div class="slim-header">
            <div class="slim-greeting">
                <h2>Halo, <?= htmlspecialchars($_COOKIE['nama'] ?? 'Admin') ?></h2>
                <p><?= tanggal_indonesia(date('Y-m-d')) ?> &mdash; Dashboard</p>
            </div>
        </div>

        <div class="pill-stats">
            <div class="pill-item">
                <i class="fa-solid fa-user-group" style="font-size: 10px; opacity: 0.5;"></i>
                <span class="pill-label">Total Siswa</span>
                <span class="pill-value"><?= $tot_siswa ?></span>
            </div>
            <div class="pill-item">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 10px; opacity: 0.5;"></i>
                <span class="pill-label">Poin (Bulan Ini)</span>
                <span class="pill-value"><?= $tot_pelanggaran ?></span>
            </div>
            <div class="pill-item">
                <i class="fa-solid fa-envelope" style="font-size: 10px; opacity: 0.5;"></i>
                <span class="pill-label">Surat Keluar</span>
                <span class="pill-value"><?= $tot_surat ?></span>
            </div>
            <div class="pill-item">
                <i class="fa-solid fa-file-contract" style="font-size: 10px; opacity: 0.5;"></i>
                <span class="pill-label">Proses Janji</span>
                <span class="pill-value"><?= $tot_proses ?></span>
            </div>
        </div>

        <div class="db-main-grid">
            <div class="db-content-left">
                <h3 class="db-section-title">
                    Detail Pelanggaran <span>Terbaru</span>
                </h3>
                <div class="db-table-card">
                    <div class="table-responsive">
                        <table class="db-table">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">Tanggal</th>
                                    <th style="width: 35%;">Nama Siswa</th>
                                    <th style="width: 30%;">Pelanggaran</th>
                                    <th style="width: 15%;">Poin</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                        $found = false;
                        while ($row = mysqli_fetch_assoc($q_latest)):
                            $found = true;
                            $poin = (int)$row['poin'];
                            
                            $chip = '';
                            if ($poin >= 50) $chip = 'poin-b';
                            else if ($poin >= 20) $chip = 'poin-s';
                            else $chip = 'poin-r';
                            
                            $kelas = $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'];
                        ?>
                        <tr class="mobile-row">
                            <td class="date-cell">
                                <span class="m-lbl">Tgl:</span> <?= date('d M Y', strtotime($row['tanggal'])) ?>
                            </td>
                            <td class="student-cell">
                                <div class="student-info">
                                    <strong><?= htmlspecialchars($row['nama_siswa']) ?></strong>
                                    <span><?= htmlspecialchars($kelas) ?></span>
                                </div>
                            </td>
                            <td class="type-cell"><span class="badge-jenis"><?= htmlspecialchars($row['jenis']) ?></span></td>
                            <td class="poin-cell"><span class="badge-poin <?= $chip ?>">+<?= $poin ?></span></td>
                        </tr>
                        <?php endwhile;
                        if (!$found): ?>
                        <tr><td style="text-align:center; padding: 48px; color: #cbd5e1; font-style: italic;">Belum ada pelanggaran tercatat.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="db-content-right">
        <h3 class="db-section-title">Aktivitas Sistem</h3>
        <div class="db-side-card">
            <div class="activity-list">
                <?php
                // Get latest 5 activities (from pelanggaran_siswa and surat_keluar hypothetically)
                $q_act = mysqli_query($conn, "SELECT ps.tanggal, s.nama_siswa, 'Input Pelanggaran' as act FROM pelanggaran_siswa ps JOIN siswa s ON ps.nis=s.nis ORDER BY ps.tanggal DESC LIMIT 5");
                while($act = mysqli_fetch_assoc($q_act)):
                ?>
                <div class="activity-item">
                    <div class="activity-dot"></div>
                    <div class="activity-content">
                        <p><strong><?= htmlspecialchars($act['nama_siswa']) ?></strong> - <?= $act['act'] ?></p>
                        <small><?= date('d M, H:i', strtotime($act['tanggal'])) ?></small>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <a href="#" class="btn-mobile-full" style="display:block; text-align:center; margin-top:20px; font-size:12px; font-weight:700; color:#1a1a1a; text-decoration:none; opacity:0.6;">LIHAT SEMUA</a>
        </div>
    </div>
</div>
</div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>