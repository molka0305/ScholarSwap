ScholarSwap - Réseau Social Éducatif

# Description
ScholarSwap est une plateforme web dynamique développée dans le cadre du module de Technologie Web (Semestre 2). Elle permet aux étudiants et enseignants de partager des ressources pédagogiques, de rejoindre des groupes d'étude et de tester leurs connaissances via des quiz.

#Fonctionnalités
- **Authentification sécurisée** (Inscription/Connexion).
- **Partage de fichiers** (PDF, PPTX, ZIP) par matière.
- **Flux d'activités** en temps réel sur la page d'accueil.
- **Système de Quiz** avec calcul de score automatique.
- **Groupes d'entraide** thématiques.

# Installation et Configuration

Pour faire fonctionner ce projet localement, suivez ces étapes :

# 1. Prérequis
- Avoir installé **XAMPP**, **WAMP** ou **MAMP**.
- Navigateur web moderne (Chrome, Firefox, Edge).

# 2. Base de données
1. Ouvrez **phpMyAdmin**.
2. Créez une nouvelle base de données nommée `scholarswap`.
3. Cliquez sur l'onglet **Importer** et sélectionnez le fichier `database.sql` situé à la racine de ce projet.

# 3. Configuration PHP
1. Placez le dossier du projet dans votre répertoire serveur (`htdocs` pour XAMPP ou `www` pour WAMP).
2. Ouvrez le fichier `php/db.php` (ou `db.php`) et vérifiez les identifiants de connexion :
   ```php
   $host = 'localhost';
   $dbname = 'scholarswap';
   $user = 'root';
   $pass = ''; // Modifiez si vous avez un mot de passe MySQL
