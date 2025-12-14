<?php
require "db.php";
$msg = "";

if (!empty($_POST["username"]) && !empty($_POST["password"])) {

    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);

    if ($check->rowCount() > 0) {
        $msg = "Ce pseudo existe déjà";
    } else {
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
<html>
<head><meta charset="UTF-8"><title>Inscription</title></head>
<body>

<h2>Inscription</h2>
<p style="color:red"><?= $msg ?></p>

<form method="post">
    Pseudo : <input type="text" name="username"><br>
    Mot de passe : <input type="password" name="password"><br><br>
    <button type="submit">S'inscrire</button>
</form>

</body>
</html>
