function canConnect(retour_de_requete) {
    if (retour_de_requete) {
        console.log("bon identifiant");
        moveToSearch();

    } else {
        // gestion des erreurs
        console.log("mauvais identifiant");
        document.getElementById("errors").style="display: block";
        document.getElementById("errors").innerHTML = "Mauvais identifiants!";
        // uniquement montré pendant 5 secondes
        $('#errors').show();
        setTimeout(() =>
        {
        $('#errors').hide();
        }, 5000);
    }
}

// redirige vers la page explorer.html
function moveToSearch() {
    let url = window.location.href.replace(/authentification.html.*/i, 'explorer.html');
    window.location.href = url;
}

$("#form").on('submit', (event) => { // lorsque l'utilisateur souhaite se connecter = le form est submit
    email = document.getElementById("mail").value;
    password = document.getElementById("pass").value;
    ajaxRequest('GET', `../php/requeteAuthentification.php/register?email=${email}&password=${password}`, canConnect);
    return false; // use to not reload the page when the form is submit
});