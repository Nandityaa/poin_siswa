<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// RBAC: Laporan/Cetak hanya untuk admin_bk, wakasek, kepsek
$allowed_roles = ['admin_bk', 'wakasek', 'kepsek'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_roles)) {
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/index.php';</script>";
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    if ($jenis == 'Siswa') {
        mysqli_query($conn, "UPDATE perjanjian_siswa SET status = '$new_status' WHERE id_perjanjian_siswa = '$id'");
    } else {
        mysqli_query($conn, "UPDATE perjanjian_orang_tua SET status = '$new_status' WHERE id_perjanjian_ortu = '$id'");
    }
    header("Location: surat_perjanjian.php");
    exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_agreement'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    
    if ($jenis == 'Siswa') {
        mysqli_query($conn, "DELETE FROM perjanjian_siswa WHERE id_perjanjian_siswa = '$id'");
    } else {
        mysqli_query($conn, "DELETE FROM perjanjian_orang_tua WHERE id_perjanjian_ortu = '$id'");
    }
    header("Location: surat_perjanjian.php");
    exit;
}

include ROOTPATH . "/layouts/header.php";

// Fetch gabungan dari perjanjian_siswa dan perjanjian_orang_tua
$query = mysqli_query($conn, "
    SELECT 
        'Siswa' AS jenis_perjanjian,
        ps.id_perjanjian_siswa AS id_perjanjian,
        ps.tanggal,
        ps.status,
        s.nis, 
        s.nama_siswa, 
        t.tingkat, 
        pk.program_keahlian, 
        k.rombel
    FROM perjanjian_siswa ps
    JOIN pelanggaran_siswa pel ON ps.id_pelanggaran_siswa = pel.id_pelanggaran_siswa
    JOIN siswa s ON pel.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    
    UNION ALL
    
    SELECT 
        'Orang Tua' AS jenis_perjanjian,
        po.id_perjanjian_ortu AS id_perjanjian,
        po.tanggal,
        po.status,
        s.nis, 
        s.nama_siswa, 
        t.tingkat, 
        pk.program_keahlian, 
        k.rombel
    FROM perjanjian_orang_tua po
    JOIN pelanggaran_siswa pel ON po.id_pelanggaran_siswa = pel.id_pelanggaran_siswa
    JOIN siswa s ON pel.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    
    ORDER BY tanggal DESC
");
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
.badge-jenis { background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600; }
.empty-row td { text-align: center; padding: 40px !important; color: #94a3b8; font-style: italic; }

/* Status Styles */
.status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: inline-block; }
.status-proses { background: #fee2e2; color: #991b1b; }
.status-selesai { background: #dcfce7; color: #166534; }

/* Select Style */
.status-select { 
    padding: 8px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; 
    border: 1px solid #cbd5e1; background-color: #f8fafc; color: #334155; cursor: pointer; outline: none; transition: all 0.2s;
}
.status-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

.action-btns { display: flex; align-items: center; gap: 8px; }
.btn-delete {
    width: 36px; height: 36px; border-radius: 10px; display: flex; justify-content: center; align-items: center;
    background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; transition: all 0.2s; cursor: pointer;
}
.btn-delete:hover { background: #fee2e2; color: #dc2626; border-color: #fecaca; transform: translateY(-1px); }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <div class="page-title">
            <i class="fa-solid fa-handshake"></i>
            <h2>Laporan Surat Perjanjian</h2>
        </div>
    </div>

    <div class="data-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Jenis Perjanjian</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Status Saat Ini</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if(mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) { 
                            $status_class = ($row['status'] == 'Masih Proses') ? 'status-proses' : 'status-selesai';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= date('d M Y, H:i', strtotime($row['tanggal'])) ?></td>
                            <td><span class="badge-jenis">Perjanjian <?= htmlspecialchars($row['jenis_perjanjian']) ?></span></td>
                            <td>
                                <div class="student-name"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                                <div class="student-nis">NIS: <?= htmlspecialchars($row['nis']) ?></div>
                            </td>
                            <td class="kelas-text"><?= htmlspecialchars($row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel']) ?></td>
                            <td>
                                <span class="status-badge <?= $status_class ?>"><?= htmlspecialchars($row['status']) ?></span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <form method="post" style="display:inline; margin:0;">
                                        <input type="hidden" name="id" value="<?= $row['id_perjanjian'] ?>">
                                        <input type="hidden" name="jenis" value="<?= $row['jenis_perjanjian'] ?>">
                                        <input type="hidden" name="update_status" value="1">
                                        <select name="status" class="status-select" onchange="this.form.submit()">
                                            <option value="Masih Proses" <?= $row['status'] == 'Masih Proses' ? 'selected' : '' ?>>Masih Proses</option>
                                            <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </form>
                                    <form method="post" style="display:inline; margin:0;" onsubmit="return confirm('Hapus data perjanjian ini?')">
                                        <input type="hidden" name="id" value="<?= $row['id_perjanjian'] ?>">
                                        <input type="hidden" name="jenis" value="<?= $row['jenis_perjanjian'] ?>">
                                        <input type="hidden" name="delete_agreement" value="1">
                                        <button type="submit" class="btn-delete" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php } 
                    } else { ?>
                        <tr class="empty-row"><td colspan="7">Belum ada data surat perjanjian diproses.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
