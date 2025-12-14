<?php
require_once "db.php";

$ip = $_SERVER["REMOTE_ADDR"];
$time = time();
$timeout = 300; // 5 minutes

// Insert ou update IP
$stmt = $pdo->prepare("
    INSERT INTO connectes(ip, ts)
    VALUES(?, ?)
    ON DUPLICATE KEY UPDATE ts = ?
");
$stmt->execute([$ip, $time, $time]);

// Supprimer IP expirées
$pdo->prepare("DELETE FROM connectes WHERE ts < ?")
    ->execute([$time - $timeout]);

// Compter visiteurs
$visiteurs_connectes = $pdo->query("SELECT COUNT(*) FROM connectes")
                           ->fetchColumn();
