<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Kebun Sawit</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Style untuk body dan font */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        /* Container untuk form login */
        .login-container {
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Style untuk judul login */
        h2 {
            color: #333;
            font-weight: 500;
            margin-bottom: 20px;
        }

        /* Logo di bagian atas */
        .logo img {
            width: 100px;
            margin-bottom: 20px;
        }

        /* Style untuk form label dan input */
        label {
            display: block;
            text-align: left;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
            margin-top: 15px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        /* Button login */
        button {
            width: 100%;
            padding: 12px 0;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Style untuk pesan error */
        .error-message {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }

        /* Responsif */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px;
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Logo Kebun -->
        <div class="logo">
            <img src="logo.png" alt="Logo Kebun Sawit"> <!-- Ganti dengan path logo Anda -->
        </div>

        <h2>Login Manajemen Kebun Sawit</h2>

        <!-- Form login -->
        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </form>

        <!-- Menampilkan pesan error jika ada -->
        <?php
        if (isset($_SESSION['login_error'])) {
            echo "<p class='error-message'>".$_SESSION['login_error']."</p>";
            unset($_SESSION['login_error']);
        }
        ?>
    </div>

</body>
</html>
