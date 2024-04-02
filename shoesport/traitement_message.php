<?php
require_once('db.php'); // Inclure le fichier de connexion à la base de données

// Démarrer la session pour accéder aux variables de session
session_start();

// Vérifier si 'utilisateur_id' est défini dans la session
if (isset($_SESSION['utilisateur_id'])) {
    // Récupérer l'ID de l'utilisateur depuis la session
    $utilisateur_id = $_SESSION['utilisateur_id'];

    // Vérifier si les données du formulaire ont été envoyées
    if (isset($_POST['message'], $_POST['produit'])) {
        // Récupérer les données du formulaire
        $message = $_POST['message'];
        $produit_id = $_POST['produit']; // Récupérer l'ID du produit choisi depuis le formulaire


        // Requête SQL pour insérer le message dans la table 'messages' avec les informations de nom et prénom de l'utilisateur
        $sql = "INSERT INTO messages (messagesversvendeur, utilisateur_id, ID_PRODUIT) VALUES (:message, :utilisateur_id, :produit_id)";

        // Préparer la requête
        $stmt = $pdo->prepare($sql);

        // Liaison des paramètres
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id);
        $stmt->bindParam(':produit_id', $produit_id);

        // Exécution de la requête
        if ($stmt->execute()) {
            // Stocker le message de succès dans une variable de session
            $_SESSION['success_message'] = "Message envoyé.";
            header("Location: messages.php"); // Redirection vers la page des messages
            exit();
        } else {
            echo "Erreur : " . $sql . "<br>" . $stmt->errorInfo()[2];
        }
    } else {
        echo "Les données du formulaire ne sont pas complètes.";
    }
} else {
    echo "L'utilisateur n'est pas connecté ou l'ID de l'utilisateur n'est pas défini en session.";
}

// Fermer la connexion à la base de données 
$pdo = null;
?>
