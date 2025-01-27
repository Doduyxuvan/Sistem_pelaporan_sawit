<?php
// Memeriksa apakah sesi sudah dimulai, jika belum, mulai sesi
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Fungsi untuk memeriksa akses berdasarkan level
function check_access($allowed_levels) {
    if (!in_array($_SESSION['level'], $allowed_levels)) {
        // Jika level tidak sesuai, redirect ke halaman akses ditolak
        header("Location: no_access.php");
        exit();
    }
}
?>
