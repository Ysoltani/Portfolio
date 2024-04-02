<head>
    <style>
        body 
        {
            background-color: black;
        }
    </style>
</head>
<?php
session_start();

require_once 'db.php'; // Inclure le fichier de connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <div class="head">
        <nav>
            <ul class="navbar">
                <li><a href="acceuil.php">Accueil</a></li>
                <li><a href="boutique.php">Boutique</a></li>
                <li><a href="messages.php">Réclamations</a></li>
                <li><a href="messagerie.php">Messagerie</a></li>
                <img src="imagesheader/logo.jpg" class="logo" alt="">
                <img class='imagepanier' src="imagesheader/panier.jpg" alt="" onclick="window.location.href = 'panier.php';">

                <?php
// Vérification de l'état de connexion de l'utilisateur
if (isset($_SESSION['utilisateur_id'])) {
    $id_utilisateur = $_SESSION['utilisateur_id'];
    echo '<button class="btn-primary" onclick="location.href=\'page_personnelle.php?id=' . $id_utilisateur . '\'">Accéder à ma page personnelle</button>';
} else {
    echo '<button class="btn-secondary" onclick="location.href=\'form_connexion_inscription.php\'">S\'identifier</button>';
}
?>

            </ul>
        </nav>
    </div>
</body>
</html>
