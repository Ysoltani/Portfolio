<?php 
require_once('header.php');
require_once('db.php');?>
<head>
    <link rel="stylesheet" href="css/pagepersonnelle.css">
    <style>
        body 
        {
            background-color: black;
        }
    </style>
</head>
<?php
// Vérification de l'ID de l'utilisateur dans l'URL
if (isset($_GET['id'])) {
    $id_utilisateur = $_GET['id'];

    // Assurez-vous de valider ou filtrer l'ID de l'utilisateur ici avant utilisation pour des raisons de sécurité

    // Requête pour récupérer les informations de l'utilisateur
    $sql = "SELECT * FROM utilisateurs WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_utilisateur);
    $stmt->execute();

    // Vérification du nombre de lignes retournées
    if ($stmt->rowCount() == 1) {
        // L'utilisateur existe, affichage de ses informations
        $info_utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<div class='elements'>";
        echo "<p>Nom : " . $info_utilisateur['nom_et_prenom'] . "</p>";
        echo "<p>Email : " . $info_utilisateur['email'] . "</p>";
        echo "<p>Adresse : " . $info_utilisateur['adresse'] . "</p>";
        echo '<br>';
        echo '<a href="modif.php?id=' . $id_utilisateur . '">Modifier mes informations</a>';
        echo "</div>";
        ?>
        
        <html>
        <form action="commandes.php">
    <input type="submit" name="mes_commandes" value="Mes commandes">
</form>
    </html>

    <?php
        $_SESSION['utilisateur_id'] = $id_utilisateur;
    } else {
        // L'utilisateur n'existe pas ou l'ID est invalide
        echo "Utilisateur introuvable.";
        // Ajout d'un message d'erreur pour obtenir plus d'informations sur la raison pour laquelle l'utilisateur n'a pas été trouvé
        $errorInfo = $stmt->errorInfo();
        echo "<p>Erreur: " . $errorInfo[2] . "</p>";
    }
} else {
    // ID d'utilisateur manquant dans l'URL
    echo "ID d'utilisateur manquant dans l'URL.";
}
// Fermeture de la connexion à la base de données après avoir terminé toutes les opérations nécessitant la base de données
$pdo = null;
?>
<a href="deconnexion.php">Déconnexion</a>

