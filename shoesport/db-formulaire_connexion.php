<?php
session_start(); // Début de la session
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['email'], $_POST['mdp'])) {
        $email = $_POST['email'];
        $mdp = $_POST['mdp'];

        // Requête SQL pour récupérer les informations de l'utilisateur basées sur l'adresse e-mail
        $sql = "SELECT id, nom_et_prenom, mot_de_passe, adresse, email FROM utilisateurs WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {
            // L'utilisateur a fourni des informations valides
            // Rediriger vers la page personnelle avec l'ID de l'utilisateur dans l'URL
            header("Location: page_personnelle.php?id=" . $user['id']);

            // Stocker les informations de l'utilisateur dans les variables de session
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['adresse'] = $user['adresse'];
            $_SESSION['nom'] = $user['nom_et_prenom'];
            exit();
            // Autres actions après une connexion réussie
        } else {
            header("Location: form_connexion_inscription.php?erreur=invalid_credentials");
            exit();            
        }
    } else {
        echo "Tous les champs sont requis.";
    }
}

exit();
?>


