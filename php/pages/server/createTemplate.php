<?php
$racine = $_SERVER['DOCUMENT_ROOT'];
$p0 = "/php/libs/dataMetier/Templates.php";
require_once($racine.$p0);

if(isset($_POST['submitTemplate'])){
    //Nom fichier créé : $_GET['nameQuiz']."Temp.json"
    if ($_POST["attrType"] === "qcm") {
        $content = array(
            "nomQuiz" => $_POST['nomQuiz'],
            "nbQuestion" => intval($_POST['nbQuestion']),
            "nbProposition" => intval($_POST['nbProposition']),
            "varQuestion" => array(
                "attrQuestion" => $_POST['attrQuestion'],
                "attrReponse" => $_POST['attrReponse']
            ),
            "categorie" => $_POST['categorie'],
            "type" => "QCM"
        );
    }
    if ($_POST["attrType"] === "loc") {
        $content = array( 
            "nomQuiz" => $_POST['nomQuiz'],
            "nbQuestion" => intval($_POST['nbQuestion']),
            "nbProposition" => 0,
            "varQuestion" => array(
                "attrQuestion" => $_POST['attrLoc'],
                "attrReponse" => $_POST['attrLoc']
            ),
            "categorie" => $_POST['categorie'],
            "type" => "carte"
        );
    }
    //echo json_encode($content);
    createTemplate($content);
} 

header("Location: /index.php");
?>