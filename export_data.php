<?php
// Koneksi ke database
require_once "library/koneksi.php";
require_once 'library/check_access.php';  // Pastikan file check_access.php ada di path yang sesuai

// Cek apakah user sudah login
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}
// Cek apakah pengguna memiliki akses
check_access(['admin']);

// Query untuk mengambil data yang akan diekspor
$query = "SELECT * FROM kebun"; // Sesuaikan dengan tabel yang ingin diekspor
$result = mysqli_query($conn, $query);

// Mengatur header untuk mengunduh file CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="data_kebun.csv"');
$output = fopen('php://output', 'w');

// Menulis nama kolom sebagai header CSV
fputcsv($output, array('ID Kebun', 'Lokasi', 'Luas', 'Tanggal Tanam'));

// Menulis data dari database ke dalam file CSV
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Menutup file
fclose($output);
exit();
?>
