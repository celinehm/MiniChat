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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 350px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .error {
            color: #e53e3e;
            text-align: center;
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Connexion</h2>

    <p class="error"><?= $messageErreur ?></p>

    <form method="post">
        <label>Pseudo</label>
        <input type="text" name="username" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>

</div>

</body>
</html>
