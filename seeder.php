<?php
require_once "library/koneksi.php";

// Data pengguna yang ingin ditambahkan
$userData = [
    ['username' => 'admin', 'password' => 'admin', 'level' => 'admin', 'nama' => 'Administrator'],
    ['username' => 'manager', 'password' => 'manager', 'level' => 'manager', 'nama' => 'Manager'],
];

foreach ($userData as $user) {
    $username = $user['username'];
    $password = $user['password'];
    $level = $user['level'];
    $nama = $user['nama'];

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk menambahkan user ke database
    $query = "INSERT INTO users (username, password, level, nama) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $username, $hashedPassword, $level, $nama);
    $stmt->execute();
}

echo "Pengguna berhasil ditambahkan!";
?>
