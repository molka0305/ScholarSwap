<?php
require_once "config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($full_name === "" || $email === "" || $password === "") {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $error = "Cet email existe déjà.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (full_name, email, password)
                VALUES (?, ?, ?)
            ");

            $stmt->execute([$full_name, $email, $hashedPassword]);

            $_SESSION["user_id"] = $pdo->lastInsertId();

            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - ScholarSwap</title>
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
        .auth-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .logo-container img {
            width: 150px;
            margin-bottom: 20px;
        }
        h2 {
            font-family: 'Montserrat', sans-serif;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }
        .subtitle {
            color: var(--text-gray);
            font-size: 14px;
            margin-bottom: 25px;
        }
        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
            color: var(--primary-dark);
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-sizing: border-box;
        }
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: var(--primary-dark);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
        }
        .footer-link {
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-gray);
        }
        .footer-link a {
            color: var(--primary-blue);
            font-weight: bold;
            text-decoration: none;
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
    <div class="auth-container">
        <div class="logo-container">
            <img src="assets/colored-logo1.png" alt="Logo ScholarSwap">
        </div>

        <h2>Créer un compte</h2>
        <p class="subtitle">Rejoignez la communauté ScholarSwap</p>

        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="full_name" placeholder="Molka ..." required>
            </div>

            <div class="form-group">
                <label>Email étudiant</label>
                <input type="email" name="email" placeholder="nom@etudiant.com" required>
            </div>

            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-primary">S'inscrire</button>
        </form>

        <div class="footer-link">
            Déjà un compte ? <a href="login.php">Se connecter</a>
        </div>
    </div>
</body>
</html>