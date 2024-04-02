<style>
    body {
        background-color: black;
    }
</style>
<?php
require_once('header.php');
require_once 'db.php';

if (isset($_SESSION['utilisateur_id'])) {
    $user_id = $_SESSION['utilisateur_id'];

    // Requête SQL à exécuter en filtrant par l'ID de l'utilisateur connecté
    $sql = "SELECT ID_PRODUIT, Nom, Prix, Couleur, date_commande FROM commandes_passees WHERE utilisateur_id = :user_id";

    // Préparation de la requête
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Récupération des résultats
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier s'il y a des résultats
    if (count($results) > 0) {
        // Code de la boucle pour générer les images et afficher les produits
        foreach ($results as $see) {
            $str = "<img src='imagesboutique/" . $see["ID_PRODUIT"] . ".jpeg' width='90' />";

            // Affichage du produit
            echo '<div class="produit" onclick="window.location=\'product_detail.php?id=' . $see["ID_PRODUIT"] . '\'">';
            echo $str;
            echo '<p class="nom">' . htmlspecialchars($see['Nom'], ENT_QUOTES, 'UTF-8') . '</p>';
            echo '<p class="prix">' . htmlspecialchars($see['Prix'], ENT_QUOTES, 'UTF-8') . '€' . '</p>';
            echo '<p class="couleur">Couleurs : ' . htmlspecialchars($see['Couleur'], ENT_QUOTES, 'UTF-8') . '</p>' . '<br>';
            echo '<p class="couleur">Date de la commande : ' . htmlspecialchars($see['date_commande'], ENT_QUOTES, 'UTF-8') . '</p>' . '<br>';
            echo '</div>';
        }
    } else {
        // Afficher un message si l'utilisateur n'a aucune commande
        echo "Vous n'avez aucune commande.";
    }
}
?>
