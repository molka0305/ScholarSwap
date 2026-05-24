<?php
require_once "config.php";
require_login();

$user = current_user();

$subject_id = (int)($_GET["id"] ?? 1);

$stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->execute([$subject_id]);
$subject = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$subject) {
    die("Matière introuvable.");
}

$stmt = $pdo->prepare("
    SELECT * FROM resources
    WHERE subject_id = ?
    ORDER BY created_at DESC
");

$stmt->execute([$subject_id]);
$resources = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM quizzes WHERE subject_id = ?");
$stmt->execute([$subject_id]);
$quizCount = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= e($subject["name"]) ?> - ScholarSwap</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/details-matiere.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<header class="main-header">
    <div class="header-left">
        <a href="index.php">
            <img src="assets/colored-logo2.png" alt="Logo" class="nav-logo">
        </a>
    </div>

    <div class="search-container">
        <input type="text" placeholder="Rechercher un document...">
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
            <li class="active" onclick="window.location.href='matieres.php'"><span class="icon">📚</span> Mes Matières</li>
            <li onclick="window.location.href='quiz.php'"><span class="icon">📝</span> Mes Quiz</li>
            <li onclick="window.location.href='groupes.php'"><span class="icon">👥</span> Groupes</li>
        </ul>
    </nav>

    <main class="feed">
        <div class="breadcrumb">
            <a href="matieres.php">Mes Matières</a> / <strong><?= e($subject["name"]) ?></strong>
        </div>

        <section class="course-header-card">
            <div class="subject-tag"><?= e($subject["semester"]) ?></div>
            <h1><?= e($subject["name"]) ?></h1>
            <p><?= e($subject["description"]) ?></p>

            <div class="course-quick-stats">
                <span><strong><?= count($resources) ?></strong> Supports</span>
                <span><strong><?= e($quizCount) ?></strong> Quiz</span>
            </div>
        </section>

        <div style="margin-bottom: 20px;">
            <a href="ajouter-ressource.php?subject_id=<?= e($subject_id) ?>" class="download-btn-blue" style="text-decoration:none;">
                + Ajouter une ressource
            </a>
        </div>

        <div class="resources-list">
            <h3 class="section-title">📚 Documents de cours</h3>

            <?php foreach ($resources as $resource): ?>
                <div class="file-card-item">
                    <div class="file-icon-box">
                        <?= e($resource["file_type"] ?: "DOC") ?>
                    </div>

                    <div class="file-info">
                        <h4><?= e($resource["title"]) ?></h4>
                        <p>
                            <?= e($resource["file_size"] ?: "Fichier") ?>
                            • <?= date("d/m/Y", strtotime($resource["created_at"])) ?>
                        </p>
                    </div>

                    <?php if (!empty($resource["file_path"])): ?>
                        <a href="<?= e($resource["file_path"]) ?>" download class="download-btn-blue">
                            Télécharger
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

</body>
</html>