<?php
require_once "config.php";
require_login();

$user = current_user();

$groupId = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM study_groups WHERE id = ?");
$stmt->execute([$groupId]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    die("Groupe introuvable.");
}

$stmt = $pdo->prepare("
    SELECT id FROM group_members 
    WHERE user_id = ? AND group_id = ?
");

$stmt->execute([$user["id"], $groupId]);
$alreadyExists = $stmt->fetch();

if (!$alreadyExists) {
    $stmt = $pdo->prepare("
        INSERT INTO group_members (user_id, group_id, status)
        VALUES (?, ?, 'pending')
    ");

    $stmt->execute([$user["id"], $groupId]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande envoyée - ScholarSwap</title>
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
        <input type="text" placeholder="Rechercher un groupe...">
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
        <div class="group-card" style="max-width: 500px; margin: auto; text-align: center;">
            <h2>Demande envoyée !</h2>

            <p style="margin: 20px 0;">
                Vous avez envoyé une demande pour rejoindre 
                <strong><?= e($group["name"]) ?></strong>.
            </p>

            <button class="create-group-btn" onclick="window.location.href='groupes.php'">
                Retour aux groupes
            </button>
        </div>
    </main>
</div>

</body>
</html>