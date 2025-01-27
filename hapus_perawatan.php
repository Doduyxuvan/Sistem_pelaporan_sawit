<?php
session_start();
require_once "library/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Cek apakah ID perawatan ada di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query untuk menghapus perawatan berdasarkan ID
    $query = "DELETE FROM perawatan WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id); // Bind parameter ID

    if ($stmt->execute()) {
        echo "<p>Perawatan berhasil dihapus.</p>";
        echo "<p><a href='perawatan.php'>Kembali ke Daftar Perawatan</a></p>";
    } else {
        echo "<p>Gagal menghapus perawatan.</p>";
    }
} else {
    echo "<p>ID perawatan tidak ditemukan.</p>";
}
?>
