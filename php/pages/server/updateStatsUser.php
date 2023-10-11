<?php
if(!isset($_SESSION)){
    session_start();
}

$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/UsersDAO.php";
$p1 = "/php/libs/UsersProfile.php";
require_once($racine.$p0);
require_once($racine.$p1);

echo "UPDATE_STATS_USER \n";

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);



if(isLogged()){
    if(isset($data["quizStats"])){
        echo "envoie\n";
        $infos = array(
            "pourcentage" => $data['pourcentage'],
            "nbQuestion" => $data['nbQuestion']
        );
        computeUserStat($data["type"], $data["nomQuiz"], $data["nomCat"], $infos);    
    }
}
