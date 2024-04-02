    <?php 
$id = $_GET['id'];

require_once ('header.php');
require_once ('db.php');
?>

    <meta charset="UTF_8">
    <link rel="stylesheet" href="css/modif.css">
    <style>
        body 
        {
            background-color: black;
        }
    </style>

    <div>
        <form action="update.php" method="POST" onsubmit="return validatemodif();">
            <label for="Nom">Nom :</label>
            <input type="text" name="Nom" id="Nom" value="" />

            <label for="Email">Email :</label>
            <input type="text" name="Email" id="Email" value="" />

            <label for="Adresse">Adresse :</label>
            <input type="text" name="Adresse" id="Adresse" value="" />

            <input type="hidden" name="id" value="<?php echo $id; ?>" />

            <input type="submit" name="modifier" value="Modifier" />
        </form>

    </div>

    <script>
            function validatemodif() {
        var nom = document.getElementById('Nom').value;
        var email = document.getElementById('Email').value;
        var adresse = document.getElementById('Adresse').value;

        if (nom.trim() === '' || email.trim() === '' || adresse.trim() === '') {
            alert('Veuillez remplir tous les champs.');
            return false; // Prevents form submission
        }

        // You can add additional validation logic here if needed

        return true; // Allows form submission if all fields are filled
    }

    </script>
