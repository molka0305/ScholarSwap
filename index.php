<?php
require_once "config.php";
require_login();

$user = current_user();

$stmt = $pdo->query("
    SELECT 
        r.*,
        COALESCE(u.full_name, r.author_name, 'Utilisateur') AS author
    FROM resources r
    LEFT JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
");

$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>ScholarSwap - Flux de Ressources</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<header class="main-header">
    <div class="header-left">
        <a href="index.php">
            <img src="assets/colored-logo2.png" alt="Logo" class="nav-logo">
        </a>
    </div>

    <div class="search-container">
        <input type="text" placeholder="Rechercher un cours, un quiz, un prof...">
    </div>

    <div class="header-right">
        <a href="ajouter-ressource.php" class="user-badge" style="text-decoration:none;">+ Publier</a>
        <a href="profil.php" class="user-badge" style="text-decoration:none;">
            <?= e($user["full_name"]) ?>
        </a>
        <a href="logout.php" class="user-badge" style="text-decoration:none;">Déconnexion</a>
    </div>
</header>

<div class="main-layout">
    <nav class="sidebar">
        <ul>
            <li class="active" onclick="window.location.href='index.php'">
                <span class="icon">🏠</span> Accueil
            </li>
            <li onclick="window.location.href='matieres.php'">
                <span class="icon">📚</span> Mes Matières
            </li>
            <li onclick="window.location.href='quiz.php'">
                <span class="icon">📝</span> Mes Quiz
            </li>
            <li onclick="window.location.href='groupes.php'">
                <span class="icon">👥</span> Groupes d'étude
            </li>
            <hr style="border: 0; border-top: 1px solid #EEE; margin: 15px 0;">
            <li onclick="window.location.href='profil.php'">
                <span class="icon">👤</span> Profil
            </li>
        </ul>
    </nav>

    <main class="feed">
        <div class="feed-header">
            <h2>Ressources récentes</h2>
        </div>

        <?php foreach ($resources as $resource): ?>
            <article class="resource-card">
                <div class="card-user">
                    <div class="avatar">
                        <?= e(initials($resource["author"])) ?>
                    </div>

                    <div class="user-info">
                        <strong><?= e($resource["author"]) ?></strong>
                        <p class="post-date">
                            <?= date("d/m/Y H:i", strtotime($resource["created_at"])) ?>
                        </p>
                    </div>
                </div>

                <div class="card-content">
                    <h3><?= e($resource["title"]) ?></h3>
                    <p><?= e($resource["description"]) ?></p>

                    <?php if (!empty($resource["file_name"])): ?>
                        <div class="file-preview">
                            <span class="file-icon">📄</span>
                            <span><?= e($resource["file_name"]) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-actions">
                    <button class="action-btn">❤️ <?= e($resource["likes_count"]) ?></button>
                    <button class="action-btn">💬 <?= e($resource["comments_count"]) ?> commentaires</button>

                    <?php if (!empty($resource["file_path"])): ?>
                        <a href="<?= e($resource["file_path"]) ?>" download class="action-btn primary-btn" style="text-decoration: none;">
                            ⬇️ Télécharger
                        </a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </main>
</div>
</body>
</html>