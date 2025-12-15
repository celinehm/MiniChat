<?php
session_start();
require "db.php";

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupération des infos utilisateur
$stmt = $pdo->prepare("
    SELECT username, nb_connexions, last_ip, last_login
    FROM users
    WHERE id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire de message
if (!empty($_POST['message'])) {
    $message = htmlentities($_POST['message']); // Sécurité contre HTML/JS

    // Insertion dans la table messages
    $stmt = $pdo->prepare("
        INSERT INTO messages (user_id, message)
        VALUES (?, ?)
    ");
    $stmt->execute([$_SESSION['user_id'], $message]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mini Chat</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 70%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .user-info {
            background: #eef2ff;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .logout {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #fff;
            background: #e53e3e;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
        }

        .logout:hover {
            background: #c53030;
        }

        form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            border: none;
            background: #667eea;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #5a67d8;
        }

        .message {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .message strong {
            color: #4c51bf;
        }

        .date {
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Mini Chat</h1>

    <div class="user-info">
        👤 <strong><?= $user['username'] ?></strong><br>
        🔢 Nombre de connexions : <strong><?= $user['nb_connexions'] ?></strong><br>
        🌍 Dernière IP : <strong><?= $user['last_ip'] ?></strong><br>
        🕒 Dernière connexion : <strong><?= $user['last_login'] ?></strong><br>

        <a href="logout.php" class="logout">Déconnexion</a>
    </div>

    <!-- Formulaire d'envoi de message -->
    <form method="post">
        <input type="text" name="message" placeholder="Écris ton message..." required>
        <button type="submit">Envoyer</button>
    </form>

    <h3>Derniers messages</h3>

    <?php
    // Récupérer les messages
    $req = $pdo->query("
        SELECT users.username, messages.message, messages.date_message
        FROM messages
        JOIN users ON users.id = messages.user_id
        ORDER BY messages.id DESC
    ");

    while ($m = $req->fetch()) {
        echo "
        <div class='message'>
            <strong>{$m['username']}</strong>
            <span class='date'>({$m['date_message']})</span><br>
            {$m['message']}
        </div>";
    }
    ?>

</div>

</body>
</html>
