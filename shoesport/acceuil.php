<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil</title>

  <style>
    body {
      margin: 0;
      padding: 0;
      overflow: hidden;
      background-color: black; /* Ajout du style de fond noir pour le body */
    }

    video {
      position: fixed;
      top: 50%;
      left: 50%;
      min-width: 100%;
      min-height: 100%;
      width: auto;
      height: auto;
      transform: translateX(-50%) translateY(-50%);
      z-index: -1;
    }

    #content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translateX(-50%) translateY(-50%);
      z-index: 1;
      text-align: center;
      color: white;
    }
  </style>

</head>
<body>

  <?php require_once ('header.php')?>
  <?php require_once ('db.php')?>

  <video autoplay loop muted playsinline>
    <source src="Videodechaussure.mp4" type="video/mp4">
    Votre navigateur ne prend pas en charge la balise vidéo.
  </video>

  <div id="content">
    <h1>Bienvenue sur Shoesport</h1>
    <p>Découvrez notre contenu passionnant.</p>
  </div>

</body>
</html>







