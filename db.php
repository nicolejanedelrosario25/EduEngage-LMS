<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=eduengage_lms;charset=utf8mb4', 'root', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}
?>
