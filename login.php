<?php
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = $user["id"];

        header("Location: index.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - ScholarSwap</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #0A1E4A;
            --primary-blue: #5BCAF4;
            --bg-light: #f8f9fa;
            --border-color: #e0e0e0;
            --text-gray: #6B7280;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--bg-light);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-logo {
            height: 80px;
            margin-bottom: 20px;
        }
        h2 {
            font-family: 'Montserrat', sans-serif;
            color: var(--primary-dark);
            margin-bottom: 10px;
            font-size: 22px;
        }
        p.subtitle {
            color: var(--text-gray);
            font-size: 14px;
            margin-bottom: 30px;
        }
        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-dark);
            font-size: 14px;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .login-btn {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-dark);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .login-footer {
            margin-top: 25px;
            font-size: 13px;
        }
        .login-footer a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
        }
        .login-footer span {
            display: block;
            margin-top: 10px;
            color: var(--text-gray);
        }
        .error {
            background: #ffe5e5;
            color: red;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="assets/colored-logo1.png" alt="Logo ScholarSwap" class="login-logo">

        <h2>Bienvenue sur ScholarSwap</h2>
        <p class="subtitle">Connectez-vous pour accéder à vos ressources</p>

        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label>Email étudiant</label>
                <input type="email" name="email" placeholder="nom@etudiant.com" required>
            </div>

            <div class="input-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="login-btn">Se connecter</button>
        </form>

        <div class="login-footer">
            <a href="forgot_password.html">Mot de passe oublié ?</a>
            <span>Pas encore de compte ? <a href="register.php">S'inscrire</a></span>
        </div>
    </div>
</body>
</html>