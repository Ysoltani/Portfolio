<style>
        body 
        {
            background-color: black;
        }
    </style>
<?php
require_once('db.php');
require_once('header.php');
require_once('db.php');
if (isset($_SESSION['utilisateur_id'])) {
    $utilisateur_id = $_SESSION['utilisateur_id'];

    try {

        // Requête pour récupérer les détails des produits dans le panier de l'utilisateur
        $sql = "SELECT p.ID_PRODUIT, p.Nom, p.Prix, p.Couleur, p.Details, panier.quantite
                FROM panier 
                JOIN produit p ON panier.produit_id = p.ID_PRODUIT
                WHERE panier.utilisateur_id = :utilisateur_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Affichage des produits du panier

// Affichage des produits du panier
if ($products) {
    foreach ($products as $product) {
        $str = "<img src='imagesboutique/" . $product["ID_PRODUIT"] . ".jpeg' width='90' />";
        echo $str;
        echo '<div>';
        echo '<h3>' . htmlspecialchars($product['Nom']) . '</h3>';
        echo '<p>Prix : ' . htmlspecialchars($product['Prix']) . '</p>';
        echo '<p>Couleur : ' . htmlspecialchars($product['Couleur']) . '</p>';
        echo '<p>Détails : ' . htmlspecialchars($product['Details']) . '</p>';
        echo '<p>Quantité : ' . htmlspecialchars($product['quantite']) . '</p>';
        echo '<form action="supprimer_produit.php" method="post">';
        echo '<input type="hidden" name="produit_id" value="' . htmlspecialchars($product['ID_PRODUIT']) . '">';
        echo '<input type="submit" value="Retirer du panier">';
        echo '</form>';
        echo '</div>';
    }

    // Formulaire pour valider l'ensemble du panier une fois qu'on a fini de lister les produits
    echo '<form action="valider.php" method="post">';
    echo '<input type="hidden" name="utilisateur_id" value="' . $_SESSION['utilisateur_id'] . '">';
    echo '<input type="submit" value="Valider le panier">';
    echo '</form>';
} else {
    echo 'Le panier est vide.';
}

    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
} else {
    header("Location: form_connexion_inscription.php");
}


?>
