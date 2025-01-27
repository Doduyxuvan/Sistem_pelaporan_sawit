<?php
session_start();
require_once "library/koneksi.php";
require_once 'library/check_access.php';  // Pastikan file check_access.php ada di path yang sesuai

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}
check_access(['admin']);


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Query untuk mendapatkan data kebun berdasarkan ID
    $query = "SELECT * FROM kebun WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $kebun = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lokasi = $_POST['lokasi'];
    $luas = $_POST['luas'];
    $jenis = $_POST['jenis'];

    // Query untuk memperbarui data kebun
    $query = "UPDATE kebun SET lokasi = ?, luas = ?, jenis = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $lokasi, $luas, $jenis, $id);
    if ($stmt->execute()) {
        echo "<p>Kebun berhasil diperbarui.</p>";
        echo "<p><a href='kebun.php'>Kembali ke Daftar Kebun</a></p>";
    } else {
        echo "<p>Gagal memperbarui kebun.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kebun</title>
    <!-- Link to Google Fonts for a modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-size: 16px;
            color: #555;
        }

        input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #218838;
        }

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Kebun</h2>
        <form action="edit_kebun.php?id=<?php echo $kebun['id']; ?>" method="POST">
            <label for="lokasi">Lokasi:</label>
            <input type="text" name="lokasi" id="lokasi" value="<?php echo $kebun['lokasi']; ?>" required>

            <label for="luas">Luas (Ha):</label>
            <input type="text" name="luas" id="luas" value="<?php echo $kebun['luas']; ?>" required>

            <label for="jenis">Jenis:</label>
            <input type="text" name="jenis" id="jenis" value="<?php echo $kebun['jenis']; ?>" required>

            <button type="submit">Perbarui Kebun</button>
        </form>

        <a href="kebun.php" class="btn-back">Kembali ke Daftar Kebun</a>
    </div>
</body>
</html>
