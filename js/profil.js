function printInfos(retour_de_requete) {
    if(retour_de_requete["results"]["infoPlayer"]["age"] == 0){
        document.getElementById("ageProfil").innerHTML = "Votre âge actuel : pas encore choisi";
    }
    else{
        document.getElementById("ageProfil").innerHTML = "Votre âge actuel : " + retour_de_requete["results"]["infoPlayer"]["age"] + " ans";
    }    
    if(retour_de_requete["results"]["infoPlayer"]["forme_sportive"] == "null"){
        document.getElementById("formeSportiveProfil").innerHTML = "Votre forme sportive actuelle : pas encore choisie";
    }
    else{
        document.getElementById("formeSportiveProfil").innerHTML = "Votre forme sportive actuelle : " + retour_de_requete["results"]["infoPlayer"]["forme_sportive"];
    }
    document.getElementById("mdpProfil").innerHTML = "Votre mot de passe actuel : " + retour_de_requete["results"]["infoPlayer"]["mot_de_passe"];
    document.getElementById("matchsPasses").innerHTML = retour_de_requete["results"]["nb_matchs"];
    document.getElementById("villeProfil").innerHTML = "Votre ville actuelle : " +retour_de_requete["results"]["ville"]["ville"];
    document.getElementById("photoProfil").setAttribute("src", retour_de_requete["results"]["infoPlayer"]["photo"]);
}

function changeInfosPlayer(retour_de_requete){
    console.log(retour_de_requete);
}

function fonction(retour_de_requete){
    alert("Note bien relevée, merci pour votre participation");
}


// editer le profil
$("#form").on('submit', (event) => {
    age = document.getElementById("age").value;
    ville = document.getElementById("ville").value;
    forme_sportive = document.getElementById("fs").value;
    mdp = document.getElementById("mdp").value;
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
    // modifier le profil
    ajaxRequest('PUT', `../php/requeteProfil.php/modifier?age=${age}&ville=${ville}&forme_sportive=${forme_sportive}&mdp=${mdp}&photo=${photo}`, changeInfosPlayer);
    ajaxRequest('GET', `../php/requeteProfil.php`, printInfos);
    return false; // use to not reload the page when the form is submit
});

// montrer les infos du profil
ajaxRequest('GET', `../php/requeteProfil.php`, printInfos);


// noter l'application
$("#1").click(() => {
    note = document.getElementById("1").value;
    ajaxRequest('POST', `../php/requeteProfil.php/register?note=${note}`, fonction); // fonction sert à avertir l'utilisateur qu'il a bien noté lapp
    return false; // use to not reload the page when the form is submit
});

$("#2").click(() => {
    note = document.getElementById("2").value;
    ajaxRequest('POST', `../php/requeteProfil.php/register?note=${note}`, fonction);
    return false; // use to not reload the page when the form is submit
});

$("#3").click(() => {
    note = document.getElementById("3").value;
    ajaxRequest('POST', `../php/requeteProfil.php/register?note=${note}`, fonction);
    return false; // use to not reload the page when the form is submit
});