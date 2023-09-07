function toTimestamp(strDate) {
    var datum = Date.parse(strDate);
    return datum / 1000;
}
function displayMesMatchsAndFillSelect(retour_de_requete) {
    // print name
    console.log("bon identifiant");
    let name = document.createElement("h3");
    name.innerHTML = "Bonjour " + retour_de_requete["results"]["nameInfo"]["prenom"] + " " + retour_de_requete["results"]["nameInfo"]["nom"];
    name.style.color = "#4d797f";
    name.style.fontWeight = "bold";
    document.getElementById("areaName").append(name);
    // print matchs passés et maths à venir
    let areaInsertNextMatchs = document.getElementById("insert-next-matchs");
    let areaInsertPreviousMatchs = document.getElementById("insert-previous-matchs");

    // si il y a aucun matchs
    if (retour_de_requete['results']['nb_matchs'] == 0) {
        let textAreaNextMatchs = document.createElement("p");
        textAreaNextMatchs.innerHTML = "Pas de matchs à venir";
        areaInsertNextMatchs.append(textAreaNextMatchs);
        let textAreaPreviousMatchs = document.createElement("p");
        textAreaPreviousMatchs.innerHTML = "Pas de matchs passés";
        areaInsertPreviousMatchs.append(textAreaPreviousMatchs);
    }

    for (var i = 0; i < retour_de_requete['results']['nb_matchs']; i++) {
        var $comp;
        if (retour_de_requete['results']['infoMatch'][i][0]['complet'] == true) {
            $comp = "oui";
        }
        else {
            $comp = "non";
        }

        var actualTime = Date.now();
        actualTime = (actualTime - (actualTime % 1000)) / 1000;
        var matchTime = toTimestamp(retour_de_requete['results']['infoMatch'][i][0]['date_debut']);



        if (actualTime > matchTime) {
            console.log('old match');


            let card = document.createElement("div");
            card.classList.add('card', 'text-center');
            card.style.width = '18rem';
            let cardBody = document.createElement("div");
            cardBody.classList.add('card-body');
            let cardFooter = document.createElement("div");
            cardFooter.classList.add('card-footer');



            let informations = document.createElement('p');
            informations.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
            informations.textContent =
                'Sport : ' + retour_de_requete['results']['infoMatch'][i][0]['sport'] + '\r\nAdresse : ' + retour_de_requete['results']['infoMatch'][i][0]['adresse']
                + '\r\nPrix: ' + retour_de_requete['results']['infoMatch'][i][0]['prix'] + ' euros'
                + '\r\nComplet : ' + $comp
                + '\r\nDate/heure de début du match : ' + retour_de_requete['results']['infoMatch'][i][0]['date_debut']
                + '\r\n Organisateur : ' + retour_de_requete['results']['listorga'][i][0]['prenom'] + ' ' + retour_de_requete['results']['listorga'][i][0]['nom'];
            informations.classList.add('card-text');
            informations.style.color = "#FCF8E8";

            let scoreAndBestPlayer = document.createElement('p');
            scoreAndBestPlayer.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
            if(retour_de_requete['results']['infoMatch'][i][0]['score']!=null && retour_de_requete['results']['infoMatch'][i][0]['meilleur_joueur']!=null){
                console.log("oui");
                scoreAndBestPlayer.textContent = ' Score : ' + retour_de_requete['results']['infoMatch'][i][0]['score']
                + '\r\nMeilleur joueur : ' + retour_de_requete['results']['infoMatch'][i][0]['meilleur_joueur'];
                scoreAndBestPlayer.classList.add('card-text');
            }


            cardBody.append(informations);

            cardFooter.append(scoreAndBestPlayer);

            card.append(cardBody);
            card.append(cardFooter);
            areaInsertPreviousMatchs.append(card);
        }

        if (actualTime < matchTime) {
            console.log('next match');

            let card = document.createElement("div");
            card.classList.add('card', 'text-center');
            card.style.width = '18rem';
            let cardBody = document.createElement("div");
            cardBody.classList.add('card-body');
            let cardFooter = document.createElement("div");
            cardFooter.classList.add('card-footer');
            let informations = document.createElement('p');
            informations.setAttribute('style', 'white-space: pre-line;');
            informations.textContent =

                'Sport : ' + retour_de_requete['results']['infoMatch'][i][0]['sport'] + '\r\nAdresse : ' + retour_de_requete['results']['infoMatch'][i][0]['adresse']
                + '\r\nPrix: ' + retour_de_requete['results']['infoMatch'][i][0]['prix'] + ' euros'
                + '\r\nComplet : ' + $comp
                + '\r\nDate/heure de début du match : ' + retour_de_requete['results']['infoMatch'][i][0]['date_debut']
                + '\r\nNombre de joueurs actuels : ' + retour_de_requete['results']['infoMatch'][i][0]['nb_joueurs'];
            informations.classList.add("card-text");
            informations.style.color = "#FCF8E8";

            let organisateur = document.createElement("p");
            organisateur.textContent = 'Organisateur : ' + retour_de_requete['results']['listorga'][i][0]['prenom'] + ' ' + retour_de_requete['results']['listorga'][i][0]['nom'];
            organisateur.classList.add('card-text');

            cardBody.append(informations);
            cardFooter.append(organisateur);

            card.append(cardBody);
            card.append(cardFooter);
            areaInsertNextMatchs.append(card);
        }
    }
    // remplissage des select pour rechercher un match (villes et sports)

    let areaVille = document.getElementById("ville");
    console.log(retour_de_requete['results']['nb_villes']);
    for (var i = 0; i < retour_de_requete['results']['nb_villes']; i++) {
        let option = document.createElement("option");
        option.innerHTML = retour_de_requete['results']['allVille'][i]['ville'];
        option.value = retour_de_requete['results']['allVille'][i]['ville'];
        areaVille.appendChild(option);
    }

    let areaSport = document.getElementById("sport");
    for (var i = 0; i < retour_de_requete['results']['nb_sports']; i++) {
        let option = document.createElement("option");
        option.innerHTML = retour_de_requete['results']['allSport'][i];
        option.value = retour_de_requete['results']['allSport'][i];
        areaSport.appendChild(option);
    }
}

// function moveToSearch() {
//     let url = window.location.href.replace(/authentification.html.*/i, 'explorer.html');
//     window.location.href = url;
// }

function searchMatch(retour_de_requete) {
    // on supprime les matchs d'avant, comme ça on affiche uniquement ceux de la requête
    document.getElementById("insert").remove();

    let div = document.createElement("div");
    div.setAttribute("id", "insert");
    let areaInsertMatchs = document.getElementById("insert-matchs-request");
    areaInsertMatchs.append(div);
    for (var i = 0; i < retour_de_requete['nb_matchs']; i++) {
        var $comp;
        if (retour_de_requete['matchs'][i]['complet'] == true) {
            $comp = "oui";
        }
        else {
            $comp = "non";
        }
        let card = document.createElement("div");
        card.classList.add('card', 'text-center');
        card.style.width = '18rem';
        let cardBody = document.createElement("div");
        cardBody.classList.add('card-body');
        let cardFooter = document.createElement("div");
        cardFooter.classList.add('card-footer');

        let informations = document.createElement('p');
        informations.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
        informations.textContent =
            'Sport : ' + retour_de_requete['matchs'][i]['sport']
            + '\r\nVille : ' + retour_de_requete["ville"]
            + '\r\n Nombre de joueurs maximum : ' + retour_de_requete['matchs'][i]['nb_joueurs_max']
            + '\r\nDate/heure de début du match : ' + retour_de_requete['matchs'][i]['date_debut']
            + '\r\nComplet : ' + $comp;
        informations.classList.add('card-text');
        informations.style.color = "#FCF8E8";

        let details = document.createElement('button');
        details.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
        details.setAttribute("name", "details");
        details.textContent = 'Détails';
        details.classList.add('card-text');
        details.setAttribute("id", retour_de_requete['matchs'][i]["id"]);
        details.setAttribute("data-bs-toggle", "modal");
        details.setAttribute("data-bs-target", "#modal2");





        cardBody.append(informations);

        cardFooter.append(details);

        card.append(cardBody);
        card.append(cardFooter);
        div.append(card);


        let all_btn = document.getElementsByName("details");
        all_btn.forEach(function (btn) {
            btn.addEventListener("click", function () {
                //console.log(this.innerHTML + " is clicked");
                //console.log(this.id);
                idmatch = this.id;
                //score = document.getElementById("score_" + this.id).value;
                //meilleur_joueur = document.getElementById("meilleur_joueur_" + this.id).value
                ajaxRequest('POST', `../php/requeteExplorer.php/details?idmatch=${idmatch}`, printDetailsMatch);
                //console.log(document.getElementById("meilleur_joueur_"+this.id).value)
            });
        });
    }

}

// Quand l'utilisateur recherche un match
$("#form").on('submit', (event) => {
    sport = document.getElementById("sport").value;
    ville = document.getElementById("ville").value;
    // filtre : complet ou non
    oui = document.getElementById("oui"); // complet = oui
    non = document.getElementById("non"); // complet = non
    time1 = document.getElementById("time1"); // periode = 7 jours
    time2 = document.getElementById("time2"); // periode = 15 jours
    time3 = document.getElementById("time3"); // periode = 30 jours
    let time; // va stocker la periode selectionnee : soit 7, soit 15, soit 30
    let complet; // va stocker oui ou non
    if (time1.checked == true) {
        time = time1.value;
    }
    else {
        if (time2.checked) {
            time = time2.value;
        }
        else {
            if (time3.checked) {
                time = time3.value;
            }
        }
    }
    if (oui.checked) {
        complet = oui.value;
    }
    else {
        if (non.checked) {
            complet = non.value;
        }
    }

    ajaxRequest('GET', `../php/requeteExplorer.php/recherche?sport=${sport}&ville=${ville}&complet=${complet}&time=${time}`, searchMatch);
    return false; // use to not reload the page when the form is submit
});

ajaxRequest('GET', `../php/requeteExplorer.php/mesmatchs`, displayMesMatchsAndFillSelect);


// afficher les notifications
$("#notif").click(() => {
    ajaxRequest('GET', `../php/requeteExplorer.php/mesnotifs`, printNotif);
    return false; // use to not reload the page when the form is submit
});

function printNotif(notif) {

    let hr = document.createElement("hr");

    div = document.getElementById("notification");
    div1 = document.getElementById("notification1");
    div1.innerHTML = "Voici les réponses des organisateurs pour vos participations";

    let texte1 = document.createElement("div");
    texte1.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
    texte1.innerHTML = "\r\n";
    if(notif["mesNotif"]["nb_notif"]==0){
        texte1.innerHTML += "Vous n'avez pas encore de réponses<br>";
        div1.append(texte1);
    }
    else{
        for(var i=0; i<notif["mesNotif"]["nb_notif"]; i++){
            if(notif["mesNotif"]["notif"][i]["accepter"]==2){
                texte1.innerHTML += "L'organisateur vous a accepté pour le match de " + notif["mesNotif"][i]["match"][0]["sport"]+" du "+notif["mesNotif"][i]["match"][0]["date_debut"];
                
                div1.append(texte1);
                let btnAccept = document.createElement("button");
                btnAccept.setAttribute("type", "button");
                btnAccept.setAttribute("id", notif["mesNotif"]["notif"][i]["id"]);
                btnAccept.setAttribute("class", "btn");
                btnAccept.innerHTML = "Supprimer notification";
    
                text2 = "\n\r";
                texte1.append(btnAccept);
                texte1.append(hr);
                div1.append(text2);
            }
            if(notif["mesNotif"]["notif"][i]["accepter"]==3){
                texte1.innerHTML += "L'organisateur vous a refusé pour le match de " + notif["mesNotif"][i]["match"][0]["sport"]+" du "+notif["mesNotif"][i]["match"][0]["date_debut"];
                texte1.append(hr);
                let btnAccept = document.createElement("button");
                btnAccept.setAttribute("type", "button");
                btnAccept.setAttribute("id", notif["mesNotif"]["notif"][i]["id"]);
                btnAccept.setAttribute("class", "btn");
                btnAccept.innerHTML = "Supprimer notification";
    
                text2 = "\n\r";
                texte1.append(btnAccept);
                texte1.append(hr);
                div1.append(text2);
            }
        }
    }

    div.innerHTML = "Voici les matchs que vous organisez avec les demandes d'inscription des joueurs";

    let texte = document.createElement("div");
    texte.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
    texte.innerHTML = "\r\n";
    for (var i = 0; i < notif["nb_matchs"]; i++) {
        texte.innerHTML += "Sport : " + notif["infoMatch"][i]["sport"]
            + "\r\n Date : " + notif["infoMatch"][i]["date_debut"]
            + "\r\n Ville : " + notif["infoMatch"][i]["ville"][0]["ville"];
        texte.append(hr);
        texte.innerHTML += "Joueurs voulant s'inscrire : (" + notif["infoMatch"][i]["id_joueur"].length + ")";
        texte.append(hr);
        if (notif["infoMatch"][i]["id_joueur"].length == 0) {
            texte.innerHTML += "Aucun joueur ne souhaite s'inscrire pour le moment";
        }
        for (var j = 0; j < notif["infoMatch"][i]["id_joueur"].length; j++) {
            texte.innerHTML += "Nom : " + notif["infoMatch"][i]["id_joueur"][j]["info_player"][0]["nom"]
                + "\r\n Prénom : " + notif["infoMatch"][i]["id_joueur"][j]["info_player"][0]["prenom"]
                + "\r\n Forme sportive : " + notif["infoMatch"][i]["id_joueur"][j]["info_player"][0]["forme_sportive"];


            let btnAccept = document.createElement("button");
            btnAccept.setAttribute("type", "button");
            btnAccept.setAttribute("id", notif["infoMatch"][i]["id"] + "_" + notif["infoMatch"][i]["id_joueur"][j]["id_player"] + "_A");
            btnAccept.setAttribute("class", "btn btn-primary");
            btnAccept.innerHTML = "Accepter";
            btnAccept.style.color = "#fff";
            btnAccept.style.backgroundColor = "#DF7861";
            btnAccept.style.borderColor = "#DF7861";

            let btnRefuse = document.createElement("button");
            btnRefuse.setAttribute("type", "button");
            btnRefuse.setAttribute("id", notif["infoMatch"][i]["id"] + "_" + notif["infoMatch"][i]["id_joueur"][j]["id_player"] + "_R");
            btnRefuse.setAttribute("class", "btn btn-danger");
            btnRefuse.innerHTML = "Refuser";
            btnRefuse.style.color = "#fff";
            btnRefuse.style.backgroundColor = "#DF7861";
            btnRefuse.style.borderColor = "#DF7861";

            let btns = document.createElement("div");
            btns.setAttribute("id", "id_match=" + notif["infoMatch"][i]["id"] + "&id_joueur=" + notif["infoMatch"][i]["id_joueur"][j]["id_player"]);
            btns.append(btnAccept);
            btns.append(btnRefuse);
            texte.append(btns);
        }
        texte.append(hr);
    }
    texte.style.color = "#DF7861";
    texte.style.fontWeight = "bold";

    div.append(texte);

    let all_btn = document.querySelectorAll("button");
    all_btn.forEach(function (btn) {
        btn.addEventListener("click", function () {
            //console.log(this.innerHTML + " is clicked");
            tab = this.id.split("_");
            console.log(this.id);
            idmatch = tab[0];
            idplayer = tab[1];
            action = tab[2];
            
            if (action == "A") {
                ajaxRequest('POST', `../php/requeteExplorer.php/valider?idmatch=${idmatch}&idplayer=${idplayer}`, valide); // callback pas utilisee
                ajaxRequest('GET', `../php/requeteExplorer.php/mesnotifs`, printNotif);
            }
            else {
                if (action == "R") {
                    ajaxRequest('DELETE', `../php/requeteExplorer.php/refuser?idmatch=${idmatch}&idplayer=${idplayer}`, valide); // callback pas utilisee
                    ajaxRequest('GET', `../php/requeteExplorer.php/mesnotifs`, printNotif);
                }
                else{
                    ajaxRequest('DELETE', `../php/requeteExplorer.php/delete?idmatch=${this.id}`, valide);
                    ajaxRequest('GET', `../php/requeteExplorer.php/mesnotifs`, printNotif);
                }
            }
            //console.log(document.getElementById("meilleur_joueur_"+this.id).value)
        });
    });
}
function printDetailsMatch(result) {

    let hr = document.createElement("hr");

    div = document.getElementById("details");
    div.innerHTML = "Voici les détails du match";

    let texte = document.createElement("div");
    texte.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
    texte.innerHTML = "\r\n";
    texte.innerHTML += "Sport : " + result["infoMatch"]["sport"]
        + "\r\n Date début : " + result["infoMatch"]["date_debut"]
        + "\r\n Date fin : " + result["infoMatch"]["date_fin"]
        + "\r\n Ville : " + result["infoMatch"]["ville"]["ville"]
        + "\r\n Adresse : " + result["infoMatch"]["adresse"]
        + "\r\n Organisateur : " + result["infoMatch"]["infoOrganisateur"]["prenom"] + " " + result["infoMatch"]["infoOrganisateur"]["nom"]
        + "\r\n Prix : " + result["infoMatch"]["prix"] + " euros";
    texte.append(hr);
    texte.innerHTML += " Joueurs inscrits : " + "(" + result["infoMatch"]["id_joueur"]["info_player"].length + ")";
    texte.append(hr);
    for (var i = 0; i < result["infoMatch"]["id_joueur"]["info_player"].length; i++) {
        let img = document.createElement("img");
        img.setAttribute("src", result["infoMatch"]["id_joueur"]["info_player"][i][0]["photo"]);
        img.setAttribute("alt", "photo_joueur");
        img.setAttribute("class", "img-fluid");
        img.setAttribute("width", "50px");
        img.setAttribute("height", "50px");
        texte.innerHTML += "Nom : " + result["infoMatch"]["id_joueur"]["info_player"][i][0]["nom"]
            + "\r\n Prénom : " + result["infoMatch"]["id_joueur"]["info_player"][i][0]["prenom"]
            + "\r\n Photo : ";
        texte.append(img);
        texte.innerHTML += "\r\n Age : " + result["infoMatch"]["id_joueur"]["info_player"][i][0]["age"] + " ans"
            + "\r\n Forme sportive : " + result["infoMatch"]["id_joueur"]["info_player"][i][0]["forme_sportive"];

        texte.append(hr);
    }

    if (result["infoMatch"]["participe"] == true) {
        texte.innerHTML += "Vous êtes déjà inscrit au match";
    }
    else {
        if (result["infoMatch"]["complet"] == true) {
            texte.innerHTML += "Ce match est complet";
        } else {
            actualTime = Date.now();
            actualTime = (actualTime - (actualTime % 1000)) / 1000;
            if (toTimestamp(result["infoMatch"]["date_debut"]) < actualTime) {
                texte.innerHTML += "Ce match est déjà passé";
            }
            else {
                if (result["infoMatch"]["participe"] == false) {
                    let btnAccept = document.createElement("button");
                    btnAccept.setAttribute("type", "button");
                    btnAccept.setAttribute("id", result["infoMatch"]["id"]);
                    btnAccept.setAttribute("class", "btn btn-primary");
                    btnAccept.setAttribute("name", "inscription");
                    btnAccept.innerHTML = "S'inscrire";
                    btnAccept.style.color = "#fff";
                    btnAccept.style.backgroundColor = "#DF7861";
                    btnAccept.style.borderColor = "#DF7861";

                    let btns = document.createElement("div");
                    btns.append(btnAccept);
                    texte.append(btns);
                }
            }
        }
        // if(result["infoMatch"]["accepte_demande"] == false){
        //     texte.innerHTML += "L'organisateur examine votre demande";
        // }
        // else{
    }
    // }
    texte.style.color = "#DF7861";
    texte.style.fontWeight = "bold";


    div.append(texte);
    let all_btn = document.getElementsByName("inscription");
    all_btn.forEach(function (btn) {
        btn.addEventListener("click", function () {
            //console.log(this.innerHTML + " is clicked");
            //console.log(this.id);
            //score = document.getElementById("score_" + this.id).value;
            //meilleur_joueur = document.getElementById("meilleur_joueur_" + this.id).value
            ajaxRequest('POST', `../php/requeteExplorer.php/inscriptionMatch?idmatch=${result["infoMatch"]["id"]}`, inscription);
            //console.log(document.getElementById("meilleur_joueur_"+this.id).value)
        });
    });


    // $(idmatch).click(() => {
    //     ajaxRequest('POST', `../php/requeteExplorer.php/inscriptionMatch?idmatch=${result["infoMatch"]["id"]}`, pif);
    //     return false; // use to not reload the page when the form is submit
    // });


}
function inscription(retour) {
    alert("Demande bien prise en compte! L'organisateur va l'examiner");
}
function valide(retour) {

}