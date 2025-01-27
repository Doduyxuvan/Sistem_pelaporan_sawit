<?php
function anti_injection($data) {
    global $conn;
    // Menghilangkan karakter-karakter yang tidak diinginkan
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = $conn->real_escape_string($data);
    return $data;
}
?>
