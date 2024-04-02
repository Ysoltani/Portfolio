<?php
require_once('db.php');
require_once('header.php');

if (isset($_SESSION['utilisateur_id'])) {
    $utilisateur_id = $_SESSION['utilisateur_id'];

    try {
        $sqlSelectPanier = "SELECT produit.ID_PRODUIT, produit.Nom, produit.Prix, produit.Couleur, produit.Details, panier.quantite
                            FROM panier
                            JOIN produit ON panier.produit_id = produit.ID_PRODUIT
                            WHERE panier.utilisateur_id = :utilisateur_id";

        $stmtSelectPanier = $pdo->prepare($sqlSelectPanier);
        $stmtSelectPanier->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
        $stmtSelectPanier->execute();
        $products = $stmtSelectPanier->fetchAll(PDO::FETCH_ASSOC);

        if ($products) {
            // Affichage des images des produits dans le panier
            foreach ($products as $product) {
                $str = "<img src='imagesboutique/" . $product["ID_PRODUIT"] . ".jpeg' width='90' />";
                echo $str;
                // Assurez-vous de fermer correctement la balise img
                echo "<img/>"; // Cette balise img est incorrecte, elle devrait être corrigée comme ceci : echo "</img>";
            }

            try {
                $sqlViderPanier = "DELETE FROM panier WHERE utilisateur_id = :utilisateur_id";
                $stmtViderPanier = $pdo->prepare($sqlViderPanier);
                $stmtViderPanier->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
                $stmtViderPanier->execute();
            
                echo 'La commande a été passée avec succès. Le panier a été vidé.';
            } catch (PDOException $e) {
                echo "Erreur lors de la suppression du panier : " . $e->getMessage();
            }

            // Récupérer les informations de la dernière commande de l'utilisateur
            $sqlDerniereCommande = "SELECT * FROM commandes_passees WHERE utilisateur_id = :utilisateur_id ORDER BY id DESC LIMIT 1";
            $stmtDerniereCommande = $pdo->prepare($sqlDerniereCommande);
            $stmtDerniereCommande->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
            $stmtDerniereCommande->execute();
            $derniereCommande = $stmtDerniereCommande->fetch(PDO::FETCH_ASSOC);

            if ($derniereCommande) {
                // Afficher les informations de la dernière commande
                print_r($derniereCommande);
            } else {
                echo 'Aucune commande trouvée pour cet utilisateur.';
            }
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
