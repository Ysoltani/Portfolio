<?php
require_once('header.php'); 
require_once('db.php');

if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
    // Afficher le message de succès
    echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';

    // Une fois affiché, effacer la variable de session pour éviter l'affichage répété du message
    unset($_SESSION['success_message']);
}

// Vérification de l'état de connexion de l'utilisateur
if (!isset($_SESSION['utilisateur_id'])) {
    // Rediriger vers la page de connexion/inscription si l'utilisateur n'est pas connecté
    header("Location: form_connexion_inscription.php");
    exit(); // Arrêter l'exécution du script après la redirection
}

// Requête SQL pour récupérer les messages et l'état du ticket pour l'utilisateur
$sqlMessages = "SELECT messagesversvendeur, messageversclient
                FROM messages 
                WHERE utilisateur_id = :utilisateur_id";

// Préparation et exécution de la requête pour les messages
$stmtMessages = $pdo->prepare($sqlMessages);
$stmtMessages->bindParam(':utilisateur_id', $_SESSION['utilisateur_id']);
$stmtMessages->execute();

// Récupération des résultats des messages
$resultsMessages = $stmtMessages->fetchAll(PDO::FETCH_ASSOC);

// Requête SQL pour récupérer les produits
$sqlProduits = "SELECT ID_PRODUIT, Nom, Prix, Couleur FROM produit";

// Préparation et exécution de la requête pour les produits
$stmtProduits = $pdo->prepare($sqlProduits);
$stmtProduits->execute();

// Récupération des résultats des produits
$resultsProduits = $stmtProduits->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <style>
        body 
        {
            background-color: black;
        }
    </style>
    <link rel="stylesheet" href="css/reclamation.css">
</head>
<body>

    <h1>OBTENIR DE L'AIDE ?</h1>

    <!-- Formulaire pour écrire et envoyer un message au vendeur -->
    <form action="traitement_message.php" method="POST">

        <?php
        echo '<label for="produit">Avec quel produit rencontrez-vous un problème ?</label><br>';
        echo '<select id="produit" name="produit" required>';
        echo '<option value="">Sélectionnez le produit</option>';

        // Afficher les options du menu déroulant en parcourant les résultats des produits
        foreach ($resultsProduits as $row) {
            echo '<option value="' . $row['ID_PRODUIT'] . '">' . $row['Nom'] . '</option>';
        }
        echo '</select><br><br>';
        ?>

        <label for="message">Votre message :</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>

        <!-- Champ caché pour l'identifiant du vendeur -->

        <input type="submit" value="Envoyer">
    </form>
</body>
</html>