function canCreateUser(retour_de_requete) {
    if (retour_de_requete) {
        //console.log("Vous vous etes bien inscrit");
        moveToSearch();

    } else {
        // gestion des erreurs
        console.log("pas les memes mdp");
        document.getElementById("errors").style="display: block";
        document.getElementById("errors").innerHTML = "Les mots de passe ne sont pas les mêmes ou un compte existe déjà avec cette adresse mail";
        // uniquement montré pendant 5 secondes
        $('#errors').show();
        setTimeout(() =>
        {
        $('#errors').hide();
        }, 5000);
    }
}

function moveToSearch() {
    let url = window.location.href.replace(/inscription.html.*/i, 'authentification.html');
    window.location.href = url;
}

$("#form").on('submit', (event) => {

    email = document.getElementById("email").value;
    password = document.getElementById("mdp").value;
    password2 = document.getElementById("mdp2").value;
    prenom = document.getElementById("prenom").value;
    nom = document.getElementById("nom").value;
    ville = document.getElementById("ville").value;
    boy = document.getElementById("boy");
    girl = document.getElementById("girl");

    // photo va contenir l'avatar choisi lors de l'inscription, sinon ce sera boy.png par defaut
    let photo;
    if (boy.checked == true) {
        photo = "../resources/boy.png";
    }
    else {
        if (girl.checked) {
            photo = "../resources/girl.png";
        }
        else{
            photo = "../resources/boy.png";
        }
    }

    ajaxRequest('GET', `../php/requeteInscription.php/register?email=${email}&password=${password}&password2=${password2}&prenom=${prenom}&nom=${nom}&ville=${ville}&photo=${photo}`, canCreateUser);
    return false; // use to not reload the page when the form is submit
});