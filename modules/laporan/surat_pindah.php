<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

// RBAC: Laporan/Cetak hanya untuk admin_bk, wakasek, kepsek
$allowed_roles = ['admin_bk', 'wakasek', 'kepsek'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_roles)) {
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/index.php';</script>";
    exit;
}

$query = mysqli_query($conn, "SELECT sk.no_surat, sk.tanggal_pembuatan_surat, s.nis, s.nama_siswa, t.tingkat, pk.program_keahlian, k.rombel
    FROM surat_keluar sk
    JOIN siswa s ON sk.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    WHERE sk.jenis_surat = 'pindah'
    ORDER BY sk.tanggal_pembuatan_surat DESC");
?>

<style>
.page-wrapper { font-family: 'Inter', sans-serif; max-width: 1200px; margin: 40px auto; padding: 0 20px; animation: fadeIn 0.4s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
.page-title { display: flex; align-items: center; gap: 12px; }
.page-title i { font-size: 28px; color: var(--primary); }
.page-title h2 { margin: 0; font-size: 24px; font-weight: 700; color: #1e293b; letter-spacing: -0.5px; }

.data-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.05); overflow: hidden; }
.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; white-space: nowrap; }
.data-table th { background: #f8fafc; padding: 16px 20px; font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; border-bottom: 1px solid #e2e8f0; }
.data-table td { padding: 16px 20px; font-size: 14px; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.data-table tr:last-child td { border-bottom: none; }
.data-table tbody tr:hover { background: #f8fafc; }

.student-name { font-weight: 600; color: #1e293b; }
.student-nis { font-size: 12px; color: #64748b; margin-top: 2px; }
.kelas-text { font-size: 13px; color: #475569; font-weight: 500; }
.badge-no-surat { background: #f1f5f9; color: #334155; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; font-family: monospace; display: inline-block; }
.empty-row td { text-align: center; padding: 40px !important; color: #94a3b8; font-style: italic; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <div class="page-title">
            <i class="fa-solid fa-plane-departure"></i>
            <h2>Laporan Surat Pindah Siswa</h2>
        </div>
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal Dicetak</th>
                        <th>No. Surat</th>
                        <th>Siswa</th>
                        <th>Kelas Sebelumnya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_pembuatan_surat'])) ?></td>
                            <td><span class="badge-no-surat"><?= htmlspecialchars($row['no_surat']) ?></span></td>
                            <td>
                                <div class="student-name"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                                <div class="student-nis">NIS: <?= htmlspecialchars($row['nis']) ?></div>
                            </td>
                            <td class="kelas-text"><?= htmlspecialchars($row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel']) ?></td>
                        </tr>
                    <?php } 
                    } else { ?>
                        <tr class="empty-row"><td colspan="5">Belum ada data surat pindah diproses.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
