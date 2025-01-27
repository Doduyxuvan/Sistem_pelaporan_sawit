<?php
session_start();
require_once "library/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Cek apakah ID laporan ada dalam URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus laporan berdasarkan ID
    $queryHapus = "DELETE FROM laporan WHERE id = ?";
    $stmt = mysqli_prepare($conn, $queryHapus);

    // Bind parameter dan eksekusi query
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil dihapus, tampilkan pesan dan kembali ke halaman laporan
        echo "<script>
                alert('Laporan berhasil dihapus');
                window.location.href = 'laporan.php';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>
                alert('Gagal menghapus laporan');
                window.location.href = 'laporan.php';
              </script>";
    }

    // Tutup statement
    mysqli_stmt_close($stmt);
}

// Tutup koneksi
mysqli_close($conn);
?>
