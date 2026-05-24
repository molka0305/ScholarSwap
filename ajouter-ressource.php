<?php
require_once "config.php";
require_login();

$user = current_user();

$preselectedSubject = (int)($_GET["subject_id"] ?? 0);
$error = "";

$subjects = $pdo->query("SELECT * FROM subjects ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $subject_id = (int)($_POST["subject_id"] ?? 0);

    $fileName = null;
    $filePath = null;
    $fileType = null;
    $fileSize = null;

    if ($title === "" || $description === "" || $subject_id <= 0) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        if (!empty($_FILES["file"]["name"])) {
            if (!is_dir("uploads")) {
                mkdir("uploads", 0777, true);
            }

            $originalName = basename($_FILES["file"]["name"]);
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            $newName = uniqid("cours_") . "." . $extension;
            $destination = "uploads/" . $newName;

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination)) {
                $fileName = $originalName;
                $filePath = $destination;
                $fileType = strtoupper($extension);
                $fileSize = round($_FILES["file"]["size"] / 1024, 1) . " Ko";
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO resources 
            (user_id, subject_id, title, description, file_name, file_path, file_type, file_size)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $user["id"],
            $subject_id,
            $title,
            $description,
            $fileName,
            $filePath,
            $fileType,
            $fileSize
        ]);

        header("Location: details-matiere.php?id=" . $subject_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une ressource</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/groupes.css">
</head>
<body>

<header class="main-header">
    <div class="header-left">
        <a href="index.php">
            <img src="assets/colored-logo2.png" alt="Logo" class="nav-logo">
        </a>
    </div>

    <div class="search-container">
        <input type="text" placeholder="Publier une ressource...">
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
        <div class="group-card" style="max-width: 600px; margin: auto;">
            <h2>Ajouter une ressource</h2>

            <?php if ($error): ?>
                <p style="color:red; margin:10px 0;"><?= e($error) ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Matière</label>
                    <select name="subject_id" required style="padding:12px; border-radius:8px; border:1px solid #ddd;">
                        <option value="">Choisir une matière</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= e($subject["id"]) ?>" <?= $subject["id"] == $preselectedSubject ? "selected" : "" ?>>
                                <?= e($subject["name"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="title" placeholder="Ex: Cours JavaScript DOM" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="4" placeholder="Description du document..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Fichier</label>
                    <input type="file" name="file">
                </div>

                <button type="submit" class="create-group-btn" style="width:100%;">
                    Publier
                </button>
            </form>
        </div>
    </main>
</div>

</body>
</html>