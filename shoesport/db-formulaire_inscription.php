<?php
require_once('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['nom_et_prenom'], $_POST['email'], $_POST['mdp'], $_POST['adresse'])) {
        $nom_et_prenom = $_POST['nom_et_prenom'];
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];
        $adresse = $_POST['adresse'];

        // Vérifier si l'e-mail existe déjà dans la base de données
        $sql_check_email = "SELECT email FROM utilisateurs WHERE email = :email";
        $stmt_check_email = $pdo->prepare($sql_check_email);
        $stmt_check_email->bindParam(':email', $email);
        $stmt_check_email->execute();
        $existing_email = $stmt_check_email->fetchColumn();

        if ($existing_email) {
            // Rediriger vers la page de connexion/inscription avec un message d'erreur
            header("Location: form_connexion_inscription.php?error=existing_email");
            exit();
        } else {
            // Hacher le mot de passe
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

            try {
                // Requête SQL pour insérer les données dans la table 'utilisateurs'
                $sql = "INSERT INTO utilisateurs (nom_et_prenom, email, mot_de_passe, adresse) VALUES (:nom_et_prenom, :email, :mdp, :adresse)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nom_et_prenom', $nom_et_prenom);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':mdp', $mdp_hash);
                $stmt->bindParam(':adresse', $adresse);
                $stmt->execute();

                // Redirection après l'inscription réussie
                header("Location: acceuil.php");
                exit();
            } catch (PDOException $e) {
                echo "Erreur d'insertion : " . $e->getMessage();
            }
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}

// Fermeture de la connexion à la base de données
$conn = null;
?>
