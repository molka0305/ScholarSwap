<?php
require_once "config.php";
require_login();

$user = current_user();

$stmt = $pdo->prepare("
    SELECT 
        q.id,
        q.title,
        q.level,
        q.duration,
        q.questions_count,
        MAX(qs.score) AS best_score,
        MAX(qs.total) AS total_score
    FROM quizzes q
    LEFT JOIN quiz_scores qs 
        ON qs.quiz_id = q.id AND qs.user_id = ?
    GROUP BY q.id, q.title, q.level, q.duration, q.questions_count
    ORDER BY q.id ASC
");

$stmt->execute([$user["id"]]);
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Quiz - ScholarSwap</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/quiz.css">
</head>
<body>

<header class="main-header">
    <div class="header-left">
        <a href="index.php">
            <img src="assets/colored-logo2.png" alt="Logo ScholarSwap" class="nav-logo">
        </a>
    </div>

    <div class="search-container">
        <input type="text" placeholder="Rechercher un quiz...">
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
            <li class="active" onclick="window.location.href='quiz.php'"><span class="icon">📝</span> Mes Quiz</li>
            <li onclick="window.location.href='groupes.php'"><span class="icon">👥</span> Groupes d'étude</li>
        </ul>
    </nav>

    <main class="feed">
        <div class="feed-header">
            <h2>Mes Quiz & Entraînements</h2>
        </div>

        <div class="quiz-grid">
            <?php foreach ($quizzes as $quiz): ?>
                <?php
                    // 1. Définition de la classe CSS selon la difficulté
                    $levelClass = "easy";
                    if ($quiz["level"] === "Intermédiaire") {
                        $levelClass = "medium";
                    } elseif ($quiz["level"] === "Difficile") {
                        $levelClass = "hard";
                    }

                    // 2. Redirection et texte dynamiques pour chaque type de quiz
                    if ($quiz["level"] === "Facile") {
                        $quizUrl = "refaire-quiz.html";
                        $btnText = "Refaire";
                    } elseif ($quiz["level"] === "Intermédiaire") {
                        $quizUrl = "continuer-quiz.html"; // Assurez-vous d'avoir renommé ce fichier avec un tiret (-)
                        $btnText = "Continuer";
                    } else { // Difficile
                        $quizUrl = "commencer-quiz.html";
                        $btnText = "Commencer";
                    }
                ?>

                <div class="quiz-card">
                    <div class="quiz-badge <?= e($levelClass) ?>">
                        <?= e($quiz["level"]) ?>
                    </div>

                    <h3><?= e($quiz["title"]) ?></h3>

                    <div class="quiz-info">
                        <span>⏱️ <?= e($quiz["duration"]) ?> min</span>
                        <span>❓ <?= e($quiz["questions_count"]) ?> Questions</span>
                    </div>

                    <div class="last-score">
                        <?php if ($quiz["best_score"] !== null): ?>
                            Dernier score : 
                            <strong><?= e($quiz["best_score"]) ?>/<?= e($quiz["total_score"]) ?></strong>
                        <?php else: ?>
                            Pas encore tenté
                        <?php endif; ?>
                    </div>

                    <!-- Le bouton utilise maintenant les variables PHP dynamiques $quizUrl et $btnText -->
                    <button class="start-btn primary" onclick="window.location.href='<?= e($quizUrl) ?>'">
                        <?= e($btnText) ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

</body>
</html>