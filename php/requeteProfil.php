<?php
require_once('database.php');

// Enable all warnings and errors.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connexion.
$db = dbConnect();

// Check the request.
$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_SESSION['id'];
//$request = substr($_SERVER['PATH_INFO'], 1);
//$request = explode('/', $request);
//$requestRessource = array_shift($request);
if ($requestMethod == "GET") {
    $printInfo = getPlayer($db, $id);
}

if ($requestMethod == "POST") {
    $note = $_GET["note"];
    $printInfo = changeNote($db, $id, $note);
}

if ($requestMethod == "PUT") {

    $test = getPlayer($db, $id);

    if($_GET["age"] != NULL){
        $age = $_GET["age"];
    }else{
        $age=$test["results"]["infoPlayer"]["age"];
    }

    if($_GET["mdp"] != NULL){
        $mdp = $_GET["mdp"];
    }else{
        $mdp=$test["results"]["infoPlayer"]["mot_de_passe"];
    }

    if($_GET["photo"] != NULL){
        $photo = $_GET["photo"];
    }else{
        $photo=$test["results"]["infoPlayer"]["photo"];
    }

    if($_GET["ville"] != NULL){
        $ville = $_GET["ville"];
    }else{
        $ville= $test["results"]["ville"]["ville"];
    }

    if($_GET["forme_sportive"] != NULL){
        $forme_sportive = $_GET["forme_sportive"];
    }else{
        $forme_sportive = $test["results"]["infoPlayer"]["forme_sportive"];
    }
    $printInfo = changePlayer($db, $id, $age, $ville, $mdp, $forme_sportive, $photo);

}

header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('HTTP/1.1 200 OK');

echo json_encode($printInfo);
?>