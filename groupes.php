<?php
require_once "config.php";
require_login();

$user = current_user();

$stmt = $pdo->prepare("
    SELECT 
        g.*,
        (
            SELECT COUNT(*) 
            FROM group_members gm 
            WHERE gm.group_id = g.id AND gm.status = 'accepted'
        ) AS real_members,
        (
            SELECT gm.status
            FROM group_members gm
            WHERE gm.group_id = g.id AND gm.user_id = ?
            LIMIT 1
        ) AS my_status
    FROM study_groups g
    ORDER BY g.created_at DESC
");

$stmt->execute([$user["id"]]);
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Groupes d'étude - ScholarSwap</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/groupes.css">
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
        <div class="feed-header">
            <h2>Communautés d'étude</h2>
            <button class="create-group-btn" onclick="window.location.href='creer-groupe.php'">
                + Créer un groupe
            </button>
        </div>

        <div class="groups-grid">
            <?php foreach ($groups as $group): ?>
                <?php
                    $totalMembers = (int)$group["members_count"] + (int)$group["real_members"];
                ?>

                <div class="group-card">
                    <div class="group-header">
                        <span class="category-tag"><?= e($group["category"]) ?></span>
                        <h3><?= e($group["name"]) ?></h3>
                    </div>

                    <p><?= e($group["description"]) ?></p>

                    <div class="group-meta">
                        <span>👥 <?= e($totalMembers) ?> membres</span>
                        <span class="online-status">🟢 <?= e($group["online_count"]) ?> en ligne</span>
                    </div>

                    <?php if ($group["my_status"] === "accepted"): ?>
                        <button class="join-btn joined">
                            Déjà membre
                        </button>
                    <?php elseif ($group["my_status"] === "pending"): ?>
                        <button class="join-btn joined">
                            Demande envoyée
                        </button>
                    <?php else: ?>
                        <button class="join-btn" onclick="window.location.href='rejoindre-groupe.php?id=<?= e($group["id"]) ?>'">
                            Rejoindre le groupe
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

</body>
</html>