<?php
if(!isset($_COOKIE['username'])){
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href = '/poin_siswa/modules/auth/login.php';</script>";
    exit;
}
$current_page = $_SERVER['REQUEST_URI'];
function isActive($path) {
    global $current_page;
    return strpos($current_page, $path) !== false ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Informasi Pelanggaran Siswa</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
    :root {
        --primary: #1a1a1a;
        --primary-dark: #000;
        --secondary: #fff;
        --bg: #fcfcfc;
        --text: #1a1a1a;
        --text-light: #64748b;
        --nav-bg: #fff;
        --border-color: #e2e8f0;
        --card-bg: #fff;
        --table-header-bg: #1a1a1a;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.02);
        --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.04), 0 2px 4px -1px rgba(0,0,0,0.02);
        --shadow-lg: 0 12px 40px rgba(0,0,0,0.06);
    }
    
    html, body {
        max-width: 100vw;
        overflow-x: hidden !important;
        position: relative;
        width: 100%;
        margin: 0;
        padding: 0;
        background-color: var(--bg);
        -webkit-overflow-scrolling: touch;
    }
    
    body {
        font-family: 'Inter', 'Roboto', sans-serif;
        color: var(--text);
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
    }
    
    * { 
        box-sizing: border-box !important; 
    }

    /* GLASSMORPHIC HEADER */
    header {
        background: var(--nav-bg);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 1px solid #000;
        box-shadow: none;
    }

    .header-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 72px;
    }

    /* BRAND LOGO/TITLE */
    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: #000;
        z-index: 1001;
    }
    .brand i {
        font-size: 26px;
        color: #000;
        background: none;
        transition: transform 0.3s ease;
    }
    .brand:hover i { transform: scale(1.1) rotate(-5deg); }
    
    .brand h1 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #000;
    }

    /* NAVIGATION DESKTOP */
    nav { display: flex; align-items: center; }
    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 4px;
        align-items: center;
    }
    nav ul li { position: relative; }
    nav ul li a {
        color: var(--text);
        text-decoration: none;
        font-weight: 500;
        font-size: 13.5px;
        padding: 10px 18px;
        border-radius: 10px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    nav ul li a:hover, nav ul li a.active {
        color: #000;
        background: #f8f9fa;
    }
    nav ul li a i { font-size: 16px; color: #000; opacity: 1; }
    nav ul li a:hover i { transform: translateY(-1px); }

    /* OVERFLOW PROTECTION & GLOBAL SCROLL */
    html {
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden !important;
        scroll-behavior: smooth;
    }
    
    body {
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden !important;
        font-family: 'Inter', sans-serif;
        background-color: var(--bg);
        margin: 0;
        padding: 0;
        position: relative;
    }
    
    * { 
        box-sizing: border-box !important;
        -webkit-tap-highlight-color: transparent;
    }

    .btn-entri-pelanggaran {
        background: #1a1a1a !important; color: #fff !important; 
        border: 1px solid #1a1a1a !important; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
        font-weight: 700 !important;
        padding: 8px 16px !important;
        font-size: 13px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .btn-entri-pelanggaran:hover { background: #000 !important; transform: translateY(-1px) !important; }

    /* COMPACT FAB (MOBILE) */
    .fab-mobile {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 52px;
        height: 52px;
        background: #1a1a1a;
        color: white !important;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        z-index: 2000;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .fab-mobile:active { transform: scale(0.9); background: #000; }

    /* DROPDOWN DESKTOP */
    .dropdown > a::after {
        content: '\f107';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        font-size: 12px;
        margin-left: 6px;
        transition: transform 0.3s ease;
        opacity: 1;
        color: #000;
    }
    .dropdown:hover > a::after { transform: rotate(180deg); color: #000; }
    
    .dropdown-content {
        visibility: hidden;
        opacity: 0;
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        background: #fff;
        min-width: 220px;
        display: flex;
        flex-direction: column;
        width: auto;
        box-shadow: 0 12px 32px rgba(0,0,0,0.10);
        border-radius: 12px;
        border: none;
        margin-top: 8px;
        padding: 12px;
        transform: translateY(15px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 100;
    }
    
    /* Pseudo-element to bridge gap so hover doesn't break */
    .dropdown::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        height: 20px;
    }
    
    .dropdown:hover .dropdown-content {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }
    
    .dropdown-content li { display: block; width: 100%; margin-bottom: 4px; }
    .dropdown-content li:last-child { margin-bottom: 0; }
    
    .dropdown-content a {
        padding: 11px 16px;
        color: #1a1a1a !important;
        background: transparent !important;
        border-radius: 8px;
        font-weight: 500;
        font-size: 13.5px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.2s ease;
        border: none !important;
    }
    .dropdown-content a:hover {
        background: #f8f9fa !important;
        color: #000 !important;
        transform: translateX(4px);
    }
    .dropdown-content a i {
        color: #1a1a1a !important;
        font-size: 14px;
        width: 18px;
        text-align: center;
    }

    /* NOTIFICATION BUTTON */
    .notif-btn {
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 10px 18px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #1a1a1a;
        font-weight: 500;
        font-size: 14px;
    }
    .notif-btn:hover { background: #f8f9fa; border-color: #cbd5e1; }
    .notif-icon { font-size: 16px; color: #1a1a1a; }
    .notif-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: 800;
        height: 18px;
        min-width: 18px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 4px;
        border: 2px solid white;
    }

    /* NOTIF DROPDOWN */
    .notif-dropdown {
        display: none;
        position: absolute;
        right: 0; top: 120%;
        background: white;
        min-width: 320px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        z-index: 200;
        animation: dropDownAnim 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes dropDownAnim {
        from { opacity: 0; transform: translateY(-10px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .notif-dropdown.show { display: block; }
    .notif-header {
        background: #f8fafc;
        padding: 16px 20px;
        font-weight: 700;
        font-size: 14px;
        border-bottom: 1px solid #e2e8f0;
        color: var(--text);
    }
    .notif-item {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        text-decoration: none;
        color: var(--text);
        transition: background 0.2s;
    }
    .notif-item:hover { background: #f8fafc; }
    .notif-item i {
        color: #ef4444;
        background: #fef2f2;
        padding: 10px;
        border-radius: 50%;
    }
    .notif-text { font-size: 13px; font-weight: 500; line-height: 1.4; }
    .notif-text b { color: #ef4444; font-size: 14px; }

    /* USER PROFILE BTN */
    .profile-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 14px 6px 6px !important;
        background: #fff !important;
        border: 1px solid #e2e8f0;
        border-radius: 12px !important;
        box-shadow: none;
        color: #1a1a1a !important;
        transition: all 0.3s ease;
    }
    .profile-btn:hover { background: #f8f9fa !important; border-color: #cbd5e1; }
    .profile-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #F5F5F5;
        color: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
    }

    /* LOGOUT BUTTON */
    .logout { color: #000 !important; }
    .logout:hover { background: #F5F5F5 !important; color: #000 !important; padding-left: 24px !important; border: 1px solid #000; }

    /* MOBILE MENU ICON */
    .menu-toggle {
        display: none;
        font-size: 24px;
        color: #000;
        cursor: pointer;
        padding: 5px;
    }

    /* MAIN CONTENT WRAPPER */
    main { padding: 40px 20px; min-height: calc(100vh - 150px); max-width: 1400px; margin: 0 auto; }
    
    @media print {
        .no-print { display: none !important; }
        main { padding: 0 !important; background: transparent !important; }
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) {
        .header-container { padding: 0 20px; }
        .menu-toggle { display: block; order: 1; }
        
        nav {
            position: fixed;
            top: 0;
            right: 0;
            width: 280px;
            height: 100vh;
            background: white;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            transform: translateX(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1100;
            padding: 80px 0 40px 0;
            border-left: 1px solid #000000;
            border-right: none;
            display: block;
            overflow-y: auto;
        }
        nav.active { transform: translateX(0); }
        
        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.2);
            backdrop-filter: blur(2px);
            z-index: 1099;
        }
        .sidebar-overlay.active { display: block; }

        nav ul { flex-direction: column; align-items: stretch; gap: 4px; padding: 0; }
        nav ul li { width: 100%; position: relative; }
        
        nav ul li a { 
            padding: 14px 24px; 
            font-size: 14px; 
            border-radius: 0;
            border-right: 4px solid transparent;
            border-left: none;
            gap: 12px;
            text-align: right;
            justify-content: flex-end;
            flex-direction: row-reverse;
        }
        
        /* Active Link Styling */
        nav ul li a.active, nav ul li.open > a {
            background: #f5f5f5;
            color: #000;
            border-right-color: #000;
            border-left-color: transparent;
            font-weight: 700;
        }
        
        /* Submenu Indentation */
        .dropdown-content {
            position: static;
            visibility: visible;
            opacity: 1;
            display: none;
            box-shadow: none;
            border: none;
            padding: 0;
            margin: 0;
            background: #fafafa;
            width: 100%;
            transform: none !important;
        }
        .dropdown.open > .dropdown-content { display: block; }
        
        .dropdown-content li a {
            padding-right: 54px;
            padding-left: 20px;
            font-size: 13px;
            border-right: 4px solid transparent;
        }

        /* Chevron Rotation */
        .dropdown > a::after {
            content: '\f107';
            margin-right: auto;
            margin-left: 0;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dropdown.open > a::after { transform: rotate(180deg); }
        
        .profile-btn { 
            margin: 20px; 
            border-radius: 12px !important; 
            padding: 12px !important;
            justify-content: flex-end;
            flex-direction: row-reverse;
        }
        
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        .btn-entri-pelanggaran {
            display: none !important; /* Hide in nav, use FAB instead */
        }

        .header-container { height: 64px; }
        .brand h1 { font-size: 18px; }
        .brand i { font-size: 22px; }

        .container, .db-page, .sd-page {
            padding-left: 12px !important;
            padding-right: 12px !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
        }

        body { 
            padding-bottom: 70px;
            font-size: 14px;
        }

        .fab-mobile { display: flex; }
    }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header class="no-print">
        <div class="header-container">
            <!-- BRAND -->
            <a href="/poin_siswa/modules/dashboard/<?= (isset($_COOKIE['role']) && $_COOKIE['role'] == 'siswa') ? 'siswa.php' : 'index.php' ?>" class="brand">
                <i class="fa-solid fa-shield-halved"></i>
                <h1>Sistem Pelanggaran Siswa</h1>
            </a>

            <!-- MOBILE TOGGLE -->
            <i class="fa-solid fa-bars menu-toggle" onclick="document.querySelector('nav').classList.toggle('active'); document.querySelector('.sidebar-overlay').classList.toggle('active');"></i>

            <div class="sidebar-overlay" onclick="document.querySelector('nav').classList.remove('active'); this.classList.remove('active');"></div>

            <!-- NAVIGATION -->
            <nav>
                <ul>
                    <?php if (isset($_COOKIE['role']) && $_COOKIE['role'] == 'siswa') { ?>
                    <!-- MENU SISWA -->
                    <li><a href="/poin_siswa/modules/dashboard/siswa.php" class="<?= isActive('dashboard/siswa.php') ?>"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                    
                    <?php 
                    $notif_count = 0;
                    $cookie_nis = mysqli_real_escape_string($conn, $_COOKIE['username']);
                    $q_notif_1 = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pelanggaran_siswa WHERE nis = '$cookie_nis' AND DATE(tanggal) = CURRENT_DATE()");
                    if($q_notif_1) $notif_count += mysqli_fetch_assoc($q_notif_1)['total'];
                    $q_notif_2 = mysqli_query($conn, "SELECT COUNT(ps.id_perjanjian_siswa) AS total FROM perjanjian_siswa ps JOIN pelanggaran_siswa pel ON ps.id_pelanggaran_siswa = pel.id_pelanggaran_siswa WHERE pel.nis = '$cookie_nis' AND ps.status = 'Masih Proses'");
                    if($q_notif_2) $notif_count += mysqli_fetch_assoc($q_notif_2)['total'];
                    $q_notif_3 = mysqli_query($conn, "SELECT COUNT(po.id_perjanjian_ortu) AS total FROM perjanjian_orang_tua po JOIN pelanggaran_siswa pel ON po.id_pelanggaran_siswa = pel.id_pelanggaran_siswa WHERE pel.nis = '$cookie_nis' AND po.status = 'Masih Proses'");
                    if($q_notif_3) $notif_count += mysqli_fetch_assoc($q_notif_3)['total'];
                    ?>
                    
                    <li style="position: relative;">
                        <div class="notif-btn" onclick="document.getElementById('notifMenu').classList.toggle('show'); event.stopPropagation();">
                            <i class="fa-solid fa-bell notif-icon"></i> Notifikasi
                            <?php if($notif_count > 0) { ?><span class="notif-badge"><?= $notif_count ?></span><?php } ?>
                        </div>
                        <div id="notifMenu" class="notif-dropdown" onclick="event.stopPropagation();">
                            <div class="notif-header">Notifikasi Terbaru</div>
                            <?php if($notif_count == 0) { ?>
                                <div style="padding: 20px; text-align: center; color: #9ca3af; font-size:13px;">Tidak ada notifikasi baru</div>
                            <?php } else { 
                                if($q_notif_1 && mysqli_data_seek($q_notif_1, 0) && ($t = mysqli_fetch_assoc($q_notif_1)['total']) > 0) {
                                    echo "<a href='/poin_siswa/modules/dashboard/siswa.php' class='notif-item'><i class='fa-solid fa-triangle-exclamation'></i><div class='notif-text'><b>$t</b> Pelanggaran baru hari ini</div></a>";
                                }
                                if($q_notif_2 && mysqli_data_seek($q_notif_2, 0) && ($t = mysqli_fetch_assoc($q_notif_2)['total']) > 0) {
                                    echo "<a href='/poin_siswa/modules/dashboard/siswa.php' class='notif-item'><i class='fa-solid fa-file-signature'></i><div class='notif-text'><b>$t</b> Surat Perjanjian Anda masih proses</div></a>";
                                }
                                if($q_notif_3 && mysqli_data_seek($q_notif_3, 0) && ($t = mysqli_fetch_assoc($q_notif_3)['total']) > 0) {
                                    echo "<a href='/poin_siswa/modules/dashboard/siswa.php' class='notif-item'><i class='fa-solid fa-users'></i><div class='notif-text'><b>$t</b> Surat Perjanjian Orang Tua proses</div></a>";
                                }
                            } ?>
                        </div>
                    </li>
                    
                    <li class="dropdown"> 
                        <a href="#" class="profile-btn" onclick="this.parentElement.classList.toggle('open')">
                            <div class="profile-avatar"><?= substr($_COOKIE['nama'], 0, 1) ?></div>
                            <span style="font-weight:600;"><?= htmlspecialchars($_COOKIE['nama']) ?></span>
                        </a>
                        <ul class="dropdown-content">
                            <li><a class="logout" href="/poin_siswa/modules/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                        </ul>
                    </li>

                    <?php } else { 
                        $user_role = $_COOKIE['role'] ?? 'guru';
                    ?>
                    <!-- MENU GURU -->
                    <li><a href="/poin_siswa/modules/dashboard/index.php" class="<?= isActive('dashboard/index.php') ?>"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                    
                    <?php if ($user_role == 'admin_bk' || $user_role == 'wakasek' || $user_role == 'kepsek' || $user_role == 'guru' /* fallback */) { ?>
                        <?php if ($user_role == 'admin_bk' || $user_role == 'guru' || $user_role == 'wakasek' || $user_role == 'kepsek') { ?>
                        <li class="dropdown <?= (isActive('guru/index.php') || isActive('siswa/index.php') || isActive('kelas/index.php') || isActive('jenis_pelanggaran/index.php')) ? 'active' : '' ?>" onclick="if(window.innerWidth<=1024) this.classList.toggle('open')">
                            <a href="#"><i class="fa-solid fa-database"></i>Data</a>
                            <ul class="dropdown-content">
                                <li><a href="/poin_siswa/modules/guru/index.php" class="<?= isActive('guru/index.php') ?>">Data Guru</a></li>
                                <li><a href="/poin_siswa/modules/siswa/index.php" class="<?= isActive('siswa/index.php') ?>">Data Siswa</a></li>
                                <li><a href="/poin_siswa/modules/kelas/index.php" class="<?= isActive('kelas/index.php') ?>">Data Kelas</a></li>
                                <li><a href="/poin_siswa/modules/jenis_pelanggaran/index.php" class="<?= isActive('jenis_pelanggaran/index.php') ?>">Jenis Pelanggaran</a></li>
                            </ul>
                        </li>
                        <?php } ?>
                        
                        <li><a href="/poin_siswa/modules/pelanggaran_siswa/create.php" class="btn-entri-pelanggaran"><i class="fa-solid fa-plus"></i> Entri Pelanggaran</a></li>
                        
                        <a href="/poin_siswa/modules/pelanggaran_siswa/create.php" class="fab-mobile">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                        
                        <li class="dropdown <?= isActive('persuratan_cetak/') ? 'active' : '' ?>" onclick="if(window.innerWidth<=1024) this.classList.toggle('open')">
                            <a href="#"><i class="fa-solid fa-envelope-open-text"></i> Menu Surat</a>
                            <ul class="dropdown-content">
                                <li><a href="/poin_siswa/modules/persuratan_cetak/perjanjian_siswa/cetak.php" class="<?= isActive('perjanjian_siswa/cetak.php') ?>">Surat Perjanjian Siswa</a></li>
                                <li><a href="/poin_siswa/modules/persuratan_cetak/perjanjian_ortu/cetak.php" class="<?= isActive('perjanjian_ortu/cetak.php') ?>">Surat Perjanjian Orang Tua</a></li>
                                <li><a href="/poin_siswa/modules/persuratan_cetak/panggilan_ortu/cetak.php" class="<?= isActive('panggilan_ortu/cetak.php') ?>">Surat Panggilan Orang Tua</a></li>
                                <li><a href="/poin_siswa/modules/persuratan_cetak/pindah_sekolah/cetak.php" class="<?= isActive('pindah_sekolah/cetak.php') ?>">Surat Pindah Sekolah</a></li>
                                <li style="border-top: 1px dotted #e2e8f0; margin-top: 4px; padding-top: 4px;"><a href="/poin_siswa/modules/persuratan_cetak/status_surat.php" class="<?= isActive('status_surat.php') ?>"><i class="fa-solid fa-clipboard-check"></i> Status Surat</a></li>
                            </ul>
                        </li>
                        
                        <li class="dropdown <?= isActive('laporan/') ? 'active' : '' ?>" onclick="if(window.innerWidth<=1024) this.classList.toggle('open')">
                            <a href="#"><i class="fa-solid fa-chart-line"></i> Laporan</a>
                            <ul class="dropdown-content">
                                <li><a href="/poin_siswa/modules/laporan/index.php" class="<?= isActive('laporan/index.php') ?>"><i class="fa-solid fa-list-ul"></i><span>Detail Pelanggaran</span></a></li>
                                <li><a href="/poin_siswa/modules/laporan/surat_panggilan.php" class="<?= isActive('laporan/surat_panggilan.php') ?>"><i class="fa-solid fa-envelope"></i><span>Laporan Surat Panggilan</span></a></li>
                                <li><a href="/poin_siswa/modules/laporan/surat_perjanjian.php" class="<?= isActive('laporan/surat_perjanjian.php') ?>"><i class="fa-solid fa-file-signature"></i><span>Laporan Surat Perjanjian</span></a></li>
                                <li><a href="/poin_siswa/modules/laporan/surat_pindah.php" class="<?= isActive('laporan/surat_pindah.php') ?>"><i class="fa-solid fa-arrow-right"></i><span>Laporan Surat Pindah</span></a></li>
                                <li><a href="/poin_siswa/modules/persuratan_cetak/rekap_perjanjian.php" class="<?= isActive('persuratan_cetak/rekap_perjanjian.php') ?>"><i class="fa-solid fa-chart-bar"></i><span>Laporan Rekap Perjanjian</span></a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <!-- GURU MAPEL MENU -->
                        <li><a href="/poin_siswa/modules/pelanggaran_siswa/create.php" style="background: var(--primary); color: white;"><i class="fa-solid fa-plus"></i> Entri Pelanggaran</a></li>
                        <li><a href="/poin_siswa/modules/persuratan_cetak/status_surat.php"><i class="fa-solid fa-clipboard-check"></i> Status Surat</a></li>
                    <?php } ?>
                    
                    <li class="dropdown" onclick="if(window.innerWidth<=1024) this.classList.toggle('open')"> 
                        <a href="#" class="profile-btn">
                            <div class="profile-avatar"><?= substr($_COOKIE['nama'] ?? 'G', 0, 1) ?></div>
                            <span style="font-weight:600;"><?= htmlspecialchars($_COOKIE['nama'] ?? '') ?></span>
                        </a>
                        <ul class="dropdown-content">
                            <li><a href="/poin_siswa/modules/profil/edit.php"><i class="fa-solid fa-user-pen"></i> Edit Profil</a></li>
                            <li><a class="logout" href="/poin_siswa/modules/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </nav>
        </div>
        <script>
            // Close notif dropdown on outside click
            document.addEventListener('click', function(event) {
                var notifMenu = document.getElementById('notifMenu');
                if (notifMenu && notifMenu.classList.contains('show')) {
                    notifMenu.classList.remove('show');
                }
            });
        </script>
    </header>

    <!-- MAIN CONTENT START -->
    <main>

    <?php if(!isset($_COOKIE['role']) || $_COOKIE['role'] !== 'siswa'): ?>
    <!-- COMPACT ACTION BUTTON (MOBILE ONLY) -->
    <a href="/poin_siswa/modules/pelanggaran_siswa/create.php" class="fab-mobile no-print">
        <i class="fa-solid fa-plus"></i>
    </a>
    <?php endif; ?>