<?php
if(!isset($_COOKIE['username'])){
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href = '/poin_siswa/modules/auth/login.php';</script>";
    exit;
}
?>

<!-- Menandakan bahwa ini adalah dokumen HTML5 -->
<!DOCTYPE html>

<!-- Tag utama pembungkus seluruh halaman, dengan bahasa Indonesia -->
<html lang="id">

<head>
    <!-- Mengatur karakter huruf agar teks tampil dengan benar -->
    <meta charset="UTF-8" />

    <!-- Judul halaman yang tampil di tab browser -->
    <title>Poin Pelanggaran Siswa SMK TI</title>

    <!-- Bagian untuk menulis style (CSS) -->
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f6;
        margin: 0;
        padding: 0;
    }

    header {
        background: #007bff;
        color: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding-bottom: 2px;
    }

    header h1 {
        text-align: center;
        margin: 0;
        padding: 20px 0 10px 0;
        font-size: 24px;
        letter-spacing: 1px;
    }

    nav {
        background: #007bff;
        padding: 10px 0 15px 0;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 25px;
        justify-content: center;
        align-items: center;
    }

    nav ul li {
        display: inline-block;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        padding: 10px 18px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    nav ul li a:hover {
        background: rgba(255,255,255,0.15);
        color: #fff;
    }

    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #ffffff;
        min-width: 200px;
        box-shadow: 0px 8px 24px rgba(0,0,0,0.15);
        z-index: 100;
        margin-top: 10px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #eaeaea;
    }

    .dropdown-content a {
        color: #333 !important;
        padding: 14px 20px;
        text-decoration: none;
        display: block;
        font-weight: 500;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }

    .dropdown-content a:last-child {
        border-bottom: none;
    }

    .dropdown-content a:hover {
        background-color: #f8f9fa;
        color: #007bff !important;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .logout {
        color: #fe4a4a !important;
        font-weight: 700;
    }
    
    .logout:hover {
        background-color: #fe4a4a !important;
        color: white !important;
    }

    .scroll {
        overflow-x: auto;
        max-height: 400px;
    }
    
    .lebar {
        width: 100%;
        max-width: 800px;
    }

    @media print {
        .no-print {
            display: none !important;
        }
        main {
            background: transparent !important;
        }
    }
    .notif-btn {
        position: relative;
        cursor: pointer;
        padding: 10px 18px;
        color: white;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 15px;
        border-radius: 6px;
        transition: background 0.3s;
    }
    .notif-btn:hover {
        background: rgba(255,255,255,0.15);
    }
    .notif-badge {
        position: absolute;
        top: 2px;
        right: 8px;
        background: #ff3b3b;
        color: white;
        font-size: 12px;
        padding: 3px 6px;
        border-radius: 12px;
        font-weight: 700;
        line-height: 1;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .notif-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 55px;
        background-color: white;
        min-width: 320px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        border-radius: 10px;
        z-index: 1000;
        border: 1px solid #eaeaea;
        overflow: hidden;
        animation: dropDownAnim 0.2s ease-out;
    }
    @keyframes dropDownAnim {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .notif-dropdown.show {
        display: block;
    }
    .notif-header {
        background: #f8f9fa;
        padding: 15px 20px;
        font-weight: 700;
        border-bottom: 1px solid #eee;
        color: #333;
        font-size: 15px;
    }
    .notif-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: block;
        color: #444 !important;
        text-decoration: none;
        font-weight: 500 !important;
        font-size: 14px;
        transition: background 0.2s;
    }
    .notif-item:hover {
        background: #f4f7f6 !important;
        color: #007bff !important;
    }
    .notif-item:last-child {
        border-bottom: none;
    }
    .notif-icon {
        width: 22px;
        height: 22px;
        fill: white;
    }
    </style>
</head>

<body>
    <!-- Bagian header (bagian atas halaman) -->
    <header class="no-print">
        <!-- Judul utama halaman -->
        <h1>Aplikasi Poin Pelanggaran Siswa</h1>

        <!-- Navigasi menu utama -->
        <nav>
            <!-- Daftar menu navigasi -->
            <ul>
                <?php if (isset($_COOKIE['role']) && $_COOKIE['role'] == 'siswa') { ?>
                <!-- Menu untuk Siswa -->
                <li><a href="/poin_siswa/modules/dashboard/siswa.php">Dashboard</a></li>
                <?php 
                // Hitung notifikasi khusus siswa (berdasarkan NIS)
                $notif_count = 0;
                $cookie_nis = mysqli_real_escape_string($conn, $_COOKIE['username']); // NIS
                
                $q_notif_1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pelanggaran_siswa WHERE nis = '$cookie_nis' AND DATE(tanggal) = CURRENT_DATE()");
                if($q_notif_1) $notif_count += mysqli_fetch_assoc($q_notif_1)['total'];

                $q_notif_2 = mysqli_query($conn, "SELECT COUNT(ps.id_perjanjian_siswa) AS total FROM perjanjian_siswa ps JOIN pelanggaran_siswa pel ON ps.id_pelanggaran_siswa = pel.id_pelanggaran_siswa WHERE pel.nis = '$cookie_nis' AND ps.status = 'Masih Proses'");
                if($q_notif_2) $notif_count += mysqli_fetch_assoc($q_notif_2)['total'];

                $q_notif_3 = mysqli_query($conn, "SELECT COUNT(po.id_perjanjian_ortu) AS total FROM perjanjian_orang_tua po JOIN pelanggaran_siswa pel ON po.id_pelanggaran_siswa = pel.id_pelanggaran_siswa WHERE pel.nis = '$cookie_nis' AND po.status = 'Masih Proses'");
                if($q_notif_3) $notif_count += mysqli_fetch_assoc($q_notif_3)['total'];
                ?>
                <li style="position: relative;">
                    <div class="notif-btn" onclick="document.getElementById('notifMenu').classList.toggle('show'); event.stopPropagation();">
                        <svg class="notif-icon" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/></svg>
                        Notifikasi
                        <?php if($notif_count > 0) { ?>
                        <span class="notif-badge"><?= $notif_count ?></span>
                        <?php } ?>
                    </div>
                    <div id="notifMenu" class="notif-dropdown" onclick="event.stopPropagation();">
                        <div class="notif-header">Notifikasi Terbaru</div>
                        <?php if($notif_count == 0) { ?>
                            <div style="padding: 15px; text-align: center; color: #777;">Tidak ada notifikasi baru</div>
                        <?php } else { 
                            if($q_notif_1 && mysqli_data_seek($q_notif_1, 0) && ($t = mysqli_fetch_assoc($q_notif_1)['total']) > 0) {
                                echo "<a href='/poin_siswa/modules/dashboard/siswa.php' class='notif-item'><b>$t</b> Pelanggaran baru hari ini</a>";
                            }
                            if($q_notif_2 && mysqli_data_seek($q_notif_2, 0) && ($t = mysqli_fetch_assoc($q_notif_2)['total']) > 0) {
                                echo "<a href='/poin_siswa/modules/dashboard/siswa.php' class='notif-item'><b>$t</b> Surat Perjanjian Anda masih proses</a>";
                            }
                            if($q_notif_3 && mysqli_data_seek($q_notif_3, 0) && ($t = mysqli_fetch_assoc($q_notif_3)['total']) > 0) {
                                echo "<a href='/poin_siswa/modules/dashboard/siswa.php' class='notif-item'><b>$t</b> Surat Perjanjian Orang Tua masih proses</a>";
                            }
                        } ?>
                    </div>
                </li>
                <li class="dropdown"> 
                    <a href="#"><?php echo $_COOKIE['nama'];?></a>
                    <ul class="dropdown-content">
                        <li><a class="logout" href="/poin_siswa/modules/auth/logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php } else { ?>
                <!-- Menu untuk Guru -->
                <li><a href="/poin_siswa/modules/dashboard/index.php">Dashboard</a></li>
                <!-- dropdown semua data-->
                <li class="dropdown">
                    <a href="#">Data</a>
                    <ul class="dropdown-content">
                        <li><a href="/poin_siswa/modules/guru/index.php">Data Guru</a></li>
                        <li><a href="/poin_siswa/modules/siswa/index.php">Data Siswa</a></li>
                        <li><a href="/poin_siswa/modules/kelas/index.php">Data Kelas</a></li>
                        <li><a href="/poin_siswa/modules/jenis_pelanggaran/index.php">Data Jenis Pelanggaran</a></li>
                    </ul>
                </li>
                <li><a href="/poin_siswa/modules/pelanggaran_siswa/create.php">Entri Pelanggaran</a></li>
                <li class="dropdown">
                    <a href="#">Menu Surat</a>
                    <ul class="dropdown-content">
                        <li><a href="/poin_siswa/modules/persuratan_cetak/perjanjian_siswa/cetak.php">Surat Perjanjian Siswa</a></li>
                        <li><a href="/poin_siswa/modules/persuratan_cetak/perjanjian_ortu/cetak.php">Surat Perjanjian Orang Tua</a></li>
                        <li><a href="/poin_siswa/modules/persuratan_cetak/panggilan_ortu/cetak.php">Surat Panggilan Orang Tua</a></li>
                        <li><a href="/poin_siswa/modules/persuratan_cetak/pindah_sekolah/cetak.php">Surat Pindah Sekolah</a></li>
                        <li style="border-top: 1px solid #eee;"><a href="/poin_siswa/modules/persuratan_cetak/status_surat.php">Status Surat</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Laporan</a>
                    <ul class="dropdown-content">
                        <li><a href="/poin_siswa/modules/laporan/index.php">Detail Pelanggaran</a></li>
                        <li><a href="/poin_siswa/modules/laporan/surat_panggilan.php">Laporan Surat Panggilan</a></li>
                        <li><a href="/poin_siswa/modules/laporan/surat_perjanjian.php">Laporan Surat Perjanjian</a></li>
                        <li><a href="/poin_siswa/modules/laporan/surat_pindah.php">Laporan Surat Pindah</a></li>
                    </ul>
                </li>
                <li class="dropdown"> 
                    <a href="#"><?php echo $_COOKIE['nama'];?></a>
                    <ul class="dropdown-content">
                        <li><a href="/poin_siswa/modules/profil/edit.php">Edit Profil</a></li>
                        <li><a class="logout" href="/poin_siswa/modules/auth/logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </nav>

        <script>
            // Script untuk menutup dropdown notifikasi ketika klik di luar kotak
            document.addEventListener('click', function(event) {
                var notifMenu = document.getElementById('notifMenu');
                if (notifMenu && notifMenu.classList.contains('show')) {
                    notifMenu.classList.remove('show');
                }
            });
        </script>
    </header>

    <!-- Bagian utama halaman, tempat isi konten ditampilkan -->
    <main>


    <!-- 
    💡 Penjelasan ringkas struktur HTML-nya:
	•	<!DOCTYPE html> → Menentukan dokumen ini memakai standar HTML5.
	•	<html lang="id"> → Bahasa halaman adalah bahasa Indonesia.
	•	<head> → Bagian kepala, berisi pengaturan halaman (judul, karakter, style).
	•	<body> → Bagian isi tampilan halaman.
	•	<header> → Bagian atas, biasanya berisi judul dan menu navigasi.
	•	<nav> → Area navigasi untuk berpindah ke halaman lain.
	•	<ul> dan <li> → Menyusun daftar menu.
	•	<main> → Area utama yang nanti berisi konten dari halaman lain. 
    -->