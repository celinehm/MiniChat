<?php
session_start();
require "db.php";

// Message d'erreur
$messageErreur = "";

// Fonction pour récupérer l'IP du client de manière fiable
function getClientIP() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];

    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ipList[0]); // Prendre la première IP

    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        return $_SERVER['REMOTE_ADDR'];

    } else {
        return 'IP non disponible';
    }
}

// Traitement du formulaire de connexion
if (!empty($_POST['username']) && !empty($_POST['password'])) {

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($_POST['password'], $user['password'])) {

        // Récupération de l'IP
        $ip = getClientIP();

        // Mise à jour du nombre de connexions, de l'IP et de la date de dernière connexion
        $update = $pdo->prepare("
            UPDATE users
            SET nb_connexions = nb_connexions + 1,
                last_ip = ?,
                last_login = NOW()
            WHERE id = ?
        ");
        $update->execute([$ip, $user['id']]);

        // Création de la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: chat.php");
        exit;
    } else {
        $messageErreur = "Pseudo ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>

<h2>Connexion</h2>

<p style="color:red"><?= $messageErreur ?></p>

<form method="post">
    Pseudo : <input type="text" name="username" required><br><br>
    Mot de passe : <input type="password" name="password" required><br><br>
    <button type="submit">Se connecter</button>
</form>

</body>
</html>
