<?php
require_once "config.php";
require_login();

$user = current_user();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM resources WHERE user_id = ?");
$stmt->execute([$user["id"]]);
$resourceCount = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM quiz_scores WHERE user_id = ? AND score >= total / 2");
$stmt->execute([$user["id"]]);
$quizSuccess = $stmt->fetchColumn();

$points = ($resourceCount * 50) + ($quizSuccess * 25);

$stmt = $pdo->prepare("
    SELECT * FROM resources
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 5
");

$stmt->execute([$user["id"]]);
$activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - ScholarSwap</title>
    <link rel="stylesheet" href="css/profil.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="main-header">
    <div class="header-left">
        <a href="index.php">
            <img src="assets/colored-logo2.png" alt="Logo ScholarSwap" class="nav-logo">
        </a>
    </div>

    <div class="search-container">
        <input type="text" placeholder="Rechercher sur mon profil...">
    </div>

    <div class="header-right">
        <div class="user-badge"><?= e($user["full_name"]) ?></div>
    </div>
</header>

<div class="main-layout">
    <nav class="sidebar">
        <ul>
            <li onclick="window.location.href='index.php'"><span class="icon">🏠</span> Accueil</li>
            <li onclick="window.location.href='matieres.php'"><span class="icon">📚</span> Mes Matières</li>
            <li onclick="window.location.href='quiz.php'"><span class="icon">📝</span> Mes Quiz</li>
            <li onclick="window.location.href='groupes.php'"><span class="icon">👥</span> Groupes d'étude</li>
            <hr style="border: 0; border-top: 1px solid #EEE; margin: 15px 0;">
            <li onclick="window.location.href='logout.php'"><span class="icon">🚪</span> Déconnexion</li>
        </ul>
    </nav>

    <main class="feed">
        <div class="profile-header">
            <div class="profile-info-main">
                <div class="profile-avatar-large">
                    <?= e(initials($user["full_name"])) ?>
                </div>

                <div class="profile-text">
                    <h2><?= e($user["full_name"]) ?></h2>
                    <p><?= e($user["bio"] ?: "Étudiante prepa Informatique - Première année") ?></p>
                </div>
            </div>

            <button class="edit-profile-btn" onclick="window.location.href='parametres.html'">
                Modifier le profil
            </button>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-value"><?= e($resourceCount) ?></span>
                <span class="stat-label">Ressources Partagées</span>
            </div>

            <div class="stat-card">
                <span class="stat-value"><?= e($quizSuccess) ?></span>
                <span class="stat-label">Quiz Réussis</span>
            </div>

            <div class="stat-card">
                <span class="stat-value"><?= e($points) ?></span>
                <span class="stat-label">Points d'Entraide</span>
            </div>
        </div>

        <div class="profile-content">
            <h3>Activité Récente</h3>

            <div class="activity-list">
                <?php if (count($activities) === 0): ?>
                    <p>Aucune activité récente.</p>
                <?php endif; ?>

                <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <span class="activity-icon">📄</span>

                        <div class="activity-detail">
                            <p>
                                Vous avez publié 
                                <strong>"<?= e($activity["title"]) ?>"</strong>
                            </p>
                            <span><?= date("d/m/Y H:i", strtotime($activity["created_at"])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>

</body>
</html>