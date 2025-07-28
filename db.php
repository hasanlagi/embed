<?php
// db.php - versi MySQL
$host = 'localhost';
$dbname = 'kamera';
$user = 'root';
$pass = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS cameras (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        m3u8_url TEXT NOT NULL
    )");
} catch (PDOException $e) {
    die("Koneksi ke database gagal: " . $e->getMessage());
}
?>