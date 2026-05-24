<?php
require_once "config.php";
require_login();

$user = current_user();

$stmt = $pdo->query("
    SELECT 
        s.*,
        COUNT(DISTINCT r.id) AS resource_count,
        COUNT(DISTINCT q.id) AS quiz_count
    FROM subjects s
    LEFT JOIN resources r ON r.subject_id = s.id
    LEFT JOIN quizzes q ON q.subject_id = s.id
    GROUP BY s.id
    ORDER BY s.id ASC
");

$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Matières - ScholarSwap</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/matieres.css">
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
        <input type="text" placeholder="Rechercher une matière...">
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
            <li onclick="window.location.href='index.php'">
                <span class="icon">🏠</span> Accueil
            </li>
            <li class="active" onclick="window.location.href='matieres.php'">
                <span class="icon">📚</span> Mes Matières
            </li>
            <li onclick="window.location.href='quiz.php'">
                <span class="icon">📝</span> Mes Quiz
            </li>
            <li onclick="window.location.href='groupes.php'">
                <span class="icon">👥</span> Groupes d'étude
            </li>
        </ul>
    </nav>

    <main class="feed">
        <div class="feed-header">
            <h2>Mes Matières</h2>
        </div>

        <div class="subjects-grid">
            <?php foreach ($subjects as $subject): ?>
                <div class="subject-card">
                    <div class="subject-icon">
                        <?= e($subject["icon"]) ?>
                    </div>

                    <h3><?= e($subject["name"]) ?></h3>
                    <p><?= e($subject["description"]) ?></p>

                    <div class="subject-stats">
                        <div class="stat-item">
                            <strong><?= e($subject["resource_count"]) ?></strong>
                            <span>Ressources</span>
                        </div>

                        <div class="stat-item">
                            <strong><?= e($subject["quiz_count"]) ?></strong>
                            <span>Quiz</span>
                        </div>
                    </div>

                    <button class="view-btn" onclick="window.location.href='details-matiere.php?id=<?= e($subject["id"]) ?>'">
                        Ouvrir le cours
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

</body>
</html>