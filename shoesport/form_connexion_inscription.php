<?php
// Définir une variable pour stocker le message d'erreur
$error_message = "";

// Vérifier s'il y a une erreur passée via l'URL
if (isset($_GET['error'])) {
    $error_type = $_GET['error'];
    // Définir le message d'erreur en fonction du type d'erreur
    if ($error_type === 'existing_email') {
        $error_message = "L'adresse e-mail existe déjà. Veuillez choisir une autre adresse e-mail.";
    }
}

// Fermeture de la connexion à la base de données
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Page de connexion</title>
    <link rel="stylesheet" href="css/formulaire.css">
    <style>
        body 
        {
            background-color: black;
        }
    </style>
</head>
<body>
<?php 
    require_once('header.php');
    require_once('db.php'); 

    echo '<div class="message">';
    // Afficher le message d'erreur s'il y en a un
    if (!empty($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    echo '</div>';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
// Vérification de la session pour déterminer si l'utilisateur est déjà connecté
if (!isset($_SESSION['utilisateur_id'])) {
?>
<div class="container1">
    <div class="form-container sign-up-container">
        <form name="firstform" method="POST" action="db-formulaire_inscription.php" onsubmit="return validerFormulaire()" id="formulaire">
            <h1>Créer un compte</h1>
            <input type="text" name="nom_et_prenom" placeholder="Nom et Prénom" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="mdp" placeholder="Mot de passe" required>
            <input type="password" name="verifmdp" placeholder="Confirmer Mot de passe" required>
            <label for="adresse"></label>
            <textarea id="adresse" name="adresse" placeholder="Adresse" required></textarea>
            <button type="submit">M'inscrire</button>
        </form>
    </div>
    <div id="contenu">
        <div class='container2'>
            <h1>Connexion</h1>
            <form name='secondform' action="db-formulaire_connexion.php" method="post" onsubmit="return validerFormulairedeux()" id="formulairedeux">
                <label for="email"></label>
                <input type="email" id="email" name="email" placeholder="Email" required><br><br>
                <label for="mdp"></label>
                <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required><br><br>
                <input type="submit" name="connexion" id="connexion" value="Connexion">
            </form>
        </div>
    </div>
</div>

<?php 
} else {
    // Si l'utilisateur est déjà connecté, rediriger vers une autre page par exemple
    echo "<p>vous etes deja connecté</p>";
    echo "<a href='deconnexion.php'>Déconnexion</a>";
    exit;
}
?>
    <script src="js/veriform.js"></script>
</body>
</html>

