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

// echo "test";
if ($requestMethod == "GET") {
    if ($requestRessource == "register") {
        if (isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["password2"]) && isset($_GET["prenom"]) && isset($_GET["nom"]) && isset($_GET["ville"]) && isset($_GET["photo"])) {

            $email = $_GET["email"];
            $mdp = $_GET["password"];
            $mdp2 = $_GET["password2"];
            $prenom = $_GET["prenom"];
            $nom = $_GET["nom"];
            $ville = $_GET["ville"];
            $photo = $_GET["photo"];
            $data = createUser($db, $email, $mdp, $mdp2, $prenom, $nom, $ville, $photo);

        }
    }
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('HTTP/1.1 200 OK');

echo json_encode($data);
?>