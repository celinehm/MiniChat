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
<html>
<head>
    <meta charset="UTF-8">
    <title>Mini Chat</title>
</head>
<body>

<h1>Mini Chat</h1>

<p>
    👤 Utilisateur : <strong><?= $user['username'] ?></strong><br>
    🔢 Nombre de connexions : <strong><?= $user['nb_connexions'] ?></strong><br>
    🌍 Dernière IP : <strong><?= $user['last_ip'] ?></strong><br>
    🕒 Dernière connexion : <strong><?= $user['last_login'] ?></strong>
</p>
<!-- Lien de déconnexion -->
<a href="logout.php">Déconnexion</a>

<hr>

<!-- Formulaire d'envoi de message -->
<form method="post">
    <input type="text" name="message" placeholder="Écris ton message..." required>
    <button type="submit">Envoyer</button>
</form>

<hr>

<h3>Derniers messages</h3>

<?php
// Récupérer les derniers messages
$req = $pdo->query("
    SELECT users.username, messages.message, messages.date_message
    FROM messages
    JOIN users ON users.id = messages.user_id
    ORDER BY messages.id DESC
");

while ($m = $req->fetch()) {
    echo "<p>
        <strong>{$m['username']}</strong> ({$m['date_message']}): 
        {$m['message']}
    </p>";
}
?>

</body>
</html>
