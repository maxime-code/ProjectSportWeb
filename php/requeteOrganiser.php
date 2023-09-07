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

$id = $_SESSION['id'];

if ($requestMethod == "PUT") {      // on modifie le score et le meilleur joueur
    if ($requestRessource == "input") {
        $score = $_GET['score'];
        $meilleur_joueur = $_GET['meilleur_joueur'];
        $idmatch = $_GET['idmatch'];
        $printInfo = addScoreAndBestPlayer($db, $idmatch, $score, $meilleur_joueur);
    }
}

if ($requestMethod == "POST") {

    if ($requestRessource == "old") {
        $printInfo = getOldMatch($db, $id);
    } else {
        if ($requestRessource == "create"){
            $sport = $_GET['sport'];
            $min = $_GET['min'];
            $max = $_GET['max'];
            $adresse = $_GET['adresse'];
            $heure = $_GET['heure'];
            $duree = $_GET['duree'];
            $prix = $_GET['prix'];
            $ville = $_GET['ville'];
    
            // création d'un match
            $printInfo = createMatch($db, $id, $sport, $min, $max, $adresse, $heure, $duree, $prix, $ville);
        }
    }
    // $sport = $_POST['sport'];
    // $min = $_POST['min'];
    // $adresse = $_POST['adresse'];
    // $heure = $_POST['heure'];
    // $duree = $_POST['duree'];
    // $prix = $_POST['prix'];
    // $ville = $_POST['ville'];

    // on récupère les informations
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('HTTP/1.1 200 OK');

echo json_encode($printInfo);
?>
