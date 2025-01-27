<?php
// Koneksi ke database
$conn = new mysqli("localhost", "username_db", "password_db", "nauli_sawit");

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password']; // Password yang dimasukkan oleh admin
    $level = $_POST['level']; // admin / manager / petugas
    $nama = $_POST['nama']; // Nama pengguna

    // Validasi input: pastikan username hanya berisi huruf dan angka
    if (!ctype_alnum($username)) {
        echo "Username hanya boleh mengandung huruf dan angka!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Menggunakan Prepared Statements untuk mencegah SQL Injection
        $stmt = $conn->prepare("INSERT INTO users (username, password, level, nama) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $level, $nama);

        // Menjalankan query
        if ($stmt->execute()) {
            echo "Pengguna berhasil ditambahkan!";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Menutup statement
        $stmt->close();
    }
}

// Menutup koneksi ke database
$conn->close();
?>

<!-- Form untuk menambahkan pengguna baru -->
<form method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    
    <label for="level">Level:</label>
    <select name="level" id="level">
        <option value="admin">Admin</option>
        <option value="manager">Manager</option>
        <option value="petugas">Petugas</option>
    </select>
    
    <label for="nama">Nama:</label>
    <input type="text" name="nama" id="nama" required>
    
    <button type="submit">Tambah Pengguna</button>
</form>
