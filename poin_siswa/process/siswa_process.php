<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa/poin_siswa');
include ROOTPATH . "/config/config.php";

// Helper function to get next ID because AI is uncertain/dangerous without check
function getNextId($conn, $table, $id_col)
{
    $q = mysqli_query($conn, "SELECT MAX($id_col) as max_id FROM $table");
    $row = mysqli_fetch_assoc($q);
    return ($row['max_id']) ? $row['max_id'] + 1 : 1;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_GET['action'])) {

    $action = $_POST['action'] ?? $_GET['action'];

    // --- ADD DATA ---
    if ($action == 'add') {
        // 1. Prepare Data
        $nis = $_POST['nis'];
        $nama_siswa = $_POST['nama_siswa'];
        $jk = $_POST['jenis_kelamin'];
        $kelas_id = $_POST['id_kelas'];
        $alamat = $_POST['alamat'];
        $password = $_POST['password']; // Default password provided in form
        $status = 'aktif';

        // Parent Data
        $ayah = $_POST['ayah'];
        $ibu = $_POST['ibu'];
        $wali = $_POST['wali'];
        $p_ayah = $_POST['pekerjaan_ayah'];
        $p_ibu = $_POST['pekerjaan_ibu'];
        $p_wali = $_POST['pekerjaan_wali'];
        $a_ayah = $_POST['alamat_ayah'];
        $a_ibu = $_POST['alamat_ibu'];
        $a_wali = $_POST['alamat_wali'];
        $t_ayah = $_POST['no_telp_ayah'];
        $t_ibu = $_POST['no_telp_ibu'];
        $t_wali = $_POST['no_telp_wali'];

        // 2. Insert into Ortu_Wali first
        // Check if ortu_wali ID is auto increment or not. We use getNextId to be safe manually.
        $new_ortu_id = getNextId($conn, 'ortu_wali', 'id_ortu_wali');

        $q_ortu = "INSERT INTO ortu_wali (id_ortu_wali, ayah, ibu, wali, pekerjaan_ayah, pekerjaan_ibu, pekerjaan_wali, no_telp_ayah, no_telp_ibu, no_telp_wali, alamat_ayah, alamat_ibu, alamat_wali) 
                   VALUES ('$new_ortu_id', '$ayah', '$ibu', '$wali', '$p_ayah', '$p_ibu', '$p_wali', '$t_ayah', '$t_ibu', '$t_wali', '$a_ayah', '$a_ibu', '$a_wali')";

        if (!mysqli_query($conn, $q_ortu)) {
            die("Error Insert Ortu: " . mysqli_error($conn));
        }

        // 3. Insert into Siswa
        $q_siswa = "INSERT INTO siswa (nis, nama_siswa, jenis_kelamin, alamat, password, status, id_ortu_wali, id_kelas) 
                    VALUES ('$nis', '$nama_siswa', '$jk', '$alamat', '$password', '$status', '$new_ortu_id', '$kelas_id')";

        if (mysqli_query($conn, $q_siswa)) {
            echo "<script>alert('Data berhasil ditambahkan'); window.location.href='../pages/siswa/list.php';</script>";
        } else {
            die("Error Insert Siswa: " . mysqli_error($conn));
        }

        // --- EDIT DATA ---
    } elseif ($action == 'edit') {
        $nis = $_POST['nis']; // PK of student
        $nama_siswa = $_POST['nama_siswa'];
        $jk = $_POST['jenis_kelamin'];
        $kelas_id = $_POST['id_kelas']; // Dropdown value
        $alamat = $_POST['alamat'];

        // Parent Data
        $ayah = $_POST['ayah'];
        $ibu = $_POST['ibu'];
        $wali = $_POST['wali'];
        $p_ayah = $_POST['pekerjaan_ayah'];
        $p_ibu = $_POST['pekerjaan_ibu'];
        $p_wali = $_POST['pekerjaan_wali'];
        $a_ayah = $_POST['alamat_ayah'];
        $a_ibu = $_POST['alamat_ibu'];
        $a_wali = $_POST['alamat_wali'];
        $t_ayah = $_POST['no_telp_ayah'];
        $t_ibu = $_POST['no_telp_ibu'];
        $t_wali = $_POST['no_telp_wali'];

        // Get Ortu ID from Siswa to update the correct parent record
        $get_ortu = mysqli_query($conn, "SELECT id_ortu_wali FROM siswa WHERE nis = '$nis'");
        $row_ortu = mysqli_fetch_assoc($get_ortu);
        $id_ortu = $row_ortu['id_ortu_wali'];

        // Update Ortu_Wali
        $q_ortu = "UPDATE ortu_wali SET 
                   ayah='$ayah', ibu='$ibu', wali='$wali', 
                   pekerjaan_ayah='$p_ayah', pekerjaan_ibu='$p_ibu', pekerjaan_wali='$p_wali',
                   no_telp_ayah='$t_ayah', no_telp_ibu='$t_ibu', no_telp_wali='$t_wali',
                   alamat_ayah='$a_ayah', alamat_ibu='$a_ibu', alamat_wali='$a_wali'
                   WHERE id_ortu_wali='$id_ortu'";
        mysqli_query($conn, $q_ortu); // Execute and proceed even if no rows changed

        // Update Siswa
        $q_siswa = "UPDATE siswa SET 
                    nama_siswa='$nama_siswa', jenis_kelamin='$jk', alamat='$alamat', id_kelas='$kelas_id' 
                    WHERE nis='$nis'";

        if (mysqli_query($conn, $q_siswa)) {
            echo "<script>alert('Data berhasil diupdate'); window.location.href='../pages/siswa/list.php';</script>";
        } else {
            die("Error Update Siswa: " . mysqli_error($conn));
        }

        // --- DELETE DATA ---
    } elseif ($action == 'delete') {
        $id = $_POST['id'] ?? $_GET['id'];

        // Get Ortu ID before deleting siswa to clean up
        $get_ortu = mysqli_query($conn, "SELECT id_ortu_wali FROM siswa WHERE nis = '$id'");
        $row_ortu = mysqli_fetch_assoc($get_ortu);
        $id_ortu = $row_ortu['id_ortu_wali'];

        // Delete Siswa first (has FK to Ortu, usually Parent record can stay, but typically 1-1 here we delete both?)
        // Delete Siswa
        $q_del_siswa = "DELETE FROM siswa WHERE nis = '$id'";
        if (mysqli_query($conn, $q_del_siswa)) {
            // Optional: Delete Ortu if intended to be 1-to-1 and exclusive
            if ($id_ortu) {
                mysqli_query($conn, "DELETE FROM ortu_wali WHERE id_ortu_wali = '$id_ortu'");
            }
            echo "<script>alert('Data berhasil dihapus'); window.location.href='../pages/siswa/list.php';</script>";
        } else {
            die("Error Delete: " . mysqli_error($conn));
        }
    }
}
?>