function validerFormulaire() {
    // Récupérer les valeurs des champs
    var nomPrenom = document.forms["firstform"]["nom_et_prenom"].value;
    var email = document.forms["firstform"]["email"].value;
    var mdp = document.forms["firstform"]["mdp"].value;
    var verifMdp = document.forms["firstform"]["verifmdp"].value;
    var adresse = document.forms["firstform"]["adresse"].value;

    // Vérifier si les champs sont vides
    if (nomPrenom == "" || email == "" || mdp == "" || verifMdp == "" || adresse == "") {
        alert("Veuillez remplir tous les champs");
        return false;
    }

    // Vérifier si les mots de passe correspondent
    if (mdp !== verifMdp) {
        alert("Les mots de passe ne correspondent pas");
        return false;
    }

    // Vérifier le format de l'adresse e-mail
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.match(emailRegex)) {
        alert("Veuillez saisir une adresse e-mail valide");
        return false;
    }

    // Validation réussie
    return true;
}
function validerFormulairedeux() {
    // Récupérer les valeurs des champs
    var email = document.forms["secondform"]["email"].value;
    var mdp = document.forms["secondform"]["mdp"].value;

    // Vérifier si les champs sont vides
    if (email == "" || mdp == "") {
        alert("Veuillez remplir tous les champs");
        return false;
    }

    // Vérifier le format de l'adresse e-mail
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.match(emailRegex)) {
        alert("Veuillez saisir une adresse e-mail valide");
        return false;
    }

    // Validation réussie
    return true;
}
