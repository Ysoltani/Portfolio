<?php
require_once('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produit_id'])) {
    if (isset($_SESSION['utilisateur_id'])) {
        $utilisateur_id = $_SESSION['utilisateur_id'];
        $produit_id = $_POST['produit_id'];

        try {
            require_once('db.php');

            // Requête pour diminuer la quantité du produit dans le panier de l'utilisateur
            $sql = "UPDATE panier SET quantite = quantite - 1 WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
            $stmt->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
            $stmt->execute();

            // Vérification de la quantité du produit dans le panier
            $sqlCheckQuantity = "SELECT quantite FROM panier WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id";
            $stmtCheckQuantity = $pdo->prepare($sqlCheckQuantity);
            $stmtCheckQuantity->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
            $stmtCheckQuantity->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
            $stmtCheckQuantity->execute();
            $quantity = $stmtCheckQuantity->fetchColumn();

            if ($quantity <= 0) {
                // Suppression du produit si la quantité atteint 0 ou devient négative
                $sqlDeleteProduct = "DELETE FROM panier WHERE utilisateur_id = :utilisateur_id AND produit_id = :produit_id";
                $stmtDeleteProduct = $pdo->prepare($sqlDeleteProduct);
                $stmtDeleteProduct->bindParam(':utilisateur_id', $utilisateur_id, PDO::PARAM_INT);
                $stmtDeleteProduct->bindParam(':produit_id', $produit_id, PDO::PARAM_INT);
                $stmtDeleteProduct->execute();
            }

            // Redirection vers la page du panier après la modification
            header("Location: panier.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
        }
    } else {
        echo "L'utilisateur n'est pas connecté.";
    }
} else {
    echo "Erreur : aucun produit spécifié pour le retrait.";
}
?>
