<?php
session_start();
require_once "library/koneksi.php";
require_once 'library/check_access.php';  // Pastikan file check_access.php ada di path yang sesuai

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data kebun
    $query = "DELETE FROM kebun WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<p>Kebun berhasil dihapus.</p>";
        echo "<p><a href='kebun.php'>Kembali ke Daftar Kebun</a></p>";
    } else {
        echo "<p>Gagal menghapus kebun.</p>";
    }
}
?>
