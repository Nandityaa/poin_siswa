<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah permintaan berasal dari metode POST (bukan GET)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $action = $_POST['action'];

    // Jika aksi adalah "delete", maka hapus data siswa berdasarkan NIS
    if ($action == 'delete') {
        // Ambil id_ortu_wali sebelum hapus siswa
        $query_ortu = mysqli_query($conn, "SELECT id_ortu_wali FROM siswa WHERE nis='$nis'");
        $row_ortu = mysqli_fetch_assoc($query_ortu);
        $id_ortu = $row_ortu ? $row_ortu['id_ortu_wali'] : null;

        // Ambil semua id_pelanggaran_siswa terkait NIS ini
        $pel_result = mysqli_query($conn, "SELECT id_pelanggaran_siswa FROM pelanggaran_siswa WHERE nis='$nis'");
        while ($pel = mysqli_fetch_assoc($pel_result)) {
            $id_pel = $pel['id_pelanggaran_siswa'];
            // Hapus perjanjian_siswa yang terkait
            mysqli_query($conn, "DELETE FROM perjanjian_siswa WHERE id_pelanggaran_siswa='$id_pel'");
            // Hapus perjanjian_orang_tua yang terkait
            mysqli_query($conn, "DELETE FROM perjanjian_orang_tua WHERE id_pelanggaran_siswa='$id_pel'");
        }

        // Hapus data pelanggaran siswa
        mysqli_query($conn, "DELETE FROM pelanggaran_siswa WHERE nis='$nis'");

        // Hapus data siswa
        mysqli_query($conn, "DELETE FROM siswa WHERE nis='$nis'") or die(mysqli_error($conn));

        // Hapus data ortu_wali jika ada
        if ($id_ortu) {
            mysqli_query($conn, "DELETE FROM ortu_wali WHERE id_ortu_wali='$id_ortu'");
        }

        // Jika aksi adalah "add", maka tambahkan data siswa beserta orang tua
    } elseif ($action == 'add') {
        // Ambil Data Siswa
        $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
        $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat_siswa']);
        $id_kelas = !empty($_POST['id_kelas']) ? mysqli_real_escape_string($conn, $_POST['id_kelas']) : 'NULL';
        $password = password_hash('Siswa12345*!', PASSWORD_DEFAULT); // Default password

        // Ambil Data Ortu (nama field sesuai dengan form add.php: ayah, ibu, wali)
        $nama_ayah = !empty($_POST['ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['ayah']) . "'" : "NULL";
        $pekerjaan_ayah = !empty($_POST['pekerjaan_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_ayah']) . "'" : "NULL";
        $alamat_ayah = !empty($_POST['alamat_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_ayah']) . "'" : "NULL";
        $no_telp_ayah = !empty($_POST['telp_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['telp_ayah']) . "'" : "NULL";

        $nama_ibu = !empty($_POST['ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['ibu']) . "'" : "NULL";
        $pekerjaan_ibu = !empty($_POST['pekerjaan_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_ibu']) . "'" : "NULL";
        $alamat_ibu = !empty($_POST['alamat_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_ibu']) . "'" : "NULL";
        $no_telp_ibu = !empty($_POST['telp_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['telp_ibu']) . "'" : "NULL";

        $nama_wali = !empty($_POST['wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['wali']) . "'" : "NULL";
        $pekerjaan_wali = !empty($_POST['pekerjaan_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_wali']) . "'" : "NULL";
        $alamat_wali = !empty($_POST['alamat_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_wali']) . "'" : "NULL";
        $no_telp_wali = !empty($_POST['telp_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['telp_wali']) . "'" : "NULL";

        // Query Insert Ortu_Wali
        $query_ortu = "INSERT INTO ortu_wali (ayah, ibu, wali, pekerjaan_ayah, pekerjaan_ibu, pekerjaan_wali, alamat_ayah, alamat_ibu, alamat_wali, no_telp_ayah, no_telp_ibu, no_telp_wali) 
                       VALUES ($nama_ayah, $nama_ibu, $nama_wali, $pekerjaan_ayah, $pekerjaan_ibu, $pekerjaan_wali, $alamat_ayah, $alamat_ibu, $alamat_wali, $no_telp_ayah, $no_telp_ibu, $no_telp_wali)";

        if (mysqli_query($conn, $query_ortu)) {
            // Ambil ID Ortu yang baru saja dibuat
            $id_ortu_wali = mysqli_insert_id($conn);

            // Query Insert Siswa
            $query_siswa = "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, alamat, password, status, id_ortu_wali, id_kelas) 
                            VALUES ('$nis', '$nama_siswa', '$jenis_kelamin', '$alamat', '$password', 'aktif', $id_ortu_wali, $id_kelas)";

            mysqli_query($conn, $query_siswa) or die("Error Insert Siswa: " . mysqli_error($conn));
        } else {
            die("Error Insert Ortu: " . mysqli_error($conn));
        }

        // Jika aksi adalah "edit", maka ubah data siswa dan data orang tua berdasarkan NIS
    } elseif ($action == 'edit') {
        $nama_siswa = mysqli_real_escape_string($conn, $_POST['nama_siswa']);
        $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $id_kelas = !empty($_POST['id_kelas']) ? mysqli_real_escape_string($conn, $_POST['id_kelas']) : 'NULL';

        // Ambil Data Ortu dari form edit (field names: ayah, ibu, wali, dll)
        $ayah = !empty($_POST['ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['ayah']) . "'" : "NULL";
        $ibu = !empty($_POST['ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['ibu']) . "'" : "NULL";
        $wali = !empty($_POST['wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['wali']) . "'" : "NULL";
        $pekerjaan_ayah = !empty($_POST['pekerjaan_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_ayah']) . "'" : "NULL";
        $pekerjaan_ibu = !empty($_POST['pekerjaan_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_ibu']) . "'" : "NULL";
        $pekerjaan_wali = !empty($_POST['pekerjaan_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['pekerjaan_wali']) . "'" : "NULL";
        $alamat_ayah = !empty($_POST['alamat_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_ayah']) . "'" : "NULL";
        $alamat_ibu = !empty($_POST['alamat_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_ibu']) . "'" : "NULL";
        $alamat_wali = !empty($_POST['alamat_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['alamat_wali']) . "'" : "NULL";
        $no_telp_ayah = !empty($_POST['no_telp_ayah']) ? "'" . mysqli_real_escape_string($conn, $_POST['no_telp_ayah']) . "'" : "NULL";
        $no_telp_ibu = !empty($_POST['no_telp_ibu']) ? "'" . mysqli_real_escape_string($conn, $_POST['no_telp_ibu']) . "'" : "NULL";
        $no_telp_wali = !empty($_POST['no_telp_wali']) ? "'" . mysqli_real_escape_string($conn, $_POST['no_telp_wali']) . "'" : "NULL";

        // Cek apakah siswa sudah punya id_ortu_wali
        $query_cek = mysqli_query($conn, "SELECT id_ortu_wali FROM siswa WHERE nis='$nis'");
        $row_cek = mysqli_fetch_assoc($query_cek);
        $id_ortu_wali = $row_cek['id_ortu_wali'];

        if (!empty($id_ortu_wali)) {
            // Update data ortu_wali yang sudah ada
            $query_ortu = "UPDATE ortu_wali SET 
                            ayah=$ayah, ibu=$ibu, wali=$wali,
                            pekerjaan_ayah=$pekerjaan_ayah, pekerjaan_ibu=$pekerjaan_ibu, pekerjaan_wali=$pekerjaan_wali,
                            alamat_ayah=$alamat_ayah, alamat_ibu=$alamat_ibu, alamat_wali=$alamat_wali,
                            no_telp_ayah=$no_telp_ayah, no_telp_ibu=$no_telp_ibu, no_telp_wali=$no_telp_wali
                          WHERE id_ortu_wali=$id_ortu_wali";
            mysqli_query($conn, $query_ortu) or die("Error Update Ortu: " . mysqli_error($conn));
        } else {
            // Jika belum punya data ortu, buat baru
            $query_ortu = "INSERT INTO ortu_wali (ayah, ibu, wali, pekerjaan_ayah, pekerjaan_ibu, pekerjaan_wali, alamat_ayah, alamat_ibu, alamat_wali, no_telp_ayah, no_telp_ibu, no_telp_wali) 
                           VALUES ($ayah, $ibu, $wali, $pekerjaan_ayah, $pekerjaan_ibu, $pekerjaan_wali, $alamat_ayah, $alamat_ibu, $alamat_wali, $no_telp_ayah, $no_telp_ibu, $no_telp_wali)";
            if (mysqli_query($conn, $query_ortu)) {
                $id_ortu_wali = mysqli_insert_id($conn);
            } else {
                die("Error Insert Ortu: " . mysqli_error($conn));
            }
        }

        // Update data siswa
        $query = "UPDATE siswa SET 
                    nama_siswa='$nama_siswa', 
                    jenis_kelamin='$jenis_kelamin', 
                    alamat='$alamat', 
                    id_kelas=$id_kelas, 
                    id_ortu_wali=$id_ortu_wali 
                  WHERE nis='$nis'";
        mysqli_query($conn, $query) or die(mysqli_error($conn));
    }

    // Setelah selesai, arahkan kembali ke halaman daftar siswa
    header("Location: index.php");
    exit;
}
?>

<!-- 
🧠 Penjelasan Singkat:
Kode ini menangani proses CRUD Siswa.
Update terbaru: Memproses Insert data orang tua (ortu_wali) terlebih dahulu sebelum memasukkan data siswa, 
agar relasi foreign key `id_ortu_wali` terbentuk otomatis.
-->