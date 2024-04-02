<style>
        body 
        {
            background-color: black;
        }
    </style>
<?php 
    require_once('header.php');
    require_once('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Boutique</title>
    <link rel="stylesheet" href="css/boutiques.css">
    <link rel="stylesheet" href="css/combined-styles.css">
</head>
<body>
    <div class="bloc-blanc">
        <h2>Boutique</h2>
    </div>

    <div class="produits-container">
        <?php
        $sql = "SELECT ID_PRODUIT, Nom, Prix, Couleur FROM produit";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $see) {
            $productID = $see["ID_PRODUIT"];
            $productName = htmlspecialchars($see['Nom'], ENT_QUOTES, 'UTF-8');
            $productPrice = htmlspecialchars($see['Prix'], ENT_QUOTES, 'UTF-8') . '€';
            $productColor = htmlspecialchars($see['Couleur'], ENT_QUOTES, 'UTF-8');
            $imagePath = 'imagesboutique/' . intval($productID) . '.jpeg';

            // Validation des données avant de les afficher
            if (file_exists($imagePath)) {
                echo '<div class="produit" onclick="redirectToProduct(' . $productID . ')">';
                echo '<img src="' . $imagePath . '" width="90" />';
                echo '<p class="nom">' . $productName . '</p>';
                echo '<p class="prix">' . $productPrice . '</p>';
                echo '<p class="couleur">Couleurs : ' . $productColor . '</p><br>';
                echo '</div>';
            }
        }

        // Fonction JavaScript pour rediriger vers la page du produit avec un ID sécurisé
        ?>
        <script>
            function redirectToProduct(productId) {
                var id = parseInt(productId);
                if (!isNaN(id) && id > 0) {
                    window.location = 'product_detail.php?id=' + id;
                }
            }
        </script>
    </div>
</body>
</html>
