<?php
// Assurez-vous d'avoir une connexion à votre base de données via PDO
require_once('db.php'); // Assurez-vous de configurer votre connexion PDO dans db.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    // Assurez-vous de valider et de filtrer les données avant de les utiliser
    $nouveauNom = filter_input(INPUT_POST, 'Nom', FILTER_SANITIZE_STRING);
    $nouvelEmail = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL);
    $nouvelleAdresse = filter_input(INPUT_POST, 'Adresse', FILTER_SANITIZE_STRING);
    $utilisateurId = $_POST['id']; // Récupération de l'ID de l'utilisateur depuis le formulaire

    // Requête SQL pour mettre à jour les données de l'utilisateur dans la table 'utilisateurs'
    $sql = "UPDATE utilisateurs
            SET nom_et_prenom = :nouveau_nom,
                email = :nouvel_email,
                adresse = :nouvelle_adresse
            WHERE id = :utilisateur_id";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql);

    // Liaison des paramètres et exécution de la requête
    $stmt->bindParam(':nouveau_nom', $nouveauNom, PDO::PARAM_STR);
    $stmt->bindParam(':nouvel_email', $nouvelEmail, PDO::PARAM_STR);
    $stmt->bindParam(':nouvelle_adresse', $nouvelleAdresse, PDO::PARAM_STR);
    $stmt->bindParam(':utilisateur_id', $utilisateurId, PDO::PARAM_INT);

    // Exécution de la requête
    if ($stmt->execute()) {
        // Redirection vers la même page après la mise à jour
        header('Location: acceuil.php');
        exit();
    } else {
        echo "Échec de la mise à jour de l'utilisateur.";
    }
}
?>
