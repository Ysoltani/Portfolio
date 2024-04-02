<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('header.php'); ?>
    <?php require_once('db.php'); ?>
    <link rel="stylesheet" href="css/productdetail.css">
    <style>
        body 
        {
            background-color: black;
        }
    </style>
    <meta charset="UTF-8">
    <title>détails</title>
</head>
<body>
<?php
    try {
        //CONNEXION
        $sql = "SELECT * FROM `produit` WHERE ID_PRODUIT = :ID_PRODUIT";
        $stmt = $pdo->prepare($sql);
        // Vérifiez si ID_PRODUIT est défini dans l'URL avant de l'utiliser dans la requête SQL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_produit = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT); // Assurez-vous que l'ID est un nombre entier
            $stmt->bindParam(':ID_PRODUIT', $id_produit, PDO::PARAM_INT); // Lier le paramètre en tant qu'entier
            $stmt->execute();
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "ID du produit non défini ou invalide.";
            // Arrêter l'exécution du reste du code si l'ID du produit n'est pas défini ou s'il n'est pas un nombre valide
            exit();
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données: " . $e->getMessage();
    }
?>
<main>
    <section class="product-details">
    <div class="product-info">
    <h2><?php echo isset($produit['Nom']) ? htmlspecialchars($produit['Nom']) : ''; ?></h2>
    <?php
        if ($produit && is_array($produit)) {
            $image_produit = isset($produit['ID_PRODUIT']) ? filter_var($produit['ID_PRODUIT'], FILTER_SANITIZE_NUMBER_INT) : '';
            echo '<img class="imageproduit" src="imagesboutique/' . $image_produit . '.jpeg" alt="' . (isset($produit['Nom']) ? htmlspecialchars($produit['Nom']) : '') . '">';
            echo '<p>' . (isset($produit['Details']) ? ($produit['Details']) : '') . '</p>';
            echo '<p>' . (isset($produit['Prix']) ? htmlspecialchars($produit['Prix']) : '') . '</p>';
            echo '<p>' . (isset($produit['Couleur']) ? htmlspecialchars($produit['Couleur']) : '') . '</p>';
            
            // Ajouter le menu déroulant des tailles
            echo '<label for="taille">Taille disponible :</label>';
            echo '<select name="taille" id="taille">';
            for ($i = 38; $i <= 45; $i++) {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
            echo '</select>';
    }
    ?>
</div>
        <?php
        if (isset($_SESSION['utilisateur_id'])) {
            // L'utilisateur est connecté
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $id_produit_panier = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT); // Assurez-vous que l'ID est un nombre entier
                echo '<a href="ajoutpanier.php?id=' . $id_produit_panier . '" onclick="return confirm(\'Produit ajouté au panier\')">Ajouter au panier</a>';
            } else {
                echo 'Le produit n\'est pas défini ou l\'ID est invalide'; // Gérer le cas où $id_produit n'est pas défini ou n'est pas un nombre valide
            }
        } else {
            echo '<a href="form_connexion_inscription.php">Ajouter au panier</a>';
        }
        ?>
    </section>
</main>
</body>
</html>
