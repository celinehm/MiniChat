<?php
$host = "localhost";
$dbname = "minichat";
$user = "root";
$pass = "";
// Connexion à la base de données avec PDO
//pdo: PHP Data Objects
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=minichat;charset=utf8",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Erreur BD : " . $e->getMessage());
}
