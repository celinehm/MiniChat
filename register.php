<?php
require "db.php";
// Message d'erreur
$msg = "";

// Traitement du formulaire d'inscription
if (!empty($_POST["username"]) && !empty($_POST["password"])) {

    // Nettoyage et hachage du mot de passe
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Vérification de l'existence du pseudo
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);

    // Si le pseudo existe déjà
    if ($check->rowCount() > 0) {
        $msg = "Ce pseudo existe déjà";
    } else {
        // Insertion dans la base de données
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password)
            VALUES (?, ?)
        ");
        $stmt->execute([$username, $password]);
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>

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
            background: #38a169;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #2f855a;
        }

        .link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .link a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Inscription</h2>

    <p class="error"><?= $msg ?></p>

    <form method="post">
        <label>Pseudo</label>
        <input type="text" name="username" required>

        <label>Mot de passe</label>
        <input type="password" name="password" required>

        <button type="submit">S'inscrire</button>
    </form>

    <div class="link">
        <a href="login.php">Déjà inscrit ? Se connecter</a>
    </div>

</div>

</body>
</html>
