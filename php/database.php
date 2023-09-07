<?php

include 'constants.php';
session_start();

function dbConnect() // fonction connexion base de données
{
    $dsn = 'pgsql:dbname=' . DB_NAME . ';host=' . DB_SERVER . ';port=' . DB_PORT;
    $user = DB_USER;
    $password = DB_PASSDWORD;

    // On vérifie la connexion

    try {
        return new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        echo 'Connexion échouée : ' . $e->getMessage();
        return false;
    }
}

function getPlayer($db, $id)    // récupérer les informations de l'utilisateur dans la page profil
{
    $requete = $db->prepare('SELECT id from participe WHERE id_player = :id');
    $requete->bindParam(':id', $id);
    $requete->execute();
    $liste = $requete->fetchAll();

    $oof = array();
    foreach ($liste as $id1) {
        $requete = $db->prepare('SELECT id, sport, prix, complet, nb_joueurs, nb_joueurs_min, nb_joueurs_max, date_debut, date_fin, id_player, id_villes, score, meilleur_joueur, adresse from match WHERE id = :id');
        $requete->bindParam(':id', $id1["id"]);
        $requete->execute();
        $infomatch = $requete->fetchAll();
        array_push($oof, $infomatch);
    }
    $retour["results"]["nb_matchs"]  = count($oof);

    $request = 'SELECT prenom, nom, age, photo, mot_de_passe, forme_sportive, id_villes FROM player WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id);
    $statement->execute();
    $result = $statement->fetch();

    $retour["results"]["infoPlayer"] = $result;

    $request = 'SELECT id_villes FROM player WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id);
    $statement->execute();
    $id_ville = $statement->fetch();

    $request = 'SELECT ville FROM villes WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id_ville[0]);
    $statement->execute();
    $result = $statement->fetch();

    $retour["results"]["ville"] = $result;
    return $retour;
}

function changeNote($db, $id, $note){
    $statement = $db->prepare('UPDATE player SET 
    note=:note
    WHERE id=:id');
    $statement->bindParam(':note', $note);
    $statement->bindParam(':id', $id);
    $statement->execute();
    return true;
}

function printInfo($db, $id)
{
    // on récupère le prenom/nom de l'utilisateur pour l'afficher
    $request = 'SELECT prenom, nom FROM player WHERE id=:id';
    $statement = $db->prepare($request);
    $statement->bindParam(':id', $id);
    $statement->execute();
    $nameInfo = $statement->fetch();

    $retour["success"]  = true;
    $retour["results"]["nameInfo"]  = $nameInfo;

    //////////////////////////


    // on récupère les matchs auxquelles l'utilisateur a joué/va jouer.
    $requete = $db->prepare('SELECT id from participe WHERE id_player = :id');
    $requete->bindParam(':id', $id);
    $requete->execute();
    $liste = $requete->fetchAll();

    $oof = array();
    foreach ($liste as $id) {
        $requete = $db->prepare('SELECT id, sport, prix, complet, nb_joueurs, nb_joueurs_min, nb_joueurs_max, date_debut, date_fin, id_player, id_villes, score, meilleur_joueur, adresse from match WHERE id = :id');
        $requete->bindParam(':id', $id["id"]);
        $requete->execute();
        $infomatch = $requete->fetchAll();
        array_push($oof, $infomatch);
    }

    $retour["results"]["nb_matchs"]  = count($oof);
    $retour["results"]["infoMatch"] = $oof;


    for ($i = 0; $i < count($oof); $i++) {
        $true = TRUE;
        if ($retour["results"]["infoMatch"][$i][0]["nb_joueurs"] == $retour["results"]["infoMatch"][$i][0]["nb_joueurs_max"]) {
            $statement = $db->prepare('UPDATE match SET 
    complet=:comp
    WHERE id=:id');
            $statement->bindParam(':comp', $true);
            $statement->bindParam(':id', $retour["results"]["infoMatch"][$i][0]["id"]);
            $statement->execute();
        }
    }

    $aaf = array();

    for ($i = 0; $i < count($oof); $i++) {
        //echo $oof[$i][0]["id_player"];
        $u = $oof[$i][0]["id_player"];
        $requete = $db->prepare('SELECT prenom,nom from player WHERE id = :id');
        $requete->bindParam(':id', $u);
        $requete->execute();
        $infoorga = $requete->fetchAll();
        array_push($aaf, $infoorga);
    }

    $retour["results"]["listorga"] = $aaf;

    // $aaf = array();
    // foreach ($liste as $id) {
    //     $requete = $db->prepare('SELECT prenom,nom from player WHERE id = :id');
    //     $requete->bindParam(':id', $infomatch[$id][0]["id_player"]);
    //     $requete->execute();
    //     $infoorga = $requete->fetchAll();
    //     array_push($aff, $infoorga);
    // }
    // $retour["results"]["infoOrga"] = $aaf;


    //////////////////////

    // on récupère toutes les villes et sports pour la recherche de match
    $requete = $db->prepare('SELECT sport from match');
    $requete->execute();
    $sport = $requete->fetchAll();
    // il peut y avoir le meme sport plusieurs fois donc on filtre ça
    $chaine = "";
    // on delimite tous les sports avec des _
    foreach ($sport as $sp) {
        $chaine .= $sp["sport"];
        $chaine .= "_";
    }
    $chaine = substr($chaine, 0, -1); // on enleve le dernier _
    // on enleve les occurences des sports et on met dans un tableau
    $sport_unique = array_unique(explode("_", $chaine));
    // comme les index de $sport_unique sont cassés (ils ne font pas 0,1,2, etc mais sont random), on le met dans un autre tableau
    $sport_final = array();
    foreach ($sport_unique as $sp) {
        array_push($sport_final, $sp);
    }


    $requete = $db->prepare('SELECT ville from villes');
    $requete->execute();
    $ville = $requete->fetchAll();

    $retour["results"]["allSport"]  = $sport_final;
    $retour["results"]["nb_sports"]  = count($sport_final);
    $retour["results"]["allVille"] = $ville;
    $retour["results"]["nb_villes"]  = count($ville);
    return $retour;
}


function getOldMatch($db, $id)
{
    $requete = $db->prepare('SELECT sport, date_debut, id_villes, id, date_fin, adresse from match WHERE id_player = :id');
    $requete->bindParam(':id', $id);
    $requete->execute();
    $listeMatchOrganise = $requete->fetchAll();
    $retour["infoMatch"] = $listeMatchOrganise;
    //$retour["infoMatch"]["nb_match"] = count($listeMatchOrganise);
    //echo count($listeMatchOrganise);

    for ($i = 0; $i < count($retour["infoMatch"]); $i++) {
        $requete = $db->prepare('SELECT ville from villes WHERE id = :id_ville');
        $requete->bindParam(':id_ville', $retour["infoMatch"][$i]["id_villes"]);
        $requete->execute();
        $ville = $requete->fetchAll();
        $retour["infoMatch"][$i]["ville"] = $ville;
    }

    for ($i = 0; $i < count($retour["infoMatch"]); $i++) {
        $requete = $db->prepare('SELECT id_player from participe WHERE id = :id');
        $requete->bindParam(':id', $retour["infoMatch"][$i]["id"]);
        $requete->execute();
        $id_joueur = $requete->fetchAll();
        $retour["infoMatch"][$i]["id_joueur"] = $id_joueur;
    }

    return $retour;
}

function addScoreAndBestPlayer($db, $idmatch, $score, $meilleur_joueur)
{
    $statement = $db->prepare('UPDATE match SET 
    score=:score,
    meilleur_joueur=:mj
    WHERE id=:id');
    $statement->bindParam(':score', $score);
    $statement->bindParam(':mj', $meilleur_joueur);
    $statement->bindParam(':id', $idmatch);
    $statement->execute();

    return true;
}

function printNotif($db, $id)
{
    // récuperer les matchs dont l'utilisateur est organisateur
    $requete = $db->prepare('SELECT sport, date_debut, id_villes, id from match WHERE id_player = :id');
    $requete->bindParam(':id', $id);
    $requete->execute();
    $listeMatchOrganise = $requete->fetchAll();
    $retour["infoMatch"] = $listeMatchOrganise;

    // récupérer la ville de chaque match
    for ($i = 0; $i < count($retour["infoMatch"]); $i++) {
        $requete = $db->prepare('SELECT ville from villes WHERE id = :id_ville');
        $requete->bindParam(':id_ville', $retour["infoMatch"][$i]["id_villes"]);
        $requete->execute();
        $ville = $requete->fetchAll();
        $retour["infoMatch"][$i]["ville"] = $ville;
    }

    // récupérer les joueurs qui veulent s'inscire au match
    for ($i = 0; $i < count($retour["infoMatch"]); $i++) {
        $requete = $db->prepare('SELECT id_player from accepte_demande WHERE id = :id AND accepter=1');
        $requete->bindParam(':id', $retour["infoMatch"][$i]["id"]);
        $requete->execute();
        $id_joueur = $requete->fetchAll();
        $retour["infoMatch"][$i]["id_joueur"] = $id_joueur;
    }

    // récupérer le nom, prénom et forme sportive de chaque joueur
    for ($i = 0; $i < count($retour["infoMatch"]); $i++) {
        for ($j = 0; $j < count($retour["infoMatch"][$i]["id_joueur"]); $j++) {
            $requete = $db->prepare('SELECT nom, prenom, forme_sportive from player WHERE id = :id');
            $requete->bindParam(':id', $retour["infoMatch"][$i]["id_joueur"][$j]["id_player"]);
            $requete->execute();
            $info_joueur = $requete->fetchAll();
            $retour["infoMatch"][$i]["id_joueur"][$j]["info_player"] = $info_joueur;
        }
    }

    $requete = $db->prepare('SELECT accepter, id from accepte_demande WHERE id_player = :id');
    //$ok=3;
    $requete->bindParam(':id', $id);
    $requete->execute();
    $mesNotif = $requete->fetchAll();
    $retour["mesNotif"]["notif"] = $mesNotif;
    $retour["mesNotif"]["nb_notif"] = count($mesNotif);
    for ($i = 0; $i < count($mesNotif); $i++) {
        $requete = $db->prepare('SELECT sport, date_debut from match WHERE id = :id');
        $requete->bindParam(':id', $retour["mesNotif"]["notif"][$i]["id"]);
        $requete->execute();
        $match = $requete->fetchAll();
        $retour["mesNotif"][$i]["match"] = $match;
    }




    $retour["nb_matchs"] = count($retour["infoMatch"]);
    return $retour;
}


function getName($db, $mail, $password) // affichage du prenom/nom de l'utilisation + création d'une session 
{
    // $request = 'SELECT mot_de_passe FROM player WHERE mail = :mail';
    // $statement = $db->prepare($request);
    // $statement->bindParam(':mail', $mail);
    // $statement->execute();
    // $mdp_bdd = $statement->fetch();

    // if(password_verify($password, $mdp_bdd[0])){
    $request = 'SELECT prenom, id FROM player WHERE mail=:m AND mot_de_passe=:mdp';

    $statement = $db->prepare($request);
    $statement->bindParam(':m', $mail);
    $statement->bindParam(':mdp', $password);
    $statement->execute();
    $result = $statement->fetch();
    $row = $statement->rowCount();
    if ($row == 1) {
        $_SESSION['id'] = $result['id'];
        return true;
    } else {
        return false;
    }
    // }
    // else{
    //     return false;
    // }


}



function createUser($db, $email, $mdp, $mdp2, $prenom, $nom, $ville, $photo) // créer un utilisateur dans la page inscription
{
    if ($mdp == $mdp2) { // vérifier si les mots de passe sont identiques

        // regarder si l'email existe deja dans la bdd
        $statement = $db->prepare('SELECT mail from player');
        $statement->execute();
        $mails_bdd = $statement->fetchAll();
        foreach ($mails_bdd as $mail_bdd) {
            if ($mail_bdd["mail"] == $email) {
                return false;
            }
        }

        $newVille = true;
        $statement = $db->prepare('SELECT ville from villes');
        $statement->execute();
        $liste = $statement->fetchAll();
        foreach ($liste as $ville_liste) {      // regarde si la ville existe dans la base
            if ($ville_liste[0] == $ville) {
                $newVille = false;
                break 1;
            }
        }
        if ($newVille) {    // si la ville n'existe pas, on l'a crée + récupère son id
            $statement = $db->prepare('INSERT INTO villes(ville) VALUES (:ville)');
            $statement->bindParam(':ville', $ville);
            $statement->execute();

            $statement = $db->prepare('SELECT id from villes WHERE ville = :ville');
            $statement->bindParam(':ville', $ville);
            $statement->execute();

            $idVille = $statement->fetch();
        } else {            // récupère son id
            $statement = $db->prepare('SELECT id from villes WHERE ville=:vi');
            $statement->bindParam(':vi', $ville);
            $statement->execute();

            $idVille = $statement->fetch();
        }

        // insérer un utilisateur avec les informations qu'il a saisies

        // $mdp = password_hash($mdp, PASSWORD_BCRYPT);
        $statement = $db->prepare("INSERT INTO player(prenom,nom,age,photo, mot_de_passe, forme_sportive, mail, id_villes) VALUES (:prenom,:nom,0,:photo,:mdp,'null',:mail, :idv)");

        $statement->bindParam(':nom', $nom);
        $statement->bindParam(':prenom', $prenom);
        $statement->bindParam(':idv', $idVille[0]);
        $statement->bindParam(':photo', $photo);
        $statement->bindParam(':mail', $email);
        $statement->bindParam(':mdp', $mdp);

        $statement->execute();
        return true;
    } else {
        return false;
    }
}

function createMatch($db, $id, $sport, $min, $max, $adresse, $heure, $duree, $prix, $ville) // créer un match dans la page "organiser"
{

    $newVille = true;
    $statement = $db->prepare('SELECT ville from villes');
    $statement->execute();
    $liste = $statement->fetchAll();
    foreach ($liste as $ville_liste) {      // vérifier l'existence de la ville saisie par l'utilisateur
        if ($ville_liste[0] == $ville) {
            $newVille = false;
            break 1;
        }
    }

    if ($newVille) {        // créer la ville + récuperer son id
        $statement = $db->prepare('INSERT INTO villes(ville) VALUES (:ville)');
        $statement->bindParam(':ville', $ville);
        $statement->execute();

        $statement = $db->prepare('SELECT id from villes WHERE ville = :ville');
        $statement->bindParam(':ville', $ville);
        $statement->execute();

        $idVille = $statement->fetch();
    } else {                // récuperer son id
        $statement = $db->prepare('SELECT id from villes WHERE ville=:vi');
        $statement->bindParam(':vi', $ville);
        $statement->execute();

        $idVille = $statement->fetch();
    }

    // transformer certains string en int
    $intmin = intval($min);
    $intmax = intval($max);
    $intprix = intval($prix);

    // transformer les dates en timestamp
    $timestampdebut = strtotime($heure);
    $timestampduree = strtotime($duree);
    $timestampaddition = $timestampdebut + $timestampduree;
    // récuperer la date de fin de match
    $date = date('Y-m-d H:i:s', $timestampaddition);


    // insérer le match dans la base de données
    $statement = $db->prepare('INSERT INTO match(sport,nb_joueurs,nb_joueurs_min,nb_joueurs_max,date_debut,date_fin, adresse, prix, id_villes, id_player, complet) 
    VALUES (:sport,0,:mi,:ma,:datedebut, :datefin,:adresse,:prix,:ville, :org, false)');


    $statement->bindParam(':org', $id);
    $statement->bindParam(":sport", $sport);
    $statement->bindParam(':mi', $intmin);
    $statement->bindParam(':ma', $intmax);
    $statement->bindParam(':adresse', $adresse);
    $statement->bindParam(':datedebut', $heure);
    $statement->bindParam(':datefin', $date);
    $statement->bindParam(':prix', $intprix);
    $statement->bindParam(':ville', $idVille["id"]);

    $statement->execute();
    // $retour["id"]  = $id;
    // $retour["sport"]  = $sport;
    // $retour["max"]  = $max;
    // $retour["min"] = $min;
    // $retour["adresse"]  = $adresse;
    // //$retour["heure"]  = $heure;
    // $retour["prix"]  = $prix;
    // $retour["ville"]  = $idVille["id"];
    // //$retour["min"] = $min;
    return true;
}

function changePlayer($db, $id, $age, $ville, $mdp, $forme_sportive, $photo)
{
    $newVille = true;
    $statement = $db->prepare('SELECT ville from villes');
    $statement->execute();
    $liste = $statement->fetchAll();
    foreach ($liste as $ville_liste) {      // vérifier l'existence de la ville saisie par l'utilisateur
        if ($ville_liste[0] == $ville) {
            $newVille = false;
            break 1;
        }
    }

    if ($newVille) {        // créer la ville + récuperer son id
        $statement = $db->prepare('INSERT INTO villes(ville) VALUES (:ville)');
        $statement->bindParam(':ville', $ville);
        $statement->execute();

        $statement = $db->prepare('SELECT id from villes WHERE ville = :ville');
        $statement->bindParam(':ville', $ville);
        $statement->execute();

        $idVille = $statement->fetch();
    } else {                // récuperer son id
        $statement = $db->prepare('SELECT id from villes WHERE ville=:vi');
        $statement->bindParam(':vi', $ville);
        $statement->execute();

        $idVille = $statement->fetch();
    }
    //$mdp = password_hash($mdp, PASSWORD_BCRYPT);
    $statement = $db->prepare('UPDATE player SET 
    mot_de_passe=:mdp,
    age=:age,
    photo=:photo,
    forme_sportive=:fs,
    id_villes=:idv
    WHERE id=:id');
    $statement->bindParam(':mdp', $mdp);
    $statement->bindParam(':age', $age);
    $statement->bindParam(':photo', $photo);
    $statement->bindParam(':fs', $forme_sportive);
    $statement->bindParam(':id', $id);
    $statement->bindParam(':idv', $idVille["id"]);
    $statement->execute();

    //$idVille = $statement->fetch();
    return true;
}
function inscriptionMatch($db, $idmatch, $id)
{
    $statement = $db->prepare('INSERT INTO accepte_demande(id, id_player, accepter) VALUES (:id_match, :id_player, 1)');
    $statement->bindParam(':id_match', $idmatch);
    $statement->bindParam(':id_player', $id);
    $statement->execute();
    return true;
}
function accepterNotif($db, $idmatch, $idplayer)
{
    $statement = $db->prepare('INSERT INTO participe(id, id_player) VALUES (:id_match, :id_player)');
    $statement->bindParam(':id_match', $idmatch);
    $statement->bindParam(':id_player', $idplayer);
    $statement->execute();

    $statement = $db->prepare('UPDATE accepte_demande SET 
    accepter=2
    WHERE id=:idmatch AND id_player=:idplayer
    ');
    $statement->bindParam(':idmatch', $idmatch);
    $statement->bindParam(':idplayer', $idplayer);
    $statement->execute();
    // $statement = $db->prepare('DELETE FROM accepte_demande WHERE id=:idmatch AND id_player=:idplayer');
    // $statement->bindParam(':idmatch', $idmatch);
    // $statement->bindParam(':idplayer', $idplayer);
    // $statement->execute();

    return true;
}
function refuserNotif($db, $idmatch, $idplayer)
{
    $statement = $db->prepare('UPDATE accepte_demande SET 
    accepter=3
    WHERE id=:idmatch AND id_player=:idplayer
    ');
    $statement->bindParam(':idmatch', $idmatch);
    $statement->bindParam(':idplayer', $idplayer);
    $statement->execute();
    return true;
}

function supprimerNotif($db, $idmatch, $id)
{
    //$o = 3;
    $statement = $db->prepare('DELETE FROM accepte_demande WHERE id=:idmatch AND id_player=:idplayer');
    $statement->bindParam(':idmatch', $idmatch);
    $statement->bindParam(':idplayer', $id);
    $statement->execute();
    return true;
}

function searchAMatch($db, $ville, $sport, $time, $comp)
{
    // on récupère l'id de la ville rentrée en paramètre
    $request = 'SELECT id FROM villes WHERE ville=:ville';
    $statement = $db->prepare($request);
    $statement->bindParam(':ville', $ville);
    $statement->execute();
    $id_ville = $statement->fetch();

    // on récupère toutes les infos du match concernant la ville et le sport passés en paramètres
    $request = 'SELECT id, sport, nb_joueurs, nb_joueurs_min, nb_joueurs_max, date_debut, date_fin, adresse, prix, complet, meilleur_joueur, score FROM match WHERE id_villes=:id_ville AND sport=:sport';
    $statement = $db->prepare($request);
    $statement->bindParam(':id_ville', $id_ville[0]);
    $statement->bindParam(':sport', $sport);
    $statement->execute();
    $matchs_filtres_selon_sport_ville = $statement->fetchAll();

    // si l'utilisateur a filtré selon si le match est complet ou pas
    $matchs_filtres_selon_sport_ville_comp = array();
    if ($comp != 'undefined') {
        if ($comp == 'oui') {
            $complet = true;
        }
        if ($comp == 'non') {
            $complet = false;
        }
        foreach ($matchs_filtres_selon_sport_ville as $match) {
            if ($match["complet"] == $complet) {
                array_push($matchs_filtres_selon_sport_ville_comp, $match);
            }
        }
    } else {
        $matchs_filtres_selon_sport_ville_comp = $matchs_filtres_selon_sport_ville;
    }
    $actualTime = time();
    // si l'utilisateur a filtré selon une période
    if ($time != 'undefined') {
        $matchs_filtres_selon_sport_ville_comp_time = array();
        // on traduit la période en secondes
        $secondes = $time * 24 * 3600;
        foreach ($matchs_filtres_selon_sport_ville_comp as $match) {
            // on traduit la date du debut en timestamp
            $timestamp = strtotime($match["date_debut"]);
            if ($actualTime + $secondes > $timestamp) {
                array_push($matchs_filtres_selon_sport_ville_comp_time, $match);
            }
        }
    } else {
        $matchs_filtres_selon_sport_ville_comp_time = $matchs_filtres_selon_sport_ville_comp;
    }


    $retour["matchs"] = $matchs_filtres_selon_sport_ville_comp_time;
    $retour["nb_matchs"] = count($matchs_filtres_selon_sport_ville_comp_time);
    $retour["ville"] = $ville;
    return $retour;
}
function printMatch($db, $id, $idmatch)
{
    $requete = $db->prepare('SELECT sport, adresse, prix, complet, date_debut, date_fin, id_villes, id, id_player, nb_joueurs, nb_joueurs_min from match WHERE id = :id');
    $requete->bindParam(':id', $idmatch);
    $requete->execute();
    $listeMatchOrganise = $requete->fetch();
    $retour["infoMatch"] = $listeMatchOrganise;

    //for($i=0; $i<count())



    //echo $listeMatchOrganise["id_player"];
    $requete = $db->prepare('SELECT nom,prenom from player WHERE id = :id');
    $requete->bindParam(':id', $retour["infoMatch"]["id_player"]);
    $requete->execute();
    $infoOrga = $requete->fetch();
    $retour["infoMatch"]["infoOrganisateur"] = $infoOrga;

    // récupérer la ville de chaque match
    $requete = $db->prepare('SELECT ville from villes WHERE id = :id_ville');
    $requete->bindParam(':id_ville', $retour["infoMatch"]["id_villes"]);
    $requete->execute();
    $ville = $requete->fetch();
    $retour["infoMatch"]["ville"] = $ville;

    // voir si l'on participe deja au match
    $requete = $db->prepare('SELECT id from participe WHERE id_player = :idplayer');
    $requete->bindParam(':idplayer', $retour["infoMatch"]["id_player"]);
    $requete->execute();
    $table_participe = $requete->fetchAll();
    $retour["infoMatch"]["participe"] = false;
    foreach ($table_participe as $participe) {
        if ($participe["id"] == $idmatch) {
            $retour["infoMatch"]["participe"] = true;
            break;
        }
    }

    // voir si l'on est en attente
    // $requete = $db->prepare('SELECT accepter from accepte_demande WHERE id = :id AND id_player = :idplayer');
    // $requete->bindParam(':idplayer', $id);
    // $requete->bindParam(':id', $idmatch);
    // $requete->execute();
    // $table_accepte_demande = $requete->fetch();
    // if($table_accepte_demande["accepter"] == FALSE){
    //     $retour["infoMatch"]["accepte_demande"] = false;
    // }
    // else{
    //     if($table_accepte_demande["accepter"] == TRUE){
    //         $retour["infoMatch"]["accepte_demande"] = true;
    //     }
    // }




    // récupérer les joueurs qui sont inscrits au match
    $requete = $db->prepare('SELECT id_player from participe WHERE id = :id');
    $requete->bindParam(':id', $idmatch);
    $requete->execute();
    $id_joueur = $requete->fetchAll();
    $retour["infoMatch"]["id_joueur"] = $id_joueur;

    //récupérer le nom, prénom et forme sportive de chaque joueur
    $tab = array();
    foreach ($retour["infoMatch"]["id_joueur"] as $joueur) {
        $requete = $db->prepare('SELECT nom, prenom, forme_sportive,age,photo from player WHERE id = :id');
        $requete->bindParam(':id', $joueur["id_player"]);
        $requete->execute();
        $info_joueur = $requete->fetchAll();
        array_push($tab, $info_joueur);
    }
    $retour["infoMatch"]["id_joueur"]["info_player"] = $tab;
    return $retour;
}
