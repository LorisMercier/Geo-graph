<?php 
if(!isset($_SESSION)){
    session_start();
}

$racine = $_SERVER['DOCUMENT_ROOT'];
$p1 = $racine."/php/libs/UsersDAO.php";
include_once($p1);


function computeUserStat($type, $nomQuiz, $nomCategorie, $infos) {
    $user = $_SESSION['user_pseudo'];
    //recuperation user
    $data = getOneUser($user);

    //calculs nouveau score
    if(isset($data["stats"][$nomCategorie][$nomQuiz][$type])){
        $score = $data["stats"][$nomCategorie][$nomQuiz][$type];
    } else {
        $score = null;
    }
    $newScore = computeScore($score, $infos);
    $data["stats"][$nomCategorie][$nomQuiz][$type] = $newScore;


    $meanCat = computeMean($data, $nomCategorie, $infos);
    $data["stats"][$nomCategorie]['mean'] = $meanCat;


    //réécriture
    deleteUserMETIER($user);
    $users = getAllUsers();

    $users[$user] = $data;

    saveUsers($users);

}

function computeScore($score, $infos){
    //moyenne pondérée
    if ($score) {
        $tmp = ($score["moyenne"] * $score["nbQuestion"] + $infos["pourcentage"] * $infos["nbQuestion"]) / ($score["nbQuestion"] + $infos["nbQuestion"]);
        $score["moyenne"] = round($tmp, 2);

        //maj nb questions
        $score["nbQuestion"] = $score["nbQuestion"] + $infos["nbQuestion"];

        $score["scoreMax"] = max($score["scoreMax"], round($infos['pourcentage'], 2));
    }
    else {
        echo "premiere entrée";
        $score["moyenne"] = round($infos['pourcentage'], 2);
        $score["nbQuestion"] = $infos["nbQuestion"];
        $score["scoreMax"] = round($infos['pourcentage'], 2);
    }

    return $score;
}

function computeMean($data, $nomCategorie, $infos){
    $mean = 0;
    $nbCat = 0;
    if(isset($data["stats"][$nomCategorie]['mean'])){

        foreach($data["stats"][$nomCategorie] as $keyNomQuiz => $val){
            echo "FOREACH1 : ".var_dump($data["stats"][$nomCategorie][$keyNomQuiz]);
            if($keyNomQuiz != "mean"){
                foreach($data["stats"][$nomCategorie][$keyNomQuiz] as $keyVal => $val2){
                    $mean += $data["stats"][$nomCategorie][$keyNomQuiz][$keyVal]["moyenne"];
                    $nbCat++;
                }
            }
        }

        $retour = round($mean / $nbCat, 2);


    } else {
        $retour = round($infos['pourcentage'], 2);
    }

    return $retour;
}


// $infos = ["pourcentage" => 100, "nbQuestion" => 5];
// computeUserStat("LORIS","Les capitales du monde", "Monde", $infos);
// computeMean(getOneUser("LORIS"), "Europe", $infos);