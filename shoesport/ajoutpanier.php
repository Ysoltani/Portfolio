<?php
require_once('db.php');
session_start();

if (isset($_GET['id'])) {
    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['utilisateur_id'])) {
        $produit_id = $_GET['id'];
        $utilisateur_id = $_SESSION['utilisateur_id'];

        try {
            require_once('db.php');

            // Vérifier si le produit est déjà dans le panier de l'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM panier WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id");
            $stmt->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
            $stmt->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
            $stmt->execute();
            $existingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingProduct) {
                // Si le produit est déjà dans le panier, augmenter la quantité
                $updateQuantity = $pdo->prepare("UPDATE panier SET quantite = quantite + 1 WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id");
                $updateQuantity->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
                $updateQuantity->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
                $updateQuantity->execute();
            } else {
                // Si le produit n'est pas dans le panier, l'ajouter avec une quantité de 1
                $insertProduct = $pdo->prepare("INSERT INTO panier (utilisateur_id, produit_id, quantite) VALUES (:utilisateur_id, :produit_id, 1)");
                $insertProduct->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
                $insertProduct->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
                $insertProduct->execute();
            }

            // Redirection vers la page de la boutique
            header("Location: boutique.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    } else {
        echo "L'utilisateur n'est pas connecté. Veuillez vous connecter pour ajouter des produits au panier.";
    }
} else {
    echo "L'ID du produit n'est pas défini.";
}
?>

