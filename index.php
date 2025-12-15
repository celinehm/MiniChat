<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mini Chat</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 40px;
            width: 320px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h1 {
            margin-bottom: 30px;
            color: #333;
        }

        a {
            display: block;
            text-decoration: none;
            padding: 12px;
            margin: 10px 0;
            background: #667eea;
            color: white;
            border-radius: 5px;
            font-weight: bold;
            transition: 0.3s;
        }

        a:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Bienvenue sur le Mini Chat</h1>

    <a href="login.php">Connexion</a>
    <a href="register.php">Inscription</a>
</div>

</body>
</html>
