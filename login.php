<?php
session_start();
require_once "library/koneksi.php";
require_once "library/fungsi_standar.php";

// Pastikan form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah 'username' dan 'password' ada
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Menggunakan fungsi anti_injection untuk mencegah SQL Injection
        $username = anti_injection($_POST['username']);
        $password = anti_injection($_POST['password']);

        // Query untuk mendapatkan data user dengan prepared statements
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);  // Binding parameter 's' untuk string
        $stmt->execute();
        $hasil = $stmt->get_result();
        $data = $hasil->fetch_array(MYSQLI_ASSOC);

        // Cek apakah user ditemukan dan password cocok
        if ($data && password_verify($password, $data['password'])) {
            // Menyimpan data session
            $_SESSION['level'] = $data['level'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama'] = $data['nama'];

            // Redirect ke halaman utama setelah login berhasil
            header("Location: dashboard.php");
            exit();
        } else {
            // Jika login gagal, tampilkan pesan error dan redirect ke halaman login
            $_SESSION['login_error'] = "Username atau password salah!";
            header("Location: form_login.php");
            exit();
        }
    }
} else {
    echo "<p>Form login belum disubmit.</p>";
}
?>
