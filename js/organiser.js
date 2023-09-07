function callback(retour_de_requete) {

}

function toTimestamp(strDate) {
    var datum = Date.parse(strDate);
    return datum / 1000;
}
// function moveToSearch() {
//     let url = window.location.href.replace(/authentification.html.*/i, 'explorer.html');
//     window.location.href = url;
// }

function changeThings(retour_de_requete){

}


function afficherAnciensMatchs(retour_de_requete) {

    let areaInsertPreviousMatchs = document.getElementById("insert-previous-matchs");


    //count(retour_de_requete['infoMatch']
    for (var i = 0; i < retour_de_requete["infoMatch"].length; i++) {
        var actualTime = Date.now();
        actualTime = (actualTime - (actualTime % 1000)) / 1000;
        var matchTime = toTimestamp(retour_de_requete['infoMatch'][i]['date_debut']);


        if (actualTime > matchTime) {
        console.log('old match');

        
    

        let card = document.createElement("div");
        card.classList.add('card', 'text-center');
        card.style.width = '18rem';
        let cardBody = document.createElement("div");
        cardBody.classList.add('card-body');
        let cardFooter = document.createElement("div");
        cardFooter.classList.add('card-footer');


        let row = document.createElement("div");
        row.setAttribute("class", "row");

        let col1 = document.createElement("div");
        col1.setAttribute("class", "col");
        let col2 = document.createElement("div");
        col2.setAttribute("class", "col");
        let col3 = document.createElement("div");
        col3.setAttribute("class", "col");

        let scoreInput = document.createElement("input");
        scoreInput.setAttribute("type", "text");
        scoreInput.setAttribute("class", "form-control");
        scoreInput.setAttribute("placeholder", "Score");
        scoreInput.setAttribute("id", "score_" + retour_de_requete["infoMatch"][i]["id"]);

        let meilleurJoueurInput = document.createElement("input");
        meilleurJoueurInput.setAttribute("type", "text");
        meilleurJoueurInput.setAttribute("class", "form-control");
        meilleurJoueurInput.setAttribute("placeholder", "Meilleur joueur");
        meilleurJoueurInput.setAttribute("id", "meilleur_joueur_" + retour_de_requete["infoMatch"][i]["id"]);

        let submit = document.createElement("button");
        submit.setAttribute("type", "button");
        submit.setAttribute("class", "submit-stats");
        submit.innerHTML = "Enregistrer";
        submit.setAttribute("id", retour_de_requete["infoMatch"][i]["id"]);

       


        col1.append(scoreInput);
        col2.append(meilleurJoueurInput);
        col3.append(submit);

        row.append(col1);
        row.append(col2);
        row.append(col3);


        cardFooter.append(row);

        let informations = document.createElement('p');
        informations.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
        informations.textContent =
            'Sport : ' + retour_de_requete['infoMatch'][i]['sport']
            + '\r\nDate/heure de début du match : ' + retour_de_requete['infoMatch'][i]['date_debut']
            + '\r\nDate/heure de fin du match : ' + retour_de_requete['infoMatch'][i]['date_fin']
            + '\r\nAdresse : ' + retour_de_requete['infoMatch'][i]['adresse'];
        informations.classList.add('card-text');
        informations.style.color = "#FCF8E8";

        // let scoreAndBestPlayer = document.createElement('p');
        // scoreAndBestPlayer.setAttribute('style', 'white-space: pre-line;'); // permet de faire les retours a la ligne
        // scoreAndBestPlayer.textContent = ' Score : ' + retour_de_requete['results']['infoMatch'][i][0]['score']
        //     + '\r\nMeilleur joueur : ' + retour_de_requete['results']['infoMatch'][i][0]['meilleur_joueur'];
        // scoreAndBestPlayer.classList.add('card-text');



        cardBody.append(informations);

        //cardFooter.append(scoreAndBestPlayer);

        card.append(cardBody);
        card.append(cardFooter);
        areaInsertPreviousMatchs.append(card);
        }
    }
    let all_btn = document.querySelectorAll("button");
    all_btn.forEach(function(btn) {
        btn.addEventListener("click", function() {
            idmatch = this.id;
            score = document.getElementById("score_"+this.id).value;
            meilleur_joueur = document.getElementById("meilleur_joueur_"+this.id).value

            ajaxRequest('PUT', `../php/requeteOrganiser.php/input?score=${score}&meilleur_joueur=${meilleur_joueur}&idmatch=${idmatch}`, callback);  // on utilise pas la callback

            //console.log(document.getElementById("meilleur_joueur_"+this.id).value)
        });
    });
}

function canConnect(retour){

}
// form pour créer un match
$("#form").on('submit', (event) => {
    sport = document.getElementById("sport").value;
    min = document.getElementById("min").value;
    max = document.getElementById("max").value;
    adresse = document.getElementById("adresse").value;
    heure = document.getElementById("heure").value;
    duree = document.getElementById("duree").value;
    prix = document.getElementById("prix").value;
    ville = document.getElementById("ville").value;
    ajaxRequest('POST', `../php/requeteOrganiser.php/create?sport=${sport}&min=${min}&max=${max}&adresse=${adresse}&heure=${heure}&duree=${duree}&prix=${prix}&ville=${ville}`, canConnect);
    return false; // use to not reload the page when the form is submit
});

// $("#test").on('submit', (event) => {
//     meilleur_joueur = document.getElementById("meilleur_joueur").value;
//     score = document.getElementById("score").value;
//     match = document.getElementById("match").value;
//     ajaxRequest('PUT', `../php/requeteOrganiser.php/register?score=${score}&meilleur_joueur=${meilleur_joueur}&match=${match}`, canConnect);
//     return false; // use to not reload the page when the form is submit
// });

ajaxRequest('POST', `../php/requeteOrganiser.php/old`, afficherAnciensMatchs);
