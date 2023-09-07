<?php
require_once('database.php');

// Enable all warnings and errors.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connexion.
$db = dbConnect();

// Check the request.
$requestMethod = $_SERVER['REQUEST_METHOD'];
$request = substr($_SERVER['PATH_INFO'], 1);
$request = explode('/', $request);
$requestRessource = array_shift($request);

if ($requestMethod == "POST") {
    if($requestRessource == "valider"){
        $idmatch = $_GET["idmatch"];
        $idplayer = $_GET["idplayer"];
        $printInfo = accepterNotif($db, $idmatch, $idplayer);
    }
    else{
        if($requestRessource == "details"){
            $id = $_SESSION['id'];
            $idmatch = $_GET['idmatch'];
            $printInfo = printMatch($db, $id, $idmatch);
        }
        else{
            if($requestRessource == "inscriptionMatch"){
                $id = $_SESSION['id'];
                $idmatch = $_GET['idmatch'];
                $printInfo = inscriptionMatch($db, $idmatch, $id);
            }
        }
    }
}

if($requestMethod == "DELETE"){
    if($requestRessource == "refuser"){
        $idmatch = $_GET["idmatch"];
        $idplayer = $_GET["idplayer"];
        $printInfo = refuserNotif($db, $idmatch, $idplayer);
    }
    if($requestRessource == "delete"){
        $idmatch = $_GET["idmatch"];
        $id = $_SESSION['id'];
        $printInfo = supprimerNotif($db, $idmatch, $id);
    }
}


if ($requestMethod == "GET") {
    if($requestRessource == "mesmatchs"){

        $id = $_SESSION['id'];
        $printInfo = printInfo($db, $id);
    }
    else{
        if($requestRessource == "recherche"){
            $id = $_SESSION['id']; 
            $ville = $_GET["ville"];
            $sport = $_GET["sport"];
            $time = $_GET["time"];
            $comp = $_GET["complet"];
            $printInfo = searchAMatch($db, $ville, $sport, $time, $comp);
        }
        else{
            if($requestRessource == "mesnotifs"){
                $id = $_SESSION['id'];
                $printInfo = printNotif($db, $id);
            }
        }
    }

}

header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('HTTP/1.1 200 OK');

echo json_encode($printInfo);
?>