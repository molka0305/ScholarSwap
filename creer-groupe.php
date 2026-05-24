<?php
require_once "config.php";
require_login();

$user = current_user();
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($name === "" || $description === "") {
        $error = "Le nom et la description sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO study_groups (name, category, description, creator_id)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([$name, $category, $description, $user["id"]]);

        $groupId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("
            INSERT INTO group_members (user_id, group_id, status)
            VALUES (?, ?, 'accepted')
        ");

        $stmt->execute([$user["id"], $groupId]);

        header("Location: groupes.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un groupe - ScholarSwap</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/groupes.css">
</head>
<body>

<header class="main-header">
    <div class="header-left">
        <a href="index.php">
            <img src="assets/colored-logo2.png" alt="Logo ScholarSwap" class="nav-logo">
        </a>
    </div>

    <div class="search-container">
        <input type="text" placeholder="Créer un groupe...">
    </div>

    <div class="header-right">
        <a href="profil.php" class="user-badge" style="text-decoration:none;">
            <?= e($user["full_name"]) ?>
        </a>
    </div>
</header>

<div class="main-layout">
    <nav class="sidebar">
        <ul>
            <li onclick="window.location.href='index.php'"><span class="icon">🏠</span> Accueil</li>
            <li onclick="window.location.href='matieres.php'"><span class="icon">📚</span> Mes Matières</li>
            <li onclick="window.location.href='quiz.php'"><span class="icon">📝</span> Mes Quiz</li>
            <li class="active" onclick="window.location.href='groupes.php'"><span class="icon">👥</span> Groupes d'étude</li>
        </ul>
    </nav>

    <main class="feed">
        <div class="group-card" style="max-width: 600px; margin: auto;">
            <h2 style="margin-bottom: 20px;">Créer un nouveau groupe</h2>

            <?php if ($error): ?>
                <p style="color:red;"><?= e($error) ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Nom de la communauté</label>
                    <input type="text" name="name" required placeholder="Ex: Masterclass PHP">
                </div>

                <div class="form-group">
                    <label>Catégorie</label>
                    <input type="text" name="category" placeholder="Ex: Code, Design, Maths">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required rows="4" placeholder="Décrivez l'objectif du groupe..."></textarea>
                </div>

                <button type="submit" class="create-group-btn" style="width: 100%;">
                    Publier le groupe
                </button>
            </form>

            <button class="join-btn" style="margin-top: 10px; border: none; background: #eee; color: #333; width: 100%;" onclick="window.location.href='groupes.php'">
                Annuler
            </button>
        </div>
    </main>
</div>

</body>
</html>